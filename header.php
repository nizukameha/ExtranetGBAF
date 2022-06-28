<div class="conteneurHeader">
    <img class='logo' src="IMG/logoGBAF.jpg" alt="Logo GBAF">
    <p class="utilisateur">
        <?php if(isset($_SESSION['id_user'])) {
            $connected_user = "$_SESSION[nom] $_SESSION[prenom]";
            echo $connected_user;
        } else {
            $disconnected_user = "Vous n'êtes pas connecté";
            echo $disconnected_user;
        }?>
    </p>
    <div class="icon">
        <a href="accueil.php"><img class='deconnexion' src="IMG/home.png" alt="deconnexion"></a>
        <a href="parametre.php"><img class='parametre' src="IMG/parametre.png" alt="parametre"></a>
        <a href="deconnexion.php"><img class='deconnexion' src="IMG/deconnexion.png" alt="deconnexion"></a>
    </div> 
</div>
<hr>