<?php 
    // On récupere les données de session
    session_start();
    // On termine la session
    session_destroy();
    // On redirige l'utilisateur sur la page de connexion
    header('Location: connexion.php');
    exit;
?>