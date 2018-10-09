<?php
ob_start();
require_once __DIR__ ."/Facebook/autoload.php";

$fb = new \Facebook\Facebook ( [
	'app_id' => '317218968770347',
	'app_secret' => '0cf0f80fc6d34fbf544fb46ec6e74fe0',
	'default_graph_version' => 'v2.12'
	]);