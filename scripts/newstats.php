<?php

/**
 * **********************************************************
 * This script computes the racenet stats using only the changes
 * since the last finished runtime.
 * 
 * @version 0.2
 * @date 26.05.2009
 * @author Andreas Linden (zolex) <zlx@gmx.de>
 */

// INIT
error_reporting( E_ALL | E_STRICT );
ini_set( 'display_errors', 'on' );
ini_set('max_execution_time',0);
date_default_timezone_set( 'Europe/Berlin' );

// abort this script in case another instance is already running
$filename = basename(__FILE__);
if (preg_match_all("/php -f .*$filename/", `ps ax`, $test)) {
    if (count($test[0]) >= 2) { // the ps command is a match itsself
        die("anoter instance is already running. aborting current compution...\n\n");
    }
}

// defifition of debug levels
final class DEBUG
{
    const OFF           = 0x0000;
    const RUNTIME       = 0x0001;
    const INFO          = 0x0002;
    const DATA          = 0x0004;
    const QUERIES       = 0x0008;
    const STOREDB       = 0x0010;
    const VERBOSE       = 0x0020;
}

// set debug level
$debug = DEBUG::RUNTIME | DEBUG::INFO;

// allow modifying the debug value via arguments
if (isset($argv[1])) {
    $debug |= (integer)$argv[1]; 
}

if ($debug & DEBUG::STOREDB)
    ob_start();

// FIXME: when running as cronjob, config should not be in developer mode | shutup!  
$_SERVER = array('HTTP_HOST' => 'new.warsow-race.net');

// load config
require_once dirname(dirname(__FILE__)) . '/config/init.php';
$config = Zend_Registry::get('config');

// Database
$db = Zend_Db::factory($config->database);
$db->setFetchMode(Zend_Db::FETCH_OBJ);

// Benchmark
$benchAll = new Racenet_Bench;
$benchAll->start();
$benchPart = new Racenet_Bench;
$benchPart->start();

// Use race archive ?
$useArchive = false;
define('RACE_TABLE', $useArchive ? 'race_archive' : 'race');

///////////////////////////////////////////////////////////////////////////////
// COMPUTION

// Determine and set the start and- end-datetimes between which we
// search for new personal records. Just one single new race would also
// mean a new personal record. The starttime usually is the endtime
// of the script's last successful execution. may be modified for
// testing reasons.

$fix_from = $db->fetchOne("SELECT created FROM ". RACE_TABLE ." ORDER BY created DESC LIMIT 1");
$fix_to = date("Y-m-d H:i:s");
#$fix_to = '2008-06-01 00:00:00';

// required to check if ne need to update the player_history table later
$currentDate = date("Y-m-d");

$maps = array();
$personalRecords = array();

// move races on maps forced to berecomputed to the race archive
// as the recompution for a map is based on the archive. afterward
// select all personal records on those maps from the archive.
$forceMaps = $db->fetchAll("SELECT id FROM map WHERE force_recompution = 'true'");
if (count($forceMaps)) {
        
    foreach ($forceMaps as $forceMap) {
        
        // required schema!
        $maps[$forceMap->id] = $forceMap->id;
    }
    
    if ($useArchive) {
    
	    $qry = "INSERT INTO     race_archive (id, map_id, player_id, time, created)
				SELECT          id, map_id, player_id, time, created
				FROM            race
				WHERE           map_id IN(". implode(',', $maps) .")
				ON DUPLICATE KEY UPDATE time = VALUES(time)";
		$db->query($qry);
		
		$db->query("DELETE FROM race WHERE map_id IN(". implode(',', $maps) .")");
    }
	
    $qry = "SELECT      r.player_id,
                        r.map_id,
                        MIN(r.time) AS time
            FROM        ". RACE_TABLE ." AS r
            JOIN        map AS m ON m.id = r.map_id
            WHERE       m.force_recompution = 'true'
              AND       m.status = 'enabled'
              AND       r.time
            GROUP BY    r.map_id, r.player_id";
    $personalRecords = $db->fetchAll($qry);

    if ($debug & DEBUG::QUERIES) {
        echo '- '.$qry .'<hr>';
        flush();
    }
    
    if ($debug & DEBUG::DATA) {
        echo '- New races on maps forced to be recomputed:';
        echo '<pre>';
        print_r($personalRecords);
        echo '</pre>';
        //exit;
    }
}

// get all new personal records on maps which are not forced to be recomputed
// TODO: join player_map to only select really new personal records but not
// those personal records done in the timespan from $fix_from to $fix_to
$qry = "SELECT      r.player_id,
                    r.map_id,
                    MIN(r.time) AS time,
                    r.created
        FROM        race AS r
        JOIN        map AS m ON m.id = r.map_id
        WHERE       r.created < '". $fix_to ."'
          AND       r.time
          AND       m.status = 'enabled'
        GROUP BY    r.map_id, r.player_id";
$personalRecords = array_merge($personalRecords, $db->fetchAll($qry));
$numRecs = count($personalRecords);

if ($debug & DEBUG::QUERIES) {
    echo '- '.$qry .'<hr>';
    flush();
}

if ($debug & DEBUG::RUNTIME) {
    echo '- selected new personal records in '. $benchPart->diff() ." seconds\n";
    flush();
}

/* // just let it run by now...
if (0 == $numRecs) {
    if ($debug & DEBUG::INFO) {
        echo "\nno new races, aborting compution...\n";
        flush();
    }
    exit;
}
*/

if ($debug & DEBUG::DATA) {
    echo '- New best races per player and map:';
    echo '<pre>';
    print_r($personalRecords);
    echo '</pre>';
    //exit;
}

if ($debug & DEBUG::INFO) {
    echo '- found '. $numRecs . ' personal records until '. $fix_to . "\n";
    flush();
}

// Update the player_map table. If combination of player and
// map already exists only change the record if the new record
// is better than the old one.
$benchPart->start();
foreach ($personalRecords as $record) {
    
    $qry = "SELECT   COUNT(id)
            FROM     race
            WHERE    player_id = ". $record->player_id ."
            AND      map_id = ". $record->map_id;
    $races = (integer)$db->fetchOne($qry);
    
    if ($debug & DEBUG::QUERIES) {
        echo '- '.$qry .'<hr>';
        flush();
    }
    
    $qry = "INSERT INTO player_map (map_id, player_id, time, races, created)
            VALUES (". $record->map_id .", ". $record->player_id .", ". $record->time .", ". $races .", '". $record->created. "')
            ON DUPLICATE KEY UPDATE
            time = IF( VALUES(time) < time OR time = 0 OR time IS NULL, VALUES(time), time ),
            races = VALUES(races),
            created = VALUES(created)";
    $db->query($qry);
    
    if ($debug & DEBUG::QUERIES) {
        echo '- '.$qry .'<hr>';
        flush();
    }
    
    // unique store maps with new records
    $maps[$record->map_id] = $record->map_id;
    
    // usleep(1);
}


// we don't need this anymore!
$personalRecords = NULL;
unset($personalRecords);

if ($debug & DEBUG::RUNTIME) {
    echo '- updated race-records and number of races in player_map in '. $benchPart->diff() ." seconds\n";
    flush();
}

if ($debug & DEBUG::INFO) {
    echo '- '.count($maps) .' maps affected'."\n"; flush();
}

if ($debug & DEBUG::DATA) {
    echo '- Maps with new races since last compution:<pre>';
    print_r($maps);
    echo '<pre>';
}

$benchPart->start();

// For debugging purpose
$affectedPersonalRecords = 0;

// Number of positions per map where points are earned
$max = 30;

// If != 0 it will be the exponent for pow() and
// will cause to not use manual points addition
$newStyle = 0;

// Iterate maps with new personal records to compute new
// points for the players on those maps. this is based on
// the data in the player_map table.
foreach ($maps as $mapId) {
    
    // query the PRE-RANKING: all personal records
    // on the current map ordered by best time
    $qry = "SELECT      player_id,
                        time,
                        NULL as position
            FROM        player_map
            WHERE       map_id = $mapId
              AND       time > 0
              AND       time IS NOT NULL
            ORDER BY    time ASC";
    $preMapRanking = $db->fetchAll($qry);
     
    if($debug & DEBUG::QUERIES) {
        echo '- '.$qry .'<hr>';
        flush();
    }
    
    // Calculate the player's points on each single map
    $position = 0;
    $offset = 0;
    $lastTime = 0;
    $lastPosition = 0;
    
    foreach ($preMapRanking as $preMapPosition)
    {
        // Position offset caused by same race-times
        if ($preMapPosition->time == $lastTime) {
            
            ++$offset;
            $preMapPosition->position = $lastPosition;
            
        }  else {
            
            $preMapPosition->position = $position += $offset + 1;
            $offset = 0;
        }
        
        $lastTime = $preMapPosition->time;
        $lastPosition = $preMapPosition->position;
        
        // Compute the points for the position
        if ($preMapPosition->position > $max) {
            
            $preMapPosition->points = 0; 
        
        } else {
        
            // New style is one single formula. the better
            // the position the highter the points boost
            if ($newStyle) {
                
                $preMapPosition->points = pow($max - $preMapPosition->position + 1, $newStyle);
            
            } else {
            
                $preMapPosition->points = $max - $preMapPosition->position + 1;
                
                // Add points for top positions on each map
                switch ($preMapPosition->position) {
                    case 1:
                        $preMapPosition->points += 10;
                        break;
                        
                    case 2:
                        $preMapPosition->points += 5;
                        break;
                        
                    case 3:
                        $preMapPosition->points += 3;
                        break;
                }
            }
        }
        
        // Update the players points on the map
        $qry = "UPDATE      player_map
                SET         points          = ". $preMapPosition->points .",
                            position        = ". ((integer)$preMapPosition->position ? $preMapPosition->position : 'NULL') ."
                WHERE       player_id       = ". (integer)$preMapPosition->player_id ."
                  AND       map_id          = ". (integer)$mapId;
        $db->query($qry);
        
        $affectedPersonalRecords++;
        if ($debug & DEBUG::QUERIES) {
            echo '- '.$qry .'<hr>';
            flush();
        }
        
        // usleep(1);
    }
    
    // disable the force_recompution flag for the
    // current map when it's compution has finished.
    $qry = "UPDATE map SET force_recompution = 'false' WHERE id = $mapId";
    $db->query($qry);
        
    if($debug & DEBUG::QUERIES) {
        echo '- '.$qry .'<hr>';
        flush();
    }
    
    // usleep(1);
}

if ($debug & DEBUG::RUNTIME) {
    echo '- updated player_maps with new points in '. $benchPart->diff() ." seconds\n";
    flush();
}
if ($debug & DEBUG::INFO) {
    echo '- '.$affectedPersonalRecords .' affected personal records'."\n";
    flush();
}


// see if the current execution is the first on the current day.
// if so save the points to player_history for the last day.
// FIXME: the closer to midnight this script runs, the more precise
// the points for the "last day" are determined. as if we compute
// the points of yesterday on 00:15:00 there might be points earned
// in those 15 minutes of the current day which count to the points
// done yesterday.
$benchPart->start();

$qry = "SELECT      COUNT(*)
        FROM        log_render
        WHERE       TO_DAYS(created) = TO_DAYS('". $currentDate ." 00:00:00')
        LIMIT 1";
$numComputionsToday = (integer)$db->fetchOne($qry);

if (0 == $numComputionsToday) {
    
    // delete outdated history entires
    $db->query("DELETE FROM player_history WHERE date < SUBDATE(CURDATE(),30)");
    
    // add new history entries

    $db->query("INSERT INTO player_history (player_id, date, points, races, maps, playtime)
       SELECT id, SUBDATE(CURDATE(),1), points, races, maps, playtime FROM player
       ON DUPLICATE KEY UPDATE points = VALUES(points), races = VALUES(races), maps = VALUES(maps), playtime = VALUES(playtime)");
    
    if ($debug & DEBUG::RUNTIME) {
        echo '- updated player_history in '. $benchPart->diff() ." seconds\n";
        flush();
    }
}

// ARCHIVING OF RACES :::experimental:::
if ($useArchive) {

    // put races into the archive so race table stays small and can be written fast :)
    if($db->query("INSERT INTO race_archive (id, player_id, map_id, time, created)
        SELECT id, player_id, map_id, time, created FROM race WHERE created < '". $fix_to ."'
        ON DUPLICATE KEY UPDATE time = VALUES(time)")) {
        
        $db->query("DELETE FROM race WHERE 1");
    }
}

// Update player table
$benchPart->start();

$playerIds = $db->fetchAll("SELECT player_id FROM player_map GROUP by player_id");
foreach ($playerIds as $player) {
    
    $races = (integer)$db->fetchOne("SELECT COUNT(id) as value FROM ". RACE_TABLE . " WHERE player_id = ?", $player->player_id);
    $points = (integer)$db->fetchOne("SELECT SUM(points) as value FROM player_map WHERE player_id = ?", $player->player_id);
    $nummaps = (integer)$db->fetchOne("SELECT COUNT(map_id) as value FROM player_map WHERE player_id = ?", $player->player_id);
    $award = (integer)$db->fetchOne("SELECT SUM(value) as value FROM award WHERE player_id = ?", $player->player_id);
    $playtime = (integer)$db->fetchOne("SELECT SUM(playtime) as value FROM player_map WHERE player_id = ? GROUP BY player_id", $player->player_id);
    $diff = (integer)$db->fetchOne("SELECT CAST($points AS SIGNED) - CAST(points AS SIGNED) as value FROM player_history WHERE player_id = ? AND date = SUBDATE(CURDATE(), 1)", $player->player_id);
    $db->query("UPDATE player SET
        races = ". $races. ",
        points = ". $points. ",
        maps = ". $nummaps. ",
        awardval = ". $award .",
        playtime = ". $playtime. ",
        diff_points = ". $diff ."
        WHERE id = ". $player->player_id ." LIMIT 1");
}

if ($debug & DEBUG::QUERIES) {
    echo '- '.$qry .'<hr>';
    flush();
}
if ($debug & DEBUG::RUNTIME) {
    echo '- updated player table in '. $benchPart->diff() ." seconds\n";
    flush();
}


// Update maps table
$benchPart->start();
if (count($maps)) {

    $qry = "UPDATE      map
            SET         races = (SELECT COUNT(r.id) FROM ". RACE_TABLE . " as r WHERE r.map_id = map.id LIMIT 1),
                        playtime = (SELECT SUM(pm.playtime) FROM player_map AS pm WHERE pm.map_id = map.id LIMIT 1),
                        downloads = (SELECT COUNT(d.id) FROM log_download AS d WHERE d.map_id = map.id LIMIT 1)
            WHERE       id IN(". implode(',', $maps) .")";
              
    $db->query($qry);

    if ($debug & DEBUG::QUERIES) {
        echo '- '.$qry .'<hr>';
        flush();
    }
    if ($debug & DEBUG::RUNTIME) {
        echo '- updated map table in '. $benchPart->diff() ." seconds\n";
        flush();
    }
    if ($debug & DEBUG::RUNTIME) {
        echo 'all together took '. $benchAll->diff() ." seconds\n";
        flush();
    }
}

// log rendering

if($debug & DEBUG::STOREDB )
    $info = ob_get_clean();
else
    $info = 'info was printed to stdout';

    
// store time for next calulation
$qry = "INSERT INTO log_render (time, info, created ) VALUES (". $benchAll->diff() .", '". $info ."', '". $fix_to ."' )";
$db->query($qry);
