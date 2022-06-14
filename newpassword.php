<?php 

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
            <?php include('headerconnexion.php'); ?>
        </header>
        <section class="conteneurImage">
            <img class='logoOpacity' src="IMG/logoGBAF.jpg" alt="Logo GBAF">   
            <form action="newpassword.php">
                <h1>Mot de passe oublié</h1><br>          
                <label for="mdp">Nouveau mot de passe :</label><br>
                    <input type="password" name="mdp"><br>
                <div class="button">
                <button type="submit" name="valider">Valider</button> 
                </div>
            </form>
        </section>
        <hr>
        <footer>
            <?php include('footer.php'); ?>
        </footer>
    </body>
</html>