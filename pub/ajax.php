<?php

// autoload
function my_autoload ($pClassName) {
    include(__DIR__ . "/../class/" . $pClassName . ".php");
}
spl_autoload_register("my_autoload");


// load required objects
$exception = new OcunException();


$ajax = new OcunAjax($exception);
$action = $_GET['action'] ?? 'default';
echo $ajax->$action();

 ?>
