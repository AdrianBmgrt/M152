<?php


$action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_URL);
if (empty($action)) {
    $action = 'home';
}

switch ($action) {
    case 'home':
        require("controllers/home.php");
        break;
    case 'post':
        require("controllers/post.php");
        break;
}

?>