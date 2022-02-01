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
function readPokemon($idPokemon)
{
    static $ps = null;
    $sql = 'SELECT idPokemon, NomPokemon';
    $sql .= ' FROM pokedex.Pokemons';
    $sql .= ' WHERE idPokemon = :IDPOKEMON';

    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':IDPOKEMON', $idPokemon, PDO::PARAM_INT);

        if ($ps->execute())
            $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

function readTypes($from = 0, $offset = 50)
{
    static $ps = null;
    $sql = 'SELECT idType, NomType';
    $sql .= ' FROM pokedex.PokemonTypes';
    $sql .= ' ORDER BY NomType ASC LIMIT :FROM,:OFFSET;';

    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':FROM', $from, PDO::PARAM_INT);
        $ps->bindParam(':OFFSET', $offset, PDO::PARAM_INT);

        if ($ps->execute())
            $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Lit les 50 premières pokémons par défaut.
 * @staticvar pdo prepare statement $ps
 * @param int $from A partir du pokémon $from (0 par défaut)
 * @param int Jusqu'au pokémon $offset (50 par défaut) 
 * @return false|array 
 */
function readPokemonsAndTypes($from = 0, $offset = 50)
{
    static $ps = null;
    $sql = 'SELECT p.idPokemon, p.NomPokemon, t.NomType ';
    $sql .= 'FROM pokedex.Pokemons as p, pokedex.PokemonTypes as t ';
    $sql .= 'WHERE p.idType = t.idType ';
    $sql .= 'ORDER BY p.idPokemon ASC LIMIT :FROM,:OFFSET;';

    $answer = false;
    try {
        if ($ps == null) {
            $ps = m152DB()->prepare($sql);
        }
        $ps->bindParam(':FROM', $from, PDO::PARAM_INT);
        $ps->bindParam(':OFFSET', $offset, PDO::PARAM_INT);

        if ($ps->execute())
            $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Ajoute une nouvelle note avec ses paramètres
 * @param mixed $nomPokemon Nom du pokémon
 * @param mixed $type  Type du pokémon
 * @return bool true si réussi
 */
function createPokemon($nomPokemon, $idType)
{
    static $ps = null;
    $sql = "INSERT INTO `pokedex`.`Pokemons` (`NomPokemon`, `idType`) ";
    $sql .= "VALUES (:NOMPOKEMON, :IDTYPE)";
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':NOMPOKEMON', $nomPokemon, PDO::PARAM_STR);
        $ps->bindParam(':IDTYPE', $idType, PDO::PARAM_INT);

        $answer = $ps->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Met à jour une note existante 
 * @param mixed $idPokemon
 * @param mixed $nom
 * @param mixed $type 
 * @return bool 
 */
function updatePokemon($idPokemon, $nomPokemon, $idType)
{
    static $ps = null;

    $sql = "UPDATE `pokedex`.`Pokemons` SET ";
    $sql .= "`NomPokemon` = :NOMPOKEMON, ";
    $sql .= "`idType` = :IDTYPE, ";
    $sql .= "WHERE (`idPokemon` = :IDPOKEMON)";
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':IDPOKEMON', $idPokemon, PDO::PARAM_INT);
        $ps->bindParam(':NOMPOKEMON', $nomPokemon, PDO::PARAM_STR);
        $ps->bindParam(':IDTYPE', $idType, PDO::PARAM_INT);
        $ps->execute();
        $answer = ($ps->rowCount() > 0);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/**
 * Supprime la note ave l'id $idPokemon.
 * @param mixed $idPokemon 
 * @return bool 
 */
function deletePokemon($idPokemon)
{
    static $ps = null;
    $sql = "DELETE FROM `pokedex`.`Pokemons` WHERE (`idPokemon` = :IDPOKEMON);";
    if ($ps == null) {
        $ps = m152DB()->prepare($sql);
    }
    $answer = false;
    try {
        $ps->bindParam(':IDPOKEMON', $idPokemon, PDO::PARAM_INT);
        $ps->execute();
        $answer = ($ps->rowCount() > 0);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

