<?php
require 'lib/boiteaoutils.inc.php';

$id = $_GET["id"];

// Initialiser les variables | Récupère les données et les filtres
$id = FILTER_INPUT(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$commande = filter_input(INPUT_POST, "btnSubmit");

if ($commande == "Yes") {
    if (DeleteMediaAndPost($id)) {
        $errorMsg = "Le post a été supprimée";
        header("location: ?action=home");
    } else {
        $errorMsg = "Un problème est survenu lors de la suppression du post";
    }
}

require("views/delete.php");
?>