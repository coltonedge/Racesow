<?php

require_once dirname(dirname(__FILE__)) . '/config/init.php';
$pdo = Zend_Registry::get('doctrine')->getDbh();

$handle = fopen ("/home/zaran/wswconsole.log", "r");
$lastInsertedMap = null;
while (!feof($handle)) {

    $buffer = fgets($handle, 4096);
	if (!$lastInsertedMap && preg_match("/INSERT INTO `map` \(`name`, `created`\) VALUES\('(.+)', NOW\(\)\);/", $buffer, $regs)) {
	
		$lastInsertedMap = $regs[1];
		continue;
	}
	
	if ($lastInsertedMap && preg_match("/SELECT `p`.`id`, `pm`.`time`, `p`.`name`, `pm`.`races`, `pm`.`playtime`, `pm`.`created`, `pm`.`prejumped`, `pm`.`points` FROM `player_map` `pm` INNER JOIN `player` `p` ON `p`.`id` = `pm`.`player_id` WHERE `pm`.`time` IS NOT NULL AND `pm`.`time` > 0 AND `pm`.`map_id` = (\d+) AND `pm`.`prejumped` in \(.+\) ORDER BY `pm`.`time` ASC;/", $buffer, $regs)) {
	
		$properMapId = $regs[1];
		$stmt = $pdo->query("SELECT * FROM map WHERE name = '". $lastInsertedMap ."' LIMIT 1");
		if (!$wrongMap = $stmt->fetchObject()) {
		
			$pdo->query("INSERT INTO map (id, name) VALUES(". $properMapId .", '". $lastInsertedMap ."');");
		
		} else {
		
			$pdo->query("UPDATE map SET id = ". $properMapId ." WHERE id = ". $wrongMap->id . " LIMIT 1;");
			$pdo->query("UPDATE race SET map_id = ". $properMapId . " WHERE map_id = ". $wrongMap->id . ";");
			
			$stmt = $pdo->query("SELECT * FROM player_map WHERE map_id = ". $wrongMap->id . ";");
			while ($wrongPersonalRecord = $stmt->fetchObject()) {
			
				$stmt2 = $pdo->query("SELECT * FROM player_map WHERE map_id = ". $properMapId ." AND player_id = ". $wrongPersonalRecord->player_id . ";");
				if ($properPersonalRecord = $stmt2->fetchObject()) {
				
					if ($wrongPersonalRecord->time < $properPersonalRecord->time) {
					
					//	$pdo->query("UPDATE player_map SET time = ". $wrongPersonalRecord->time . " WHERE map_id = ". $properMapId . " AND player_id = ". $wrongPersonalRecord->player_id . ";");
					}
					
					$pdo->query("DELETE FROM player_map WHERE player_id = ". $wrongPersonalRecord->player_id . " AND map_id = ". $wrongPersonalRecord->player_id ." LIMIT 1;");
					
				} else {
			
					$pdo->query("UPDATE player_map SET map_id = ". $properMapId . " WHERE map_id = ". $wrongMap->id . " AND player_id = ". $wrongPersonalRecord->player_id . ";");
				}
			}
			
			$stmt = $pdo->query("SELECT `p`.`id`, `pm`.`time`, `p`.`name`, `pm`.`races`, `pm`.`playtime`, `pm`.`created`, `pm`.`prejumped`, `pm`.`points`, `pm`.`map_id`, `pm`.`player_id`
				FROM `player_map` `pm`
				INNER JOIN `player` `p` ON `p`.`id` = `pm`.`player_id`
				WHERE `pm`.`time` IS NOT NULL
				  AND `pm`.`time` > 0
				  AND `pm`.`map_id` = $properMapId
				  AND `pm`.`prejumped` in ('true', 'false')
				ORDER BY `pm`.`time` ASC;");

			$bestTime = 0;
			$lastRaceTime = 0;
			$offset = 0;
			$cleanOffset = 0;
			$lastCleanRaceTime = 0;
			$currentPosition = 0;
			$currentCleanPosition = 0;
			$realPosition = 0;
			$maxPositions = 30;
			$affectedPlayerIds = array();
			
			while($personalRecord = $stmt->fetchObject()) {

				if ($bestTime == 0)
					$bestTime = $personalRecord->time;

				if ($personalRecord->time == $lastRaceTime)
					$offset++;
				else
					$offset = 0;

				if ( $personalRecord->prejumped == 'false' && $personalRecord->time == $lastCleanRaceTime )
					$cleanOffset++;
				else
					$cleanOffset = 0;

				$currentPosition++;
				if ( $personalRecord->prejumped == 'false' )
				{
					$currentCleanPosition++;
					$realPosition = $currentCleanPosition - $cleanOffset;
					$lastCleanRaceTime = $personalRecord->time;
				}
				else
				{
					$realPosition = $currentPosition - $offset;
				}

				$points = ($maxPositions + 1) - $realPosition;
				switch ($realPosition)
				{
					case 1:
						$points += 10;
						break;
					case 2:
						$points += 5;
						break;
					case 3:
						$points += 3;
						break;
				}

				$points = $points > 0 ? $points : 0;
				
				$lastRaceTime = $personalRecord->time;
				
				//only update points for players whose points have changed
				if ( $personalRecord->points != $points )
				{
					// set points in player_map
					$pdo->query("UPDATE `player_map` SET `points` = $points WHERE `map_id` = $personalRecord->map_id AND `player_id` = $personalRecord->player_id;");
					$affectedPlayerIds[] = $personalRecord->player_id;
				}	
			}
			
			if (count($affectedPlayerIds)) {
			
				$pdo->query("UPDATE `player` SET `points` = (SELECT SUM(`points`) FROM `player_map` WHERE `player_id` = `player`.`id`), `diff_points` = (`points` - (SELECT `points` FROM `player_history` WHERE `player_id` = `player`.`id` ORDER BY `date` DESC LIMIT 1)) WHERE `id` IN(". join(',', $affectedPlayerIds) .");");
			}
		}
		
		$lastInsertedMap = null;
	}
}
fclose ($handle);