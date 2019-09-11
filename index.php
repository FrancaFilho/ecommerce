<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;
use \franca\Page;
use \franca\PageAdm;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});
$app->get('/adm', function() {
    
	$page = new PageAdm();

	$page->setTpl("index");

});

$app->run();

 ?>