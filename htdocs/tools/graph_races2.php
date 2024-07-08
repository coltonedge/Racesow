<?php

error_reporting( E_ALL | E_STRICT );
ini_set( 'display_errors', 'off' ); // on for debugging!!!!
date_default_timezone_set( 'Europe/Berlin' );

require_once dirname(dirname(dirname(__FILE__))) . '/config/config.php';
$pdo = Zend_Registry::get('doctrine')->getDbh();

$from = date("Y-m-d 00:00:00", strtotime("-14 days"));
$to = date("Y-m-d 23:59:59", strtotime("-1 days"));
$stmt = $pdo->query("SELECT DATE_FORMAT(created, '%Y-%m-%d') as day, SUM(tries) as num FROM race WHERE created BETWEEN '$from' AND '$to' GROUP BY day");

$linePlot = array();
$tickLabels = array();
while ($record = $stmt->fetchObject()) {
	
	$linePlot[] = $record->num;
	$tickLabels[] = date('d.m.', strtotime($record->day));
}

// always have a plot please :)
if (!count($linePlot)) {
	
	$linePlot[] = 0;
	$linePlot[] = 0;
    $tickLabels[] = 0;
    $tickLabels[] = 0;
}

require_once 'Jpgraph/jpgraph.php';
require_once 'Jpgraph/jpgraph_line.php';
require_once 'Jpgraph/jpgraph_bar.php';

$graph = new Graph(810,200);
$graph->SetScale("textlin");
$graph->SetFrame(false);
$graph->SetMargin(55,20,10,20);
$graph->setColor('#3E3452');
$graph->legend->SetPos(0.1,0.1,'left','bottom');

$graph->xaxis->title->Set('day');
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->setColor('#FF7800');
$graph->xaxis->SetTickLabels($tickLabels);

$graph->yaxis->title->Set('started races');
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->setColor('#FF7800');
$graph->yaxis->HideZeroLabel();

$graph->xgrid->SetColor('#737ABC@0.1');
$graph->xgrid->show();

$graph->ygrid->SetColor('#737ABC@0.1');
$graph->ygrid->show();

$line = new LinePlot($linePlot);
$line->SetColor("#FF7800");
$line->SetWeight(3); 
//$line->SetLegend('points');
$graph->Add($line);

$graph->Stroke();

?>