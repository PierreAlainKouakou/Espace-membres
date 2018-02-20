<?php
// Connexion à la base de données !!!
    include('connexbdd.php');

// Si l'id de la session ouverte existe alors on à accès au contenu de la page d'accueil du site !!!

    if (isset($_SESSION['id'])) {

?>

<!DOCTYPE html>
<html>
<head>
    <title>Espace membre</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/full-slider.css">
   <link rel="stylesheet" type="text/css" href="style1.css">
</head>

<body>
<header>
<?php include('navbar.php') ?>

</header>
<div class="container">
 <div class="row" id=content>
    <div class="col-sm-12">
            <div class="profile-content">
            <h2>Informations personnelles</h2>

            <p>Merci de me laisser un message pour plus d'informations !</p>

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






