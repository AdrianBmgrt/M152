<?php

require "lib/boiteaoutils.inc.php";

$pageTitle = "Home";

require("views/header.php");

require("views/home.php");

require("views/footer.php");

var_dump(readPostAndMediaWithId(2));
$arrayImages = readPostAndMediaWithId($i);
var_dump($arrayImages[1]["commentaire"]);

?>
