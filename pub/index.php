<!-- This is a test page -->

<?php

// autoload
function my_autoload ($pClassName) {
    include(__DIR__ . "/../class/" . $pClassName . ".php");
}
spl_autoload_register("my_autoload");


// load required objects
$exception = new OcunException();
//$database = new OcunDataBase($ex);
//$query = new OcunQuery($db, 3);
$controller = new OcunController($exception);

// start session
session_start();

// load templates
$action = $_GET['action'] ?? 'welcome';
echo $controller->$action();



?>
