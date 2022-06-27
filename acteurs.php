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
            <h2>Que pensez-vous de cet acteur ?</h2>
            <?php
            if(isset($_POST['like'])) {
                extract($_POST);
                $id_user = $_SESSION['id_user'];
                $vote = 1;
                $requette = $DB->prepare("INSERT INTO vote(id_user, id_acteur, vote) 
                VALUES (?, ?, ?)");
                $requette ->execute(array($id_user, $id_acteur, $vote));
            } elseif(isset($_POST['dislike'])) {
                extract($_POST);
                $id_user = $_SESSION['id_user'];
                $vote = 0;
                $requette = $DB->prepare("INSERT INTO vote(id_user, id_acteur, vote) 
                VALUES (?, ?, ?)");
                $requette ->execute(array($id_user, $id_acteur, $vote));
            }
            ?>
            <div class="conteneurLike">
                <form action="acteurs.php?id=<?= $_GET['id'] ?>" method="post">
                    <button type="submit" class="likeButton" name="like"><img src="IMG/like.png"></button>
                    <button type="submit" class="dislikeButton" name="dislike"><img src="IMG/dislike.png"></button>
                </form>
            </div><br>
            <?php
            // Lorsque l'utilisateur valide le formulaire
            if(isset($_POST['valider'])) {
                // Vérification que le commentaire n'est pas vide
                if(empty($_POST['post'])) {
                    $errVide = "Votre commentaire est vide !";
                // Vérification que l'utilisateur n'a pas déjà commenté
                } else {
                    // Selectionne l'id_user correspondant a l'id_user de la session pour cet acteur
                    $requette = $DB->prepare("SELECT id_user, id_acteur FROM post WHERE id_user = ? AND id_acteur = ?");
                    $requette->execute(array($_SESSION['id_user'], $id_acteur));
                    // fetch et non pas fetchAll car on veut une valeur
                    $requette = $requette->fetch();
                    // Si la requette est vrai c'est que l'utilisateur a déjà commenté
                    if($requette) {
                    $errUser = "Vous avez déjà commenté pour cet acteur !";
                    // Si il n'a jamais commenté, on ajoute son commentaire a la bdd
                    } else {
                        // Récupere les données
                        extract($_POST);
                        $id_user = $_SESSION['id_user'];
                        $date_add = date("y.m.d");
                        $post = htmlspecialchars($post);
                        // Ajout a la base de donnée
                        $requette = $DB->prepare("INSERT INTO post(id_user, id_acteur, date_add, post) 
                        VALUES (?, ?, ?, ?)");
                        $requette ->execute(array($id_user, $id_acteur, $date_add, $post));
                    }
                }
            }
            ?>
            <div class="erreur">
                <?php if (isset($errVide)) { echo $errVide; } ?>
                <?php if (isset($errUser)) { echo $errUser; } ?>
            </div><br>
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