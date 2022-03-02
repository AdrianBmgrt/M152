<?php

require "lib/boiteaoutils.inc.php";

$pageTitle = "Post";

$message = "";
$commentaire = filter_input(INPUT_POST, 'commentaire');
$action      = filter_input(INPUT_POST, 'action');

switch ($action) {
    case 'submit':
        $nbFile = count($_FILES['imageFile']['name']);
        $target_dir = "img/"; // specifies the directory where the file is going to be placed
        $uploadOk = 1;
        if ($uploadOk == 1) {
            createPost($commentaire, date("Y-m-d H:i:s"));
        }
        for ($i = 0; $i < $nbFile; $i++) {

            $target_file = $target_dir . basename($_FILES["imageFile"]["name"][$i]);

            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["imageFile"]["tmp_name"][$i]);
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
            if ($_FILES["imageFile"]["size"][$i] > 3000000) {
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
                if (move_uploaded_file($_FILES["imageFile"]["tmp_name"][$i], $target_file)) {
                    $message .= "The file " . htmlspecialchars(basename($_FILES["imageFile"]["name"][$i])) . " has been uploaded.\n";
                    createMedia($imageFileType, $_FILES["imageFile"]["name"][$i], date("Y-m-d H:i:s"), getLastId());
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            }
        }
        break;
}

require("views/header.php");

require("views/post.php");

require("views/footer.php");
?>
