<?php

require_once dirname(dirname(__FILE__)) . '/config/init.php';

$cli = new Doctrine_Cli(
    array(
        'data_fixtures_path' => PATH_ROOT . DS . 'application'. DS .'doctrine'. DS .'fixtures',
        'models_path' => PATH_ROOT . DS . 'application'. DS . 'models',
        'sql_path' => PATH_ROOT . DS . 'application'. DS .'doctrine'. DS .'sql',
        'yaml_schema_path' => PATH_ROOT . DS . 'application'. DS .'doctrine'. DS .'schemas',
    )
);
$cli->run($_SERVER['argv']);