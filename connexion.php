<?php
//Connexion à la base de données !!
include('connexbdd.php');

        //On test si le bouton envoyer existe !!!
        if (isset($_POST['envoyer'])) {

          // On crée des variables , On sécurise les données envoyées!
                  $emailconnect=htmlspecialchars($_POST['emailconnect']);
                  $motpasseconnect=sha1($_POST['motpasseconnect']);

            //Si le formulaire n'est pas vide !!!
            if (!empty($emailconnect) AND !empty($motpasseconnect)){

                // On test si le mail entré respecte les normes d'une adresse email !!
                if (filter_var($emailconnect, FILTER_VALIDATE_EMAIL)) {

                  // On vérifie si le couple de données entrées existe dans notre base de données !!!

                   $stmt= $bdd->prepare("SELECT * FROM membres WHERE email = ? AND motpass = ? ");
                   $stmt->execute(array($emailconnect,$motpasseconnect));

                  // On récupère le nombre de ligne affecté par la requête !!!!

                   $result = $stmt->rowCount();

                  // S'il ya un resultat alors on fetch les données du resultat dans la variable $userinfo !!!

                  if ($result == 1) {


                    $userinfo = $stmt->fetch();
                    $_SESSION['id']=$userinfo['id'];
                    $_SESSION['prenoms']=$userinfo['prenoms'];
                    $_SESSION['nom']=$userinfo['nom'];
                    $_SESSION['email']=$userinfo['email'];
                    $_SESSION['sexe']=$userinfo['sexe'];
                    $_SESSION['age']=$userinfo['age'];
                    $_SESSION['habitat']=$userinfo['habitat'];
                    $_SESSION['tel']=$userinfo['tel'];
                    $_SESSION['profession']=$userinfo['profession'];
                    $_SESSION['avatar']=$userinfo['avatar'];
                    header('Location:profil.php?id='.$_SESSION['id']);


                  }else{

                    $erreur='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Email ou mot de passe incorrect !";
                  }

                    }else{

                      $erreur='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Vous n'avez pas entré une bonne adresse email !";
                    }

            }else{

              $erreur='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Veuillez remplir les deux champs SVP !";
            }

        }

?>


<!DOCTYPE html>
<html>
<head>
    <title>Espace membre</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>
<!-- Menu de navigation -->
<nav class="navbar navbar-inverse ">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">Bienvenue !</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="inscription.php"><span class="glyphicon glyphicon-user"></span> Inscription</a></li>
      <li><a href="connexion.php"><span class="glyphicon glyphicon-log-in"></span> Connexion</a></li>
    </ul>
  </div>
</nav>


<div class="container-fluid">

    <div align="center">
        <h1>Connexion</h1><br>

        <!-- Formulaire de connexion -->

         <form class="form-horizontal" method="post" action="">
                <div class="form-group">
                    <label for="Email">Email:</label>
                    <input class="form-control" type="email" name="emailconnect" id="Email" value="<?php if (isset($emailconnect)) {
                      echo $emailconnect;
                    }?>">
                </div>
                <div class="form-group">
                    <label for="Mot de passe">Mot de passe:</label>
                    <input class="form-control" type="password" name="motpasseconnect" id="Mot de passe">
                </div>
                <div class="form-group">
                    <button  type="submit" name="envoyer" class="btn btn-default">Envoyer</button>
                </div>

                    <p>Vous n'avez pas de compte ici ? <a href="inscription.php">Inscrivez vous !</a> </p>

          </form>

    </div>

    <center>
    <?php
          if (isset($erreur)) {
            echo '<font color="red">'.$erreur.'</font>';
          }

    ?>
    </center>

</div>

</body>
</html>
