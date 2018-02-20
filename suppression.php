<?php

    // Connexion à la base de données !!!

  include('connexbdd.php');

    // On vérifie si l'id du membre connecté existe !!!

    if (isset($_SESSION['id'])) {

            // On vérifie si le bouton supprimer existe !!!

            if (isset($_POST['supprimer']) AND isset($_POST['supprimer']) == "supprimer") {

                // On récupère l'id !!!

                $id= intval($_SESSION['id']);

                // requête préparée pour supprimer le membre de la base de données !!!

                $stmt= $bdd->prepare('DELETE FROM membres WHERE id = ? ');
                $stmt->execute(array($id));

                // On rédirige ensuite l'utilisateur vers la page d'accueil !!!

                header('Location:index.php');

                // Sinon si le bouton annuler existe on rédirige le membre vers son profil !!!

            }elseif (isset($_POST['annuler']) AND isset($_POST['annuler']) == "annuler") {


                header('Location:profil.php?id='.$_SESSION['id']);

            }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Espace membre</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/full-slider.css">
   <link rel="stylesheet" type="text/css" href="styles/stylesup.css">
</head>

<body>
<header>

<!-- Menu de navigation -->

<?php include('navbar.php') ?>

</header>
<div class="container">

 <div class="row">
    <div class="col-sm-12">
            <h2>Voulez-vous supprimer votre compte ?</h2>
            <form method="post" action="">
             <button type="submit" name="supprimer" class="btn btn-primary" value="supprimer">Supprimer</button>
            <button type="submit" name="annuler" class="btn btn-primary" value="annuler">Annuler</button>
            </form>

            <div class="row" id="ligne">
                <div class="col-sm-12">

            <p>Vos commentaires :</p>
            <br>

            </div>

            </div>

            <!-- Formulaire pour envoyer des suggestions pour améliorer le site -->

            <form method="post" action="">

                <textarea placeholder="Aidez nous à améliorer le site ..." rows="4" cols="50" name="suggestion" ></textarea><br>
                        <button type="submit" name="envoyer" class="btn btn-primary" value="envoyer">
                        <a href="mailto:suggestion@yahoo.fr" class="bouton">Envoyer</a>
                        </button>
            </form><br>

                        <?php
                                if (isset($erreur)) {
                                    echo '<font color="red">'.$erreur.'</font>';
                                }
                        ?>
    </div>

  </div>
</div>
<div>
  <center><a href="#"><span class="glyphicon glyphicon-copyright-mark"></span>Copyright
   </center></a>
   </div>
</div>

</body>
</html>
<?php

}else{

    header('Location:index.php');
}


?>







