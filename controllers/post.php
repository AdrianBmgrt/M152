<?php

require "lib/boiteaoutils.inc.php";

$pageTitle = "Post";

$message = "";
$commentaire = filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_STRING);
$action      = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

switch ($action) {
    case 'submit':
        $target_dir = "img/"; // specifies the directory where the file is going to be placed
        $target_file = $target_dir . basename($_FILES["imageFile"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["imageFile"]["tmp_name"]);
            if ($check !== false) {
                $message = "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $message = "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $message = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["imageFile"]["size"] > 3000000) {
            $message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $message = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            createPost($commentaire, date("Y-m-d H:i:s"));
            createMedia($imageFileType, $_FILES["imageFile"]["name"], date("Y-m-d H:i:s"));
            
            if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
                $message = "The file " . htmlspecialchars(basename($_FILES["imageFile"]["name"])) . " has been uploaded.";
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
            
        }
        break;
}

require("views/header.php");

require("views/post.php");

require("views/footer.php");
var_dump($_FILES);
var_dump($target_file);
?>
