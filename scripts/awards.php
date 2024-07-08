<?php

require_once dirname(dirname(__FILE__)) . '/config/init.php';
$pdo = Zend_Registry::get('doctrine')->getDbh();

if ($argc != 2) die("no span given");
$span = $argv[1];

if ($span == 'week') $diff = 8;
else if ($span == 'month') $diff = 29;
else die("invalid span given");

$fromTime = strtotime("-$diff days 00:00:00");
$fromDate = date("Y-m-d", $fromTime);
$fromDateTime = date("Y-m-d H:i:s", $fromTime);

$toTime = strtotime("-1 days 23:59:59");
$toDate = date("Y-m-d", $toTime);
$toDateTime = date("Y-m-d H:i:s", $toTime);

/**
 * Races Award
 */
$query = "
	SELECT `player_id`, COUNT(`id`) as `num`
	FROM `race`
	WHERE `created` BETWEEN '$fromDateTime' AND '$toDateTime'
	GROUP BY `player_id`
	ORDER BY `num` DESC
	LIMIT 1";

$stmt = $pdo->query($query);
$result = $stmt->fetchObject();

$award = new Award;
$award->date = $toDate;
$award->value = $result->num;
$award->player_id = $result->player_id;
$award->type = 'races_' . $span;
$award->save();

print_r($award->toArray());

$query = "
	UPDATE `player`
	SET `awardval` = (SELECT SUM(`value`) FROM `award` WHERE `player_id` = `player`.`id`)
	WHERE `id` = $award->player_id
	LIMIT 1";
	
$pdo->query($query);

/**
 * Points Award
 */
$query = "
	SELECT `player_id`,
			(`points`- (SELECT `points` FROM `player_history` WHERE `player_id` = `ph1`.`player_id` AND `date` = '$fromDate')) AS `diff`
	FROM `player_history` AS `ph1`
	WHERE `date` = '$toDate'
	ORDER BY `diff` DESC
	LIMIT 1";

$stmt = $pdo->query($query);
$result = $stmt->fetchObject();

$award = new Award;
$award->date = $toDate;
$award->value = $result->diff;
$award->player_id = $result->player_id;
$award->type = 'points_' . $span;
$award->save();

print_r($award->toArray());

$query = "
	UPDATE `player`
	SET `awardval` = (SELECT SUM(`value`) FROM `award` WHERE `player_id` = `player`.`id`)
	WHERE `id` = $award->player_id
	LIMIT 1";
	
$pdo->query($query);