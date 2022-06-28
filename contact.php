<?php 
// Connexion a la base de données depuis le fichier 'connexionDB.php'
include_once('DB/connexionDB.php');
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/inscription.css">
        <title>GBAF</title>
        <link rel="shortcut icon" type="image/png" href="IMG/faviconGBAF.ico"/>
    </head>
    <body>
        <header>
            <?php include('header.php'); ?>
        </header>
        <form action="connexion.php" method="post">
                <h1>Contact</h1><br>
                <div class="erreur">
                    <?php if(isset($err_mail)) { echo $err_mail; } ?>
                </div> 
                <label for="email">E-mail :</label><br>         
                    <input type="text" name="email"><br>
                <label for="post">Commentaire :</label><br>
                    <div class="erreur">
                        <?php if(isset($err_com)) { echo $err_com; } ?>
                    </div> 
                    <textarea name="post"></textarea><br>
                <div class="button">
                    <button type="submit" name="valider">Valider</button>
                </div>
            </form>
    </body>
</html>