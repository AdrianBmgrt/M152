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
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
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
    $sql = 'SELECT m.idPost, m.nomMedia, p.commentaire, m.creationDate';
    $sql .= ' FROM m152.media as m, m152.post as p ';
    $sql .= ' WHERE p.idPost = m.idPost ';
    $sql .= ' ORDER BY m.creationDate DESC';

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
 * Retourne les données d'une image en fonction de son idPokemon
 * @param mixed $idPokemon
 * @return false|array 
 */
function readPostAndMediaWithId($id)
{
    static $ps = null;
    $sql = 'SELECT m.idPost, m.nomMedia, m.typeMedia, p.commentaire ';
    $sql .= 'FROM m152.media as m, m152.post as p ';
    $sql .= 'WHERE m.idPost = p.idPost AND p.idPost = :ID ';

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

/*
function PostAndMediaToCarousel()
{
    $html = "";
    $array = getCountFromDifferentIdPost();
    if (!empty($array)) {
        // Chaque ligne
        for ($i = getLastId(); $i > 0; $i--) {
            $arrayMedia = readPostAndMediaWithId($i);

            for ($k = 0; $k < $array[$i]["count(*)"]; $k++) {
                var_dump($arrayMedia[$k]["typeMedia"]);

                if ($arrayMedia[$k]["typeMedia"] == "mp4" || $arrayMedia[$k]["typeMedia"] == "m4v") {
                    $html .= "\n <video width=\"100%\" height=\"100%\" autoplay muted loop>";
                    $html .= "\n <source src=\"" . $arrayMedia[$k]["nomMedia"] . "\" type=\"video/mp4\">";
                    $html .= "\n </video>";
                }
                if ($arrayMedia[$k]["typeMedia"] == "png" || $arrayMedia[$k]["typeMedia"] == "jpg" || $arrayMedia[$k]["typeMedia"] == "jpeg" || $arrayMedia[$k]["typeMedia"] == "gif" || $arrayMedia[$k]["typeMedia"] == "jpg") {
                    $html .= "\n <div class=\"panel panel-default\">";
                    $html .= "\n <div id=\"my-pics$k\" class=\"carousel slide\" data-ride=\"carousel\" data-interval=\"false\" style=\"margin:auto;\" >";

                    $html .= "\n <ol class=\"carousel-indicators\">";

                        $html .= "\n <li data-target=\"#my-pics$k\" data-slide-to=\"$k\" class=\"active\"></li>";

                    $html .= "\n </ol>";
                    $html .= "\n <div class=\"carousel-inner\" role=\"listbox\">";

                        if ($k == 0) {
                            $html .= "\n <div class=\"item active\">";
                        } else {
                            $html .= "\n <div class=\"item\">";
                        }
                        $html .= "\n <img src=\"img/" . $arrayMedia[$k]["nomMedia"] . "\" alt=\"" . $arrayMedia[$k]["nomMedia"] . "\">";
                        $html .= "\n </div>";
                    $html .= "\n </div>";
                }
            }
            $html .= "\n <a class=\"left carousel-control\" href=\"#my-pics$i\" role=\"button\" data-slide=\"prev\">";
            $html .= "\n <span class=\"icon-prev\" aria-hidden=\"true\"></span>";
            $html .= "\n <span class=\"sr-only\">Previous</span>";
            $html .= "\n </a>";

            $html .= "\n <a class=\"right carousel-control\" href=\"#my-pics$i\" role=\"button\" data-slide=\"next\">";
            $html .= "\n <span class=\"icon-next\" aria-hidden=\"true\"></span>";
            $html .= "\n <span class=\"sr-only\">Next</span>";
            $html .= "\n </a>";

            $html .= "\n </div>";

            $html .= "\n <div class=\"panel-body\">";
            $html .= "\n <hr>";
            $html .= "\n " . $arrayMedia[0]["commentaire"];
            $html .= "\n <a><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></a>";
            $html .= "\n <a><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></a>";
            $html .= "\n </div>";

            $html .= "\n </div>";
        }
    }
    return $html;
}
*/

function PostAndMediaToCarousel()
{
    $html = "";
    $array = getCountFromDifferentIdPost();
    if (!empty($array)) {
        // Chaque ligne
        for ($i = getLastId(); $i > 0; $i--) {
            $arrayMedia = readPostAndMediaWithId($i);
            $html .= "\n <div class=\"panel panel-default\">";
            $html .= "\n <div id=\"my-pics$i\" class=\"carousel slide\" data-ride=\"carousel\" data-interval=\"false\" style=\"margin:auto;\" >";

            $html .= "\n <ol class=\"carousel-indicators\">";

            for ($j = 1; $j < $array[$i]["count(*)"] + 1; $j++) {
                $html .= "\n <li data-target=\"#my-pics$i\" data-slide-to=\"$i\" class=\"active\"></li>";
            }

            $html .= "\n </ol>";
            $html .= "\n <div class=\"carousel-inner\" role=\"listbox\">";

            for ($k = 0; $k < $array[$i]["count(*)"]; $k++) {
                if ($k == 0) {
                    $html .= "\n <div class=\"item active\">";
                } else {
                    $html .= "\n <div class=\"item\">";
                }
                if ($arrayMedia[$k]["typeMedia"] == "mp4" || $arrayMedia[$k]["typeMedia"] == "m4v") {
                    $html .= "\n <video width=\"100%\" height=\"100%\" autoplay loop controls>";
                    $html .= "\n <source src=\"img/" . $arrayMedia[$k]["nomMedia"] . "\" type=\"video/mp4\">";
                    $html .= "\n </video>";
                    $html .= "\n </div>";
                }
                if ($arrayMedia[$k]["typeMedia"] == "png" || $arrayMedia[$k]["typeMedia"] == "jpg" || $arrayMedia[$k]["typeMedia"] == "jpeg" || $arrayMedia[$k]["typeMedia"] == "gif" || $arrayMedia[$k]["typeMedia"] == "jpg") {
                    $html .= "\n <img src=\"img/" . $arrayMedia[$k]["nomMedia"] . "\" alt=\"" . $arrayMedia[$k]["nomMedia"] . "\">";
                    $html .= "\n </div>";
                }
            }
            $html .= "\n </div>";

            $html .= "\n <a class=\"left carousel-control\" href=\"#my-pics$i\" role=\"button\" data-slide=\"prev\">";
            $html .= "\n <span class=\"icon-prev\" aria-hidden=\"true\"></span>";
            $html .= "\n <span class=\"sr-only\">Previous</span>";
            $html .= "\n </a>";

            $html .= "\n <a class=\"right carousel-control\" href=\"#my-pics$i\" role=\"button\" data-slide=\"next\">";
            $html .= "\n <span class=\"icon-next\" aria-hidden=\"true\"></span>";
            $html .= "\n <span class=\"sr-only\">Next</span>";
            $html .= "\n </a>";

            $html .= "\n </div>";

            $html .= "\n <div class=\"panel-body\">";
            $html .= "\n <hr>";
            $html .= "\n " . $arrayMedia[0]["commentaire"];
            $html .= "\n <a><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span></a>";
            $html .= "\n <a><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></a>";
            $html .= "\n </div>";

            $html .= "\n </div>";
        }
    }
    return $html;
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
function createMediaAndPost($typeMedia, $nomMedia, $creationDate, $commentaire, $alreadyLoop)
{
    static $ps = null;
    $answer = false;
    try {
        m152DB()->beginTransaction();

        if ($alreadyLoop == 0) {
            $sql = "INSERT INTO `m152`.`post` (`commentaire`, `creationDate`) ";
            $sql .= "VALUES (:COMMENTAIRE, :CREATIONDATE)";
            $ps = m152DB()->prepare($sql);
            $ps->bindParam(':COMMENTAIRE', $commentaire, PDO::PARAM_STR);
            $ps->bindParam(':CREATIONDATE', $creationDate, PDO::PARAM_STR);
            $answer = $ps->execute();
            $ps->close;
        }

        $sql = "INSERT INTO `m152`.`media` (`typeMedia`, `nomMedia`, `creationDate`, `idPost`) ";
        $sql .= "VALUES (:TYPEMEDIA, :NOMMEDIA, :CREATIONDATE, :IDPOST)";
        $ps = m152DB()->prepare($sql);
        $ps->bindParam(':TYPEMEDIA', $typeMedia, PDO::PARAM_STR);
        $ps->bindParam(':NOMMEDIA', $nomMedia, PDO::PARAM_STR);
        $ps->bindParam(':CREATIONDATE', $creationDate, PDO::PARAM_STR);
        $ps->bindParam(':IDPOST', getLastId(), PDO::PARAM_INT);
        $answer = $ps->execute();
        $ps->close;

        m152DB()->commit();
    } catch (PDOException $e) {
        echo $e->getMessage();
        m152DB()->rollBack();
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
