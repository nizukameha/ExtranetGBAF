<?php 
// Connexion a la base de données depuis le fichier 'connexionDB.php'
include_once('DB/connexionDB.php');
// Si l'utilisateur n'est pas connecté il est redirigé sur la page de connexion
if(!isset($_SESSION['id_user'])) {
    header('Location: connexion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/acteurs.css">
        <title>GBAF</title>
        <link rel="shortcut icon" type="image/png" href="IMG/faviconGBAF.ico"/>
    </head>
    <body>
        <header>
            <?php include('header.php'); ?>
        </header>
        <!-- Bloc qui contient tous les acteurs -->
        <section class="conteneurActeurs">
            <h1>Acteurs et partenaires</h1>
            <?php
            $id_acteur = $_GET['id'];
            // On récupere toutes les données de la table acteur en fonction de l'id
            $requette = $DB->prepare("SELECT * FROM acteur WHERE id_acteur = $id_acteur" );
            $requette ->execute();
            $acteurs = $requette->fetchAll();
            // On affiche les données dans une boucle
            foreach ($acteurs as $acteur) {
            ?>
            <article class="acteur">
                <img src="<?php echo $acteur['logo']; ?>" class="logoActeurs" alt="logo">
                <div class="conteneurDescriptionBoutton">
                    <p class="descriptionActeur">
                        <span class="gras">
                            <?= $acteur['acteur']; ?>
                        </span>
                        <?= $acteur['description']; ?>
                    </p>
                </div>
            </article>
            <br>
            <?php
            }
            ?>
        </section>
        <section class="conteneurActeurs">
            <h2>Commentaires</h2>
            <article class="acteur">
                <div class="conteneurDescriptionBoutton">
                    <span class="gras">Prénom<br>Date<br>Texte
                    </span>
                </div>
            </article>
        </section>
        <hr>
        <footer>
            <?php include('footer.php'); ?>
        </footer>
    </body>
</html>