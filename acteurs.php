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
            // Afficher les acteurs
            $id_acteur = $_GET['id'];
            // On récupere toutes les données de la table acteur en fonction de l'id
            $requette = $DB->prepare("SELECT * 
            FROM acteur 
            WHERE id_acteur = $id_acteur" );
            $requette ->execute();
            $acteurs = $requette->fetchAll();
            // On affiche les acteurs dans une boucle
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
            <h2>Ajouter un commentaire</h2>
            <?php
            // Ajouter des commentaires depuis le site
            if(isset($_POST['valider'])) {
                extract($_POST);
                $id_user = $_SESSION['id_user'];
                $date_add = date("y.m.d");
                $post = htmlspecialchars($post);
                $requette = $DB->prepare("INSERT INTO post(id_user, id_acteur, date_add, post) 
                VALUES (?, ?, ?, ?)");
                $requette ->execute(array($id_user, $id_acteur, $date_add, $post));
            }    
            ?>
            <article class="acteur">
                <div class="conteneurDescriptionBoutton">
                    <form action="acteurs.php?id=<?= $_GET['id'] ?>" method="post">
                        <label for="post">Commentaire :</label>         
                            <textarea name="post"></textarea><br>
                        <div class="button">
                            <button type="submit" name="valider">Valider</button>
                        </div>
                    </form>
                </div>
            </article>
        </section>
        </section>
        <section class="conteneurActeurs">
            <h2>Commentaires</h2>
            <?php
            // Afficher les commentaires lié aux utilisateurs et aux acteurs | INNER JOIN permet de lié plusieurs tables |
            $requette = $DB->prepare("SELECT post.*, prenom, id_acteur FROM post  
            INNER JOIN account  
            ON account.id_user = post.id_user
            WHERE id_acteur = ?");
            // On veux récupérer les commentaires des utilisateurs qui sont lié a cet acteur. Pour cela on précise l'id de l'acteur
            $requette->execute([$id_acteur]);
            $req_post = $requette->fetchAll();
                foreach($req_post as $commentaire) {
            ?>
            <article class="acteur">
                <div class="conteneurDescriptionBoutton">
                    <span class="gras">
                        <?= $commentaire['prenom']; ?><br>
                        <?= $commentaire['date_add']; ?><br>
                    </span>
                    <?= $commentaire['post']; ?>
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