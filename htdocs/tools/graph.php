<?php

error_reporting( E_ALL | E_STRICT );
ini_set( 'display_errors', 'off' ); // on for debugging!!!!
date_default_timezone_set( 'Europe/Berlin' );

require_once dirname(dirname(dirname(__FILE__))) . '/config/config.php';



switch ($_GET['type']) {
	
	case 'playerPointsHistory':
		$historyQuery = Doctrine_query::create()
		    ->from('PlayerHistory')
		    ->orderBy('date DESC')
		    ->where('player_id = ?', $_GET['id'])
		    ->limit(20);
		
		$linePlot = array();
		$tickLabels = array();
		foreach($historyQuery->execute() as $record) {
			
			$linePlot[] = $record->points;
			$tickLabels[] = date('d.m.', strtotime($record->date));
		}
		
		$linePlot = array_reverse($linePlot);
		$tickLabels = array_reverse($tickLabels);
        break;
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

$graph->yaxis->title->Set('points' );
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