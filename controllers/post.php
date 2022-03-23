<?php

require "lib/boiteaoutils.inc.php";

$pageTitle = "Post";

$message = "";
$commentaire = filter_input(INPUT_POST, 'commentaire');
$action      = filter_input(INPUT_POST, 'action');

switch ($action) {
    case 'submit':
        if (createMediaAndPost(date("Y-m-d H:i:s"), $commentaire, $_FILES)) {
            $message .= "All the files has been uploaded.\n";
        } else {
            $message .= "Sorry, there was an error uploading your file.";
        }
        header('Location: ?action=home');
        break;
}

require("views/header.php");

require("views/post.php");

require("views/footer.php");

var_dump(count($_FILES["imageFile"]));
