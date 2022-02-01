<?php

$action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_URL);
if (empty($action)) {
    $action = 'home';
}

switch ($action) {
    case 'home':
        require("../Chapitre1/controllers/home.php");
        break;
    case 'post':
        require("../Chapitre1/controllers/post.php");
        break;
}

?>