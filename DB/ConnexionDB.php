<?php
// On démarre une session pour l'utilisateur
session_start();

// On se connecte a la bdd (adresse, nom bdd, charset utilisé, id, password)
try
{
    $DB = new PDO(
        'mysql:host=localhost:3307;dbname=gbaf;charset=utf8',
        'root',
        'root'
    );
}
// En cas d'erreur on affiche un message
catch (exception $e)
{
    die('Erreur : '. $e->getMessage());
}

?>