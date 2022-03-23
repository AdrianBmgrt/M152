<?php


$pageTitle = "Home";
$id = $_POST["id"];

$action = filter_input(INPUT_POST, 'action');

switch ($action) {
    case 'yes':
        
        break;    
    case 'no':
        
        break;
}

require "lib/boiteaoutils.inc.php";

require("views/header.php");

require("views/home.php");

require("views/footer.php");

?>
