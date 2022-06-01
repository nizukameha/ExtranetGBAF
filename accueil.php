<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="accueil.css">
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
            <?php
            try
            {
            // BASE DE DONNÉES
                $mysqlClient = new PDO('mysql:host=localhost:3307;dbname=gbaf;charset=utf8', 'root', 'root');
            }
            catch(Exception $e)
            {
            // En cas d'erreur, on affiche un message et on arrête tout
                die('Erreur : '.$e->getMessage());
            }

            // On récupère tout le contenu de la table acteur
            $sqlQuery = 'SELECT * FROM acteur';
            $acteurStatement = $mysqlClient->prepare($sqlQuery);
            $acteurStatement->execute();
            $acteurs = $acteurStatement->fetchAll();

            // BOUCLE
            foreach ($acteurs as $acteur) {
            ?>
            <article class="acteur">
                <img src="<?php echo $acteur['logo']; ?>" class="logoActeurs" alt="logo">
                <div class="conteneurDescriptionBoutton">
                    <p class="descriptionActeur">
                        <span class="gras">
                            <?php echo $acteur['acteur']; ?>
                        </span>
                        <?php echo $acteur['description']; ?>
                    </p>
                    <p class="boutonSuite">En savoir plus</p>
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