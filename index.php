<?php 
// Connexion a la base de données depuis le fichier 'connexionDB.php'
include_once('DB/connexionDB.php');
// Si l'utilisateur n'est pas connecté il est redirigé sur la page de connexion
if(!isset($_SESSION['id_user'])) {
    header('Location: connexion.php');
    exit;
}
// On récupere toutes les données de la table acteur
$requete = $DB->prepare("SELECT * FROM acteur");
$requete ->execute(array());
$acteurs = $requete->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/accueil.css">
        <title>GBAF</title>
        <link rel="shortcut icon" type="image/png" href="IMG/faviconGBAF.ico"/>
    </head>
    <body>
        <header>
            <?php include('header.php'); ?>
        </header>
        <section>
            <article class="conteneurPresentation">
                <h1>Groupement Banque-Assurance Français (GBAF)</h1>
                <p>Le GBAF est le représentant de la profession bancaire et des assureurs sur tous les axes de la réglementation financière française. Sa mission est de promouvoir l'activité bancaire à l’échelle nationale. C’est aussi un interlocuteur privilégié des pouvoirs publics. Le GBAF met à disposition des salariés des banques et des assurances en France une base de données. Celle-ci liste un grand nombre d'informations sur les partenaires et acteurs du groupe ainsi que sur les produits et services bancaires et financiers. Chaque salarié peut poster un commentaire et donner son avis.</p>
                <img class="illustration"src="IMG/illustration.jpg"alt="illustration">
            </article>
        </section>
        <hr>
        <!-- Bloc qui contient tous les acteurs -->
        <section class="conteneurActeurs">
            <h2>Acteurs et partenaires</h2>
            <p>Les produits et services bancaires sont nombreux et très variés. Afin de renseigner au mieux les clients, les salariés des banques et assurances peuvent retrouver dans cette section un grand nombre d’informations sur les partenaires et acteurs du groupe ainsi que sur les produits et services bancaires et financiers. Chaque salarié pourra ainsi poster un commentaire et donner son avis.</p>
            <?php
            // On affiche les données dans une boucle
            foreach ($acteurs as $acteur) {
            ?>
            <article class="acteur">
                <img src="<?php echo $acteur['logo']; ?>" class="logoActeurs" alt="logo">
                <div class="conteneurDescriptionBoutton">
                    <p class="descriptionActeur">
                        <span class="gras">
                            <?= $acteur['acteur']; ?>
                        </span><br>
                        <?= substr($acteur['description'], 0, 60); ?>...
                    
                    <!-- Le bouton permet de récupérer l'id correspondant a l'acteur et de l'envoyer dans l'url -->
                    <div class="boutonSuite">
                        <a href="acteurs.php?id=<?= $acteur['id_acteur'] ?>">lire la suite</a>
                    </div>
                </div>
            </article>
            <br>
            <?php
            }
            ?>
        </section>
        <hr>
        <footer>
            <?php include('footer.php'); ?>
        </footer>
    </body>
</html>