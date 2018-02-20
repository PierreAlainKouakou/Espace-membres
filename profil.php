<?php

// Connexion à la base de données !!!

include('connexbdd.php');

  // On vérifie si l'id passé par l'URL existe !!!

  if (isset($_GET['id']) AND $_GET['id'] > 0) {

      $id = intval($_GET['id']);

      // On récupère les informations du membre dans la base de données à l'aide d'une requête préparée !!

      $stmt = $bdd->prepare('SELECT * FROM membres WHERE id= ? ');
      $stmt->execute(array($id));
      $userinfo = $stmt->fetch();

// On vérifie si le bouton envoyé existe pour les articles postés sur le profil !!!

if (isset($_POST['envoyer']) AND $_POST['envoyer'] = "envoyer") {

                // On vérifie l'id passé par l'URL est pareil à celui de la session ouverte !!!



          $nom=$_SESSION['nom'];
          // On récupère les données de l'article posté !!!

          $titre=htmlspecialchars($_POST['titre']);
          $article=htmlspecialchars($_POST['article']);

            // On vérifie si le formulaire est bien rempli !!!

            if (!empty($_POST['article']) AND !empty($_POST['titre'])) {

              // On insère les informations de l'article posté dans la base de données !!!

              $stmt= $bdd->prepare('INSERT INTO message (titre,post,id_membre,nom_mbr) VALUES (?,?,?,?)');
              $stmt->execute(array($titre,$article,$id,$nom));


                    }else{

                          $erreur=" Les champs ci-dessus semble vide !! ";
                       }



      }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Espace membre</title>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="styles/style2.css">
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxHMB3-nltaB3XJIp5IxbsmV29PonBXCA&language=fr">
</script>
</head>

<!-- On demarre la fonction du script avec "onload" -->
<body onload="initialize()">

<!-- Scripot javaScript de geolocalisation à l'aide du lieu d'habitation du membre inscrit dans la base de donnée -->
<script type="text/javascript">

<?php if (isset($_GET['id'])) {

            $id_geocode= intval($_GET['id']);

            $stmt = $bdd->prepare('SELECT * FROM membres WHERE id= ? ');
            $stmt->execute(array($id_geocode));
            $user_geocode = $stmt->fetch();

            } ?>

initialize = function(){
  var latLng = new google.maps.LatLng(<?php echo $user_geocode['lat_utilisateur']; ?>, <?php echo $user_geocode['long_utilisateur']; ?>); // Correspond au coordonnées de l'utilisateur
  var myOptions = {
        zoom: 17, // Zoom par défaut
        center: latLng, // Coordonnées de départ de la carte de type latLng
        mapTypeId: google.maps.MapTypeId.TERRAIN, // Type de carte, différentes valeurs possible HYBRID, ROADMAP, SATELLITE, TERRAIN
        maxZoom: 20
  };

        map = new google.maps.Map(document.getElementById('map'), myOptions);

        var marker = new google.maps.Marker({
        position : latLng,
        map : map,
        title : "Lille"

  });

    var contentMarker = 'Retrouvez moi ici !!!'

    var infoWindow = new google.maps.InfoWindow({
    content  : contentMarker,
    position : latLng
});

google.maps.event.addListener(marker, 'click', function() {
    infoWindow.open(map,marker);
});
};

</script>

<header>

<!-- Menu de navigation -->

<?php include('navbar.php') ?>

</header>
<div class="container-fluid">
 <div class="row profile">
    <div class="col-md-3">
      <div class="profile-sidebar">
        <div class="profile-userpic">

        <!-- Avatar par défaut -->

          <?php if ( !empty($userinfo['avatar'])){ ?>
                <img src="profil/avatar/<?php echo $userinfo['avatar'];?>" class="img-responsive" alt="">

                    <?php } ?>

        </div>
        <div class="profile-usertitle">
          <div class="profile-usertitle-name">
        <?php echo $userinfo['nom']." ".$userinfo['prenoms'];  ?>

          </div>
          <div class="profile-usertitle-job">
            <?php  echo $userinfo['profession'];   ?>
          </div>
        </div>
        <div class="profile-userbuttons">
          <button type="button" class="btn btn-success btn-sm">Publier</button>
          <button type="button" class="btn btn-danger btn-sm">Message</button>
        </div>
        <div class="profile-usermenu">
          <ul class="nav">
            <li class="active">
              <a href="profil.php?id=<?php echo $userinfo['id']; ?>">
              <i class="glyphicon glyphicon-user"></i>
              Aperçu </a>
            </li>
            <li>       <!-- Si l'id de la session ouverte existe alors on a accès au paramètre du compte -->

             <?php if ( isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) { ?>

              <a href="param_profil.php">
              <i class="glyphicon glyphicon-cog"></i>
              paramètres de compte</a> <?php } ?>

            </li>

            <li>    <!-- On a accès au contact du membre connecté ou du membre inscrit sélectionné -->

              <a href="contact.php?id=

                  <?php

                      if (isset($_GET['id']) AND $_GET['id'] != $userinfo['id'] ) {

                                  $id_new = $_GET['id'];

                                  echo $id_new ;

                      }else{

                        echo $userinfo['id'];
                      }

                  ?> ">
              <i class="glyphicon glyphicon-phone"></i>
              Contact </a>
            </li>
            <li>
              <a href="mbr_inscrits.php">
              <i class="glyphicon glyphicon-list-alt"></i>
              Membres inscrits </a>
            </li>
            <li> <!-- Si l'id de la session ouverte existe alors on a accès à la suppression du compte -->

              <?php if ( isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) { ?>

              <a href="suppression.php">
              <i class="glyphicon glyphicon-remove-sign"></i>
              Désactiver votre compte</a> <?php } ?>

            </li>

          </ul>

        </div>

      </div>
 <iframe scrolling="no" frameborder="0" allowTransparency="true" src="https://www.deezer.com/plugins/player?format=classic&autoplay=false&playlist=false&width=278&height=350&color=007FEB&layout=dark&size=medium&type=radio&id=radio-30991&app_id=1" width="278" height="350" id="radio"></iframe>
    </div>
    <div class="col-md-9">

            <div class="profile-content">

            <!-- Affichage de la carte de localisation -->

            <div id="map"></div>

            <h2>Mon espace membre</h2>
            <p>Inscrit depuis le <?php

                  // Date d'inscription du membre !!!!

                  $date_creat=$userinfo['date_creat'];

                  list($date, $time)=explode(" ", $date_creat);

                  list($year, $month, $day)=explode("-", $date);
                  list($hour, $min, $sec)=explode(":", $time);

               $months = array("janvier", "février", "mars", "avril", "mai", "juin",
    "juillet", "août", "septembre", "octobre", "novembre", "décembre");

            echo "".$date_creat="$day ".$months[$month-1]." $year à ${hour}h ${min}m ${sec}s";?></p>
            <br><br>
            <address> <!-- Informations personnelles du membre -->

            <p>Sexe : <?php echo $userinfo['sexe'];?><br>Age : <?php echo $userinfo['age'] ?><br>Habite à : <?php echo $userinfo['habitat'];?><br> Téléphone : <?php  echo $userinfo['tel']; ?><br> Email : <a href="mailto:">
            <?php echo $userinfo['email'] ;  ?> </a></p>
            </address>

            <form method="post" action="">

             <div class="form-group">
                 <label for="titre">Titre:</label>
                 <input class="form-control" type="text" name="titre" id="titre" placeholder="Titre">
            </div>
                <textarea rows="3" cols="51" name="article" placeholder="Postez un commentaire ici..."></textarea><br>
                        <button type="submit" name="envoyer" class="btn btn-primary" value="envoyer">Poster</button>
            </form><br>

                          <?php if (isset($erreur)) {echo '<font color="red">'.$erreur.'</font>'; } ?><br><br>

                        <ul>
                          <?php

                              // On affiche les titres d'articles du membre dont l'id est passé par l'URL !!!

                              if (isset($_GET['id'])) {

                                $idd=intval($_GET['id']);

                                $art= $bdd->prepare('SELECT * FROM message WHERE id_membre= :id ');
                                $art->bindparam('id',$idd);
                                $art->execute();

                                  while ($a = $art->fetch()) {

                                    $id_msg=$a['id'];

                                    // Requête pour récuperer le nombre de commentaire émis !!

                                $stmt= $bdd->prepare('SELECT id FROM commentaire WHERE id_comment= :id ');
                                $stmt->bindparam('id',$id_msg);
                                $stmt->execute();

                                    $nbr_comment=$stmt->rowCount();

                                    echo '<div class="row" id="article">';
                                    echo '<li>';

                                      $date_creat=$a['date_cre'];

                                       list($date, $time)=explode(" ", $date_creat);
                                       list($year, $month, $day)=explode("-", $date);
                                       list($hour, $min, $sec)=explode(":", $time);

                                     echo '<h3>'.'<a href="post.php?id='.$id_msg.'">'.$a['titre'].'</a>'.'<p class="nbr_comment">'.' ('.$nbr_comment.' commentaires)'.'</p>'.'</h3>';

                                      echo ' Posté par '.'<strong>'.$a['nom_mbr'].'</strong>';
                                      echo' le '.$date_creat="$day/$month/$year à $time";

                                      if ($_GET['id'] !== $_SESSION['id']) {

                                         if ($a['nom_mbr'] !== $_SESSION['nom']) {


                                      }else{

                                        echo '<a href="#" class="post">'." Supprimer".'</a>';
                                      echo '<a href="#" class="post">'." Modifier".'</a>';
                                      }

                                      }else{

                                            if ($a['nom_mbr'] !== $_SESSION['nom']) {

                                        echo '<a href="#" class="post">'." Supprimer".'</a>';
                                      }else{

                                        echo '<a href="#" class="post">'." Supprimer".'</a>';
                                      echo '<a href="#" class="post">'." Modifier".'</a>';
                                      }


                                      }

                                        echo '</li>';
                                        echo '</div>';


                            }
                             } ?>
                        </ul>




           <br><br><div>
            <h4>Mes posts et mes différentes actualités!</h4><br>

            </div>

            </div>
    </div>
  </div>
   <div class="pieds">
  <center><a href="inscription.php"><span class="glyphicon glyphicon-copyright-mark"></span>Copyright
   </a> </center>
   </div>
</div>

</body>
</html>
<?php

}else{

header('Location:index.php');

}
?>












