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
        define('limitFileSize', 3 * 1024 * 1024);
        $alreadyLoop = 0;
        $uploadOk = 1;
        $uploadOkPost = 1;
        
        for ($i = 0; $i < $nbFile; $i++) {

            $target_file = $target_dir . basename($_FILES["imageFile"]["name"][$i]);

            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $uniqueName = uniqid() .".". $imageFileType;


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

            /*
            // Check if file already exists
            if (file_exists($target_file)) {
                $message .= "Sorry, file already exists.";
                $uploadOk = 0;
            }*/

            // Check file size
            if ($_FILES["imageFile"]["size"][$i] > limitFileSize) {
                $message .= "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $message .= "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["imageFile"]["tmp_name"][$i], $target_dir . $uniqueName)) {
                    $message .= "The file " . htmlspecialchars(basename($_FILES["imageFile"]["name"][$i])) . " has been uploaded.\n";
                    createMediaAndPost($imageFileType, $uniqueName , date("Y-m-d H:i:s"), $commentaire, $alreadyLoop);
                    $alreadyLoop = 1;
                } else {
                    $message .= "Sorry, there was an error uploading your file.";
                }
            }
        }
        break;
}

require("views/header.php");

require("views/post.php");

require("views/footer.php");
?>
