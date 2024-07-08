<?php

/**
 * Controller for the Homepage
 *
 * @uses       Racenet_Controller_Action 
 * @copyright  
 * @license    
 */
class Admin_RacesController extends Racenet_Controller_Action
{
    /**
     * Define acl for the controller
     *
     */
    protected $_acl = array(
        "controller" => AclRacenet::ADMIN_MAPS,
        "forward" => array("index", "application")
    );

    /**
     * indexAction
     *
     */
    public function indexAction()
    {
		if ($this->getRequest()->isPost()) {
		
			header("Location: ". $this->view->url(array('playerfilter' => $this->_getParam('playerfilter'), 'mapfilter' => $this->_getParam('mapfilter'))));
			exit;
		}
	
		$query = Doctrine_Query::create()
			->from("Race")
			->leftJoin("Race.Player player")
			->leftJoin("Race.Map map")
			->orderBy("created DESC");
			
		if ($player = $this->_getParam('playerfilter')) {
		
			$query->andWhere('player.simplified LIKE ?', "%$player%");
			$this->view->playerFilter = $player;
		}
		
		if ($map = $this->_getParam('mapfilter')) {
		
			$query->andWhere('map.name = ?', $map);
			$this->view->mapFilter = $map;
		}
		
        $adapter = new Racenet_Paginator_Adapter_DoctrineQuery($query);
        
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(30);
        $paginator->setCurrentPageNumber($this->_getParam("page", 1));
		
        $this->view->paginator = $paginator;
		$this->view->page = $this->_getParam("page", 1);
    }
	
	public function deleteAction()
	{
		$raceId = $this->_getParam('id');
		
		$pdo = Zend_Registry::get('doctrine')->getDbh();
		$stmt = $pdo->query("SELECT player_id, map_id, server_id, time, created FROM race WHERE id = $raceId LIMIT 1;");
                $delRace = $stmt->fetchObject();

		/*
		* insert deleted race into delete_races table
		*/
		$pdo->query("INSERT INTO `deleted_race` SELECT * FROM race WHERE id = $raceId LIMIT 1;");

		
		$pdo->query("UPDATE player SET races = races-1 WHERE id = $delRace->player_id LIMIT 1");
		$pdo->query("UPDATE map SET races = races-1 WHERE id = $delRace->map_id");
		$pdo->query("UPDATE gameserver SET races = races-1 WHERE id = $delRace->server_id");
		
		$pdo->query("DELETE FROM race WHERE id = $raceId LIMIT 1;");

		$stmt = $pdo->query("SELECT *
			FROM race
			WHERE map_id = $delRace->map_id
			  AND player_id = $delRace->player_id
			ORDER BY time ASC
			LIMIT 1;");
		if ($bestRace = $stmt->fetchObject()) {
		
			$pdo->query("UPDATE `player_map`
			SET `time` = $bestRace->time,
				`prejumped` = '$bestRace->prejumped',
				`server_id` = $bestRace->server_id,
				`created` = '$bestRace->created',
				`tries` = (SELECT SUM(`tries`) FROM `race` WHERE `player_id` = $delRace->player_id AND `map_id` = $delRace->map_id AND `tries` IS NOT NULL),
				`duration` = (SELECT SUM(`duration`) FROM `race` WHERE `player_id` = $delRace->player_id AND `map_id` = $delRace->map_id AND `duration` IS NOT NULL)
			WHERE map_id = $delRace->map_id
			  AND player_id = $delRace->player_id
			LIMIT 1;");
			
		} else {
		
			$pdo->query("DELETE FROM player_map WHERE player_id = $delRace->player_id AND map_id = $delRace->map_id LIMIT 1;");
		}
		
		
		$stmt = $pdo->query("SELECT `p`.`id`, `pm`.`time`, `p`.`name`, `pm`.`races`, `pm`.`playtime`, `pm`.`created`, `pm`.`prejumped`, `pm`.`points`, `pm`.`map_id`, `pm`.`player_id`
			FROM `player_map` `pm`
			INNER JOIN `player` `p` ON `p`.`id` = `pm`.`player_id`
			WHERE `pm`.`time` IS NOT NULL
			  AND `pm`.`time` > 0
			  AND `pm`.`map_id` = $delRace->map_id
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
		
		header("Location: /admin/races/index/page/". $this->_getParam("page") . "/mapfilter/". $this->_getParam('mapfilter') . "/playerfilter/". $this->_getParam('playerfilter'));
		exit;
		
	}
}
