<?php
//Connexion à la base données !!!
  include('connexbdd.php');

//On verifie si l'identifiant de l'utilisateur dont la session est ouverte existe !!!

    if (isset($_SESSION['id'])) {

        //Si oui , on récupère les l'id dans une variable !!!

      $id=intval($_SESSION['id']);

      //Si le bouton envoyer du formulaire existe !!!
        if (isset($_POST['envoyer']) AND $_POST['envoyer']="envoyer") {

          //On récupère les données du contact saisies dans des variables sécurisés!!!
         $nom_contact=htmlspecialchars($_POST['nom_contact']);
         $telephone_contact=htmlspecialchars($_POST['telephone_contact']);
         $email_contact=htmlspecialchars($_POST['email_contact']);

         //Si le formulaire n'est pas vide !!!
         if (!empty($_POST['nom_contact']) OR !empty($_POST['telephone_contact']) OR !empty($_POST['email_contact'])) {

                // Si l'email saisi respecte les norme !!!
                if (filter_var($email_contact, FILTER_VALIDATE_EMAIL)){

                  // On insère les données du contact dans la base dans la table contact!!!
                  $stmt= $bdd->prepare("INSERT INTO contact (nom_prenom,numero,email_cont,id_mbr) VALUES(?,?,?,?)");
                  $stmt->execute(array($nom_contact,$telephone_contact,$email_contact,$id));

                  $mgs='<i class="glyphicon glyphicon-ok">'.''.'</i>'." contact ajouté !";


                        }else{

                            $mgs='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Email incorrecte !";

                        }

         }elseif (empty($_POST['nom_contact']) AND empty($_POST['telephone_contact']) AND empty($_POST['email_contact'])) {

              $mgs='<i class="glyphicon glyphicon-remove-circle">'.''.'</i>'." Veuillez remplir le formulaire SVP !";
         }


        }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Espace membre</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/full-slider.css">
   <link rel="stylesheet" type="text/css" href="styles/style_contact.css">
   <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

<body>
<header>
<!-- Inclure le Menu de navigation -->
<?php include('navbar.php') ?>
</header>
<div class="container">
<div class="row">
<div class="profile-content">

<div class="row">

<!-- Si l'identifiant de l'utilisateur existe et est pareil à celui passé dans l'URL on à accès au formulaire d'insertion de contact sinon on peut juste voir les contact ajoutés par le membre selectionné -->

<?php  if (isset($_GET['id']) AND $_GET['id'] == $_SESSION['id']) { ?>

<!-- Formulaire de contact -->

<form class="form-horizontal" method="post" action="">
      <h4>Entrer un contact</h4>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="nom_contact">Nom :</label>
                <div class="col-sm-8">
                 <input type="text"  name="nom_contact" class="form-control" id="nom_contact" placeholder="Nom et prénoms">
                </div>
                </div>
              <div class="form-group">
              <label class="control-label col-sm-4" for="telephone_contact">Téléphone :</label>
                <div class="col-sm-8">
             <input type="tel"  name="telephone_contact" class="form-control" id="telephone_contact" placeholder="Exple: 08 73 69 50">
            </div>
             </div>
             <div class="form-group">
                  <label class="control-label col-sm-4" for="email_contact">Email :</label>
                <div class="col-sm-8">
                 <input type="tel"  name="email_contact" class="form-control" id="email_contact" placeholder="Email contact">
                </div>
                </div>

              <div class="form-group">
               <div class="col-sm-offset-4 col-sm-8">
             <button type="submit" name="envoyer" class="btn btn-primary" value="envoyer">Ajouter un contact</button>
              </div>
               </div>

               <!-- Afiichage des différents messages d'alerte s'il en existe -->

               <center>
                <?php if (isset($mgs)) {

                  echo '<font color="red">'.$mgs.'</font>';
                  }?>
              </center>
</form>
<?php } ?>

</div>


 <!-- Liste des contacts enrégistrés -->
<div class="row">

<?php

//On vérifie l'id passer par l'RUL et on le récupère pour envoyer une requête à la base de données !!!

if (isset($_GET['id'])) {


              $id_new= intval($_GET['id']);

              // On selectionne les contacts du membre ayant l'id passé par l'URL dans la base de données !!!

              $stmt= $bdd->prepare('SELECT * FROM contact WHERE id_mbr= :id ');
              $stmt->bindparam('id',$id_new);
              $stmt->execute();


?>


 <!-- Tableau des contacts enrégistrés -->
<br><br>

<div class="table-responsive">
<table id="myTable" class="table table-striped">
<thead>
    <tr>
      <th>Nom et prénoms</th>
      <th>Téléphone</th>
      <th>Email</th>

    </tr>
    </thead>
<?php

// Tant que $result reçoit un fetch de la requête on affiche ce resultat dans le tableau
// !!!
while($result= $stmt->fetch())
{

?>
<tboby>
  <tr>
      <td><span class="glyphicon glyphicon-user"></span><?php echo htmlspecialchars($result['nom_prenom'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><span class="glyphicon glyphicon-phone"></span><?php echo htmlspecialchars($result['numero'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><span class="glyphicon glyphicon-envelope"></span><a href="<?php echo "mailto:".$result['email_cont']; ?>"> <?php echo htmlspecialchars($result['email_cont'], ENT_QUOTES, 'UTF-8'); ?> </a> </td>

    </tr>
    </tboby>
<?php
}
?>
</table>

</div>
<?php
}

?>

</div>


</div>
</div>
</div>
<div class="bas">
  <center><span class="glyphicon glyphicon-copyright-mark"></span><a href="#">Copyright
   </a> </center>
</div>
</body>
<script>
$(document).ready(function(){
   var table = $('#myTable').DataTable({
       lengthMenu: [ [5 ,10 ,15 ,20 ,25 ,30, -1], [5 ,10 ,15 ,20 ,25 ,30 , "All"] ],
       pageLength: 5,

 "language": {
      "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"

            }

    });

});


</script>
</html>
<?php

}else{

    header('Location:index.php');
}


?>







