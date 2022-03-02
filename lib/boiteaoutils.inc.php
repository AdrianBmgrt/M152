<?php
/*
  Date       : Février 2022
  Auteur     : Adrian Baumgartner
  Sujet      : Librairie de fonctions php
 */

require "constantesDB.inc.php";

/**
 * Connecteur de la base de données du .
 * Le script meurt (die) si la connexion n'est pas possible.
 * @staticvar PDO $dbc
 * @return \PDO
 */
function m152DB()
{
    static $pokedexConnector = null;

    if ($pokedexConnector == null) {

        try {
            $pokedexConnector = new PDO('mysql: ' . DBNAME . ';hostname= ' . HOSTNAME, DBUSER, PASSWORD, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_PERSISTENT => true
            ));
        } catch (PDOException $Exception) {
            // PHP Fatal Error. Second Argument Has To Be An Integer, But PDOException::getCode Returns A
            // String.
            error_log($Exception->getMessage());
            error_log($Exception->getCode());
            die('Could not connect to MySQL');
        }
    }
    return $pokedexConnector;
}


/**
 * Retourne les données d'un pokémon en fonction de son idPokemon
 * @param mixed $idPokemon
 * @return false|array 
 */
function readAllPostAndMedia()
{
    static $ps = null;
    $sql = 'SELECT m.idPost, m.nomMedia, p.commentaire';
    $sql .= ' FROM m152.media as m, m152.post as p ';
    $sql .= ' WHERE p.idPost = m.idPost ';

    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        if ($ps->execute())
            $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Retourne les données d'un pokémon en fonction de son idPokemon
 * @param mixed $idPokemon
 * @return false|array 
 */
function readPostAndMediaWithId($id)
{
    static $ps = null;
    $sql = 'SELECT m.idPost, m.nomMedia, p.commentaire';
    $sql .= ' FROM m152.media as m, m152.post as p ';
    $sql .= ' WHERE m.idPost = p.idPost AND p.idPost = :ID ';

    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':ID', $id, PDO::PARAM_STR);
        if ($ps->execute())
            $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Retourne les données d'un pokémon en fonction de son idPokemon
 * @param mixed $idPokemon
 * @return false|array 
 */
function getCountFromDifferentIdPost()
{
    static $ps = null;
    $sql = 'SELECT m.idPost, count(*) ';
    $sql .= ' FROM m152.media as m ';
    $sql .= ' GROUP BY idPost;';

    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        if ($ps->execute())
            $answer = $ps->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

function PostAndMediaToCarousel()
{
    $html = "";
    $array = getCountFromDifferentIdPost();
    if (!empty($array)) {
        // Chaque ligne
        for ($i=1; $i < getLastId() + 1 ; $i++) 
        {
            $arrayImages = readPostAndMediaWithId($i);
            $html .= "\n <div class=\"panel panel-default\">";
            $html .= "\n <div id=\"my-pics\" class=\"carousel slide\" data-ride=\"carousel\" style=\"margin:auto;\">";

            $html .= "\n <ol class=\"carousel-indicators\">";

            for ($j=0 ; $j < $array["count(*)"] + 1 ; $j++ ) { 
                $html .= "\n <li data-target=\"#my-pics$i\" data-slide-to=\"$i\" class=\"active\"></li>";
            }

            $html .= "\n </ol>";
            $html .= "\n <div class=\"carousel-inner\" role=\"listbox\">";

            for ($k=0; $k < $array["count(*)"] + 1; $k++) { 
            $html .= "\n <div class=\"item active\">";
            $html .= "\n <img src=\"img/".$arrayImages["nomMedia"]."\" alt=\"".$arrayImages["nomMedia"]."\">";
            $html .= "\n </div>";
            }
            $html .= "\n </div>";

            $html .= "\n <a class=\"left carousel-control\" href=\"#my-pics\" role=\"button\" data-slide=\"prev\">";
            $html .= "\n <span class=\"icon-prev\" aria-hidden=\"true\"></span>";
            $html .= "\n <span class=\"sr-only\">Previous</span>";
            $html .= "\n </a>";

            $html .= "\n <a class=\"right carousel-control\" href=\"#my-pics\" role=\"button\" data-slide=\"next\">";
            $html .= "\n <span class=\"icon-next\" aria-hidden=\"true\"></span>";
            $html .= "\n <span class=\"sr-only\">Next</span>";
            $html .= "\n </a>";

            $html .= "\n </div>";
            $html .= "\n <div class=\"panel-body\">";
            $html .= "\n <hr>";
            $html .= "\n " . $arrayImages["commentaire"];
            $html .= "\n </div>";
            $html .= "\n </div>";
        }

        
    }
    return $html;
}

/**
 * Ajoute une nouvelle post avec ses paramètres
 * @param mixed $commentaire Commentaire du post
 * @param mixed $creationDate  La date de création du post
 * @return bool true si réussi
 */
function createPost($commentaire, $creationDate)
{
    static $ps = null;
    $sql = "INSERT INTO `m152`.`post` (`commentaire`, `creationDate`) ";
    $sql .= "VALUES (:COMMENTAIRE, :CREATIONDATE)";
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':COMMENTAIRE', $commentaire, PDO::PARAM_STR);
        $ps->bindParam(':CREATIONDATE', $creationDate, PDO::PARAM_STR);
        $answer = $ps->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Met à jour une note existante 
 * @param mixed $idPost
 * @param mixed $commentaire
 * @param mixed $modificationDate 
 * @return bool 
 */
function updatePost($idPost, $commentaire, $modificationDate)
{
    static $ps = null;

    $sql = "UPDATE `m152`.`post` SET ";
    $sql .= "`commentaire` = :COMMENTAIRE, ";
    $sql .= "`modificationDate` = :MODIFICATIONDATE, ";
    $sql .= "WHERE (`idPost` = :IDPOST)";
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':IDPOST', $idPost, PDO::PARAM_INT);
        $ps->bindParam(':COMMENTAIRE', $commentaire, PDO::PARAM_STR);
        $ps->bindParam(':MODIFICATIONDATE', $modificationDate, PDO::PARAM_STR);
        $ps->execute();
        $answer = ($ps->rowCount() > 0);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Supprime la post avec l'id $idPost.
 * @param mixed $idPost
 * @return bool 
 */
function deletePost($idPost)
{
    static $ps = null;
    $sql = "DELETE FROM `m152`.`post` WHERE (`idPost` = :IDPOST);";
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':IDPOST', $idPost, PDO::PARAM_INT);
        $ps->execute();
        $answer = ($ps->rowCount() > 0);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Ajoute une nouvelle média avec ses paramètres
 * @param mixed $typeMedia Le type du média
 * @param mixed $nomMedia Le nom du média
 * @param mixed $creationDate  La date de création du média
 * @return bool true si réussi
 */
function createMedia($typeMedia, $nomMedia, $creationDate, $id)
{
    static $ps = null;
    $sql = "INSERT INTO `m152`.`media` (`typeMedia`, `nomMedia`, `creationDate`, `idPost`) ";
    $sql .= "VALUES (:TYPEMEDIA, :NOMMEDIA, :CREATIONDATE, :IDPOST)";
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':TYPEMEDIA', $typeMedia, PDO::PARAM_STR);
        $ps->bindParam(':NOMMEDIA', $nomMedia, PDO::PARAM_STR);
        $ps->bindParam(':CREATIONDATE', $creationDate, PDO::PARAM_STR);
        $ps->bindParam(':IDPOST', $id, PDO::PARAM_INT);
        $answer = $ps->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Met à jour une média existante 
 * @param mixed $idPost
 * @param mixed $commentaire
 * @param mixed $modificationDate 
 * @return bool 
 */
function updateMedia($idMedia, $typeMedia, $nomMedia, $modificationDate)
{
    static $ps = null;

    $sql = "UPDATE `m152`.`media` SET ";
    $sql .= "`typeMedia` = :TYPEMEDIA, ";
    $sql .= "`nomMedia` = :COMMENTAIRE, ";
    $sql .= "`modificationDate` = :MODIFICATIONDATE, ";
    $sql .= "WHERE (`idMedia` = :IDMEDIA)";
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':IDMEDIA', $idMedia, PDO::PARAM_INT);
        $ps->bindParam(':TYPEMEDIA', $typeMedia, PDO::PARAM_STR);
        $ps->bindParam(':NOMMEDIA', $nomMedia, PDO::PARAM_STR);
        $ps->bindParam(':MODIFICATIONDATE', $modificationDate, PDO::PARAM_STR);
        $ps->execute();
        $answer = ($ps->rowCount() > 0);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Supprime la note avec l'id $idMedia.
 * @param mixed $idMedia
 * @return bool 
 */
function deleteMedia($idMedia)
{
    static $ps = null;
    $sql = "DELETE FROM `m152`.`media` WHERE (`idMedia` = :IDMEDIA);";
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':IDMEDIA', $idMedia, PDO::PARAM_INT);
        $ps->execute();
        $answer = ($ps->rowCount() > 0);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

function getLastId()
{
    static $ps = null;
    $sql = 'SELECT MAX(idPost) ';
    $sql .= 'FROM m152.post';
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        if ($ps->execute())
            $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    //return $answer[0]["idPost"];
    return $answer[0]["MAX(idPost)"];
}
