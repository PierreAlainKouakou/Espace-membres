<?php

// Connexion à la base de données !!!

include('connexbdd.php');

// On vérifie si l'id de la session ouverte existe sur la page !!!

    if (isset($_SESSION['id'])) {

// Si oui on selectionne tous les membres inscrits dans la base de données !!!

        $stmt= $bdd->prepare('SELECT * FROM membres ');

        $stmt->execute(array());

?>

<!DOCTYPE html>
<html>
<head>
    <title>Espace membre</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/full-slider.css">
    <link rel="stylesheet" type="text/css" href="styles/stylesup.css">
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</head>

<body>
<header>

<!-- Menu de navigation -->

<?php include('navbar.php') ?>

</header>
<div class="container">
<div class="row">

<div class="profile-content">

<!-- Liste des membres inscrits -->

<h3> Voici la liste des utilisateurs</h3><br>


<div class="table-responsive">
<table id="myTable" class="table table-striped">
<thead>
    <tr>
      <th>Id</th>
      <th>Email</th>
      <th>Nom</th>
      <th>Prénoms</th>
      <th>Sexe</th>
      <th>Age</th>
      <th>Habitation</th>
      <th>Téléphone</th>
      <th>Profession</th>
      <th>Inscrit le</th>

    </tr>
    </thead>

<?php
// Tant que $result reçoit les fetch de la requête , on insère
// le membre dans le tableau !!!

while($result= $stmt->fetch())
{
?>
<tboby>
  <tr>
      <td><?php echo $result['id']; ?></td>
      <td><a href="profil.php?id=<?php echo $result['id']; ?>"><?php echo htmlspecialchars($result['email'], ENT_QUOTES, 'UTF-8'); ?></a></td>
      <td><?php echo htmlspecialchars($result['nom'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo htmlspecialchars($result['prenoms'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo htmlspecialchars($result['sexe'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo htmlspecialchars($result['age'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo htmlspecialchars($result['habitat'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php echo $result['tel']; ?></td>
      <td><?php echo htmlspecialchars($result['profession'], ENT_QUOTES, 'UTF-8'); ?></td>
      <td><?php
                  // On récupère la date d'inscription du membre ajouté dans le tableau
                  // ainsi que l'heure ensuite on affecte les données de $date_creat  à des variables à travers list()
                  // on change le format de la date avant de l'afficher !!!

                  $date_creat=$result['date_creat'];

                  list($date, $time)=explode(" ", $date_creat);

                  list($year, $month, $day)=explode("-", $date);
                  list($hour, $min, $sec)=explode(":", $time);


            echo $date_creat="$day/$month/$year à $time";?></td>
    </tr>
    </tboby>
<?php
}
?>
</table>
</div>
</div>
</div>
</div>
<div>
  <center><span class="glyphicon glyphicon-copyright-mark"></span><a href="#">Copyright
   </a> </center>
   </div>

</body>

<!-- script javascript appel des fonctionnalités DataTables -->

<script>
$(document).ready(function(){
   var table = $('#myTable').DataTable({
       lengthMenu: [ [5 ,10 ,15 ,20 ,25 ,30, -1], [5 ,10 ,15 ,20 ,25 ,30 , "All"] ],
       pageLength: 10,

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

