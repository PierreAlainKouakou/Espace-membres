<?php
// Connexion à la base de données !!!
include('connexbdd.php');
// On vérifie si le bouton envoyer du formulaire existe !!!
if(isset($_POST['envoyer']))
{
// On récupère les données entrées dans des variable avec des fonctions de sécurité !!!
$prenom=htmlspecialchars($_POST['prenom']);
$nom=htmlspecialchars($_POST['nom']);
$email=htmlspecialchars($_POST['email']);
$age=htmlspecialchars($_POST['age']);
$motpasse=sha1($_POST['motpasse']);
$motpasse2=sha1($_POST['motpasse2']);
$sexe=htmlspecialchars($_POST['sexe']);
$habit=htmlspecialchars($_POST['habit']);
$telephone=htmlspecialchars($_POST['telephone']);
$profession=htmlspecialchars($_POST['profession']);
// On vérifie si le formulaire d'inscription n'est pas vide !!!
if(!empty($_POST['prenom']) AND !empty($_POST['nom']) AND !empty($_POST['email']) AND !empty($_POST['motpasse']) AND !empty($_POST['motpasse2']) AND !empty($_POST['habit']) AND !empty($_POST['telephone']) AND !empty($_POST['profession']))
{
// On vérifie la longueur des noms et prénoms saisis par l'utilisateur ne dépasse pas 255 caractères !!!
$prenomlength=strlen($prenom);
$nomlength=strlen($nom);
if ($prenomlength <= 255 OR $nomlength <= 255) {
// On vérifie ensuite que m'email saisi est correcte !!!
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
// On envoie une requête à la base de données afin de verifier si l'email saisi n'existe pas déjà !!!
$stmt= $bdd->prepare('SELECT 1 FROM membres WHERE email= :email ');
$stmt->bindparam('email',$email);
$stmt->execute();
// Si le resultat du fetch est faux alors on continu !!!
if (FALSE == $stmt->fetch() ) {
// On vérifie ensuite que les deux mots de passe entrés sont conformes !!!
if ($motpasse == $motpasse2) {
// On insert les données du formulaire dans la base ensuite on rédirige le membre pour une nouvelle connexion!!!
$stmt= $bdd->prepare("INSERT INTO membres(prenoms,nom,email,motpass,sexe,age,habitat,tel,profession,lat_utilisateur,long_utilisateur,avatar) VALUES(?,?,?,?,?,?,?,?,?,'7.539988','-5.547080','default.png')");
$stmt->execute(array($prenom,$nom,$email,$motpasse,$sexe,$age,$habit,$telephone,$profession));
header('Location:index.php');
}else{
$erreur='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Vos mots de passe ne correspondent pas, veuillez les ressaisir à nouveau !";
}
}else{
$erreur='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Cette adresse email est déjà utilisée !";
}
}else{
$erreur='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Vous n'avez pas entré une bonne adresse email !";
}
}else{
$erreur='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Désolé vos identifiant ne doivent pas exceder 255 caractères !";
}
} else{
$erreur='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Veuillez remplir tous les champs SVP !";
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
    <nav class="navbar navbar-inverse navbar-fixed-top">
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
        <h1>Inscription</h1><br>
        <!-- Formulaire d'inscription au site  -->
        <form class="form-horizontal" method="POST" action="">
          <div class="form-group">
            <label for="Prenoms">Prenoms:</label>
            <input class="form-control" type="text" name="prenom" id="Prenoms" value="<?php if (isset($prenom)) {
            echo $prenom;
            }?>">
          </div>
          <div class="form-group">
            <label for="Nom">Nom:</label>
            <input class="form-control" type="text" name="nom" id="Nom"
            value="<?php if (isset($nom)) {echo $nom; }?>">
          </div>
          <div class="form-group">
            <label for="Email">Email:</label>
            <input class="form-control" type="email" name="email" id="Email" value="<?php if (isset($email)) {
            echo $email;
            }?>">
          </div>
          <div class="form-group">
            <label for="Email">Lieu d'habitation:</label>
            <input class="form-control" type="text" name="habit" id="Habit" value="<?php
            if (isset($habit)) {
            echo $habit;
            }?>">
          </div>
          <div class="form-group">
            <label for="Email">Téléphone:</label>
            <input class="form-control" type="tel" name="telephone" id="Telephone" value="<?php if (isset($telephone)) {
            echo $telephone;
            }?>">
          </div>
          <div class="form-group">
            <label for="Email">Profession:</label>
            <input class="form-control" type="text" name="profession" id="Profession" value="<?php if (isset($profession)) {echo $profession; }?>">
          </div>
          <div class="form-group">
            <label for="Mot de passe">Mot de passe:</label>
            <input class="form-control" type="password" name="motpasse" id="Mot de passe">
          </div>
          <div class="form-group">
            <label for="Confirmation mot de passe">Confirmez mot de passe:</label>
            <input class="form-control" type="password" name="motpasse2" id="Confirmation mot de passe">
          </div>
          <div class="form-group">
            <label for="Sexe">Je suis:</label>
            <select name="sexe" id="Sexe">
              <option value="masculin">Masculin</option>
              <option value="feminin">Féminin</option>
            </select>
          </div>
          <div class="form-group">
            <label for="Votre age">Age:</label>
            <input class="form-control" type="age" name="age" id="Votre age" value="<?php if (isset($age)) {echo $age; }?>">
          </div>
          <button  type="submit" name="envoyer" class="btn btn-default">Envoyer</button>
          <p>Vous avez déjà un compte ici ? <a href="connexion.php">Connectez vous !</a> </p>
        </form>
        <!-- Affichage des différents messages d'alerte s'il y en a -->
        <center><?php
        if (isset($erreur)) {
        echo '<font color="red">'.$erreur.'</font>';
        } ?>
        </center>
      </div>
    </body>
  </html>
