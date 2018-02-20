<?php

// Connexion à la base de données !!!

include('connexbdd.php');


// On vérifie si l'id de la session ouverte existe !!!

if (isset($_SESSION['id'])) {

      $id = intval($_SESSION['id']);

      // On récupère les informations du membre dans la base de données à l'aide d'une requête préparée !!

      $stmt = $bdd->prepare('SELECT * FROM membres WHERE id= ? ');
      $stmt->execute(array($id));
      $userinfo = $stmt->fetch();

    // On vérifie si le bouton envoyer du formulaire existe !!!

    if (isset($_POST['envoyer']) AND $_POST['envoyer'] = "Envoyer") {

      // On sécurise les données et on les récupère dans des variables !!!

      $ancienemail=htmlspecialchars($_POST['ancienemail']);
      $nouvemail=htmlspecialchars($_POST['nouvemail']);

      // On vérifie si le formulaire n'est pas vide !!!

      if (!empty($_POST['ancienemail']) AND !empty($_POST['nouvemail'])) {

        // On vérifie si le mail entré ne coincide pas avec le mail existant du membre !!!

        if ($ancienemail !== $nouvemail) {

          // On filtre le nouveau mail entré pour voir qu'il est correcte !!!

          if (filter_var($nouvemail, FILTER_VALIDATE_EMAIL)){

                      // On envoie une requête pour voir une ligne ou le mail entré
                      // existe dans la base de données !!!

                      $stmt= $bdd->prepare('SELECT 1 FROM membres WHERE email= :email ');
                      $stmt->bindparam('email',$nouvemail);
                      $stmt->execute();

                                    // Si le resultat est faux alors !!!

                                    if (FALSE == $stmt->fetch() ){

                                  // On envoie une requête préparée à la base de données
                                  // pour mettre à jour le nouveau mail entré !!!

                                    $stmt = $bdd->prepare('UPDATE membres SET email = ? WHERE id = ?');
                                    $stmt->execute(array($nouvemail,$id));

                                      // On redirige le membre vers son profil à jour !!!

                                       header('Location:profil.php?id='.$_SESSION['id']);

                                }else{

                                    $erreur="Ce mail exite dans notre base !";

                                    }

                                    }else{

                                      $erreur="Entrez une adresse email correcte pour continuer !";
                                    }

        }else{

          $erreur=" veuillez taper une adresse email différente pour une mise à jour MERCI !";

        }

      }else{

        $erreur="Veuillez remplir tous les champs pour une modification !";
      }

        // On vérifie si une image existe et si le champ n'est pas vide !!!

        if (isset($_FILES['image']) AND !empty($_FILES['image']['name'])) {

                  // On vérifie que la taille de l'image ajouter ne dépasse pas 2Mo,
                  // On charge ensuite les extensions valides dans un tableau !!!

                  $taillemax=2097152;
                  $extensionsvalides=array('jpg','jpeg','gif','png');

                  // On vérifie si la taille de l'image respecte les norme ainsi que l'extension téléchargé !!!
                  // Si oui on met l'avatar a jour dans la base de données !!!

                  if ($_FILES['image']['size'] <= $taillemax) {

                      $extensiontelecharg= strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));

                            if (in_array($extensiontelecharg, $extensionsvalides)) {

                                      // On stock l'image telechargé dans un dossier à la du serveur avec l'id du mem bre !!!

                                      $chemin= "profil/avatar/".$_SESSION['id'].".".$extensiontelecharg;
                                      $result= move_uploaded_file($_FILES['image']['tmp_name'], $chemin);
                                      if ($result) {

                                        // On met à jour l'avatar dans la base de données !!!

                                        $stmt= $bdd->prepare('UPDATE membres SET avatar= :avatar WHERE id= :id');
                                        $stmt->execute(array(


                                            'avatar'=> $_SESSION['id'].".".$extensiontelecharg,
                                            'id'=> $_SESSION['id']

                                          ));

                                        header('Location:profil.php?id='.$_SESSION['id']);


                                      }else{

                                        $erreur="Erreur durant le téléchargement !!!";
                                      }


                            }else{

                              $erreur="Votre photo de profil doit être au format jpg, jpeg, gif ou png";
                            }


                  }else{

                    $erreur="Votre photo de profil ne doit pas depasser 2Mo";
                  }

        }

    // Sinon si c'est le bouton envoyer1 qui existe !!!

    }elseif (isset($_POST['envoyer1']) AND $_POST['envoyer1'] = "Envoyer1") {

        // On récupère les données dans des variables sécurisées !!!

        $habitation=htmlspecialchars($_POST['habitation']);
        $profession=htmlspecialchars($_POST['profession']);
        $telephone=htmlspecialchars($_POST['telephone']);

      // On vérifie si tous les champs du formulaire sont vide , si oui on exige de remplir tous les
        // champs !!!

      if (empty($_POST['habitation']) OR empty($_POST['profession']) OR empty($_POST['telephone'])  ) {

                                    $err="Veuillez remplir tous les champs !";

                                     }else{

                                    // requête préparée à la base de données pour une mise à jour !!!

                                    $stmt= $bdd->prepare('UPDATE membres SET habitat = ?, profession = ?, tel = ? WHERE id = ?');
                                    $stmt->execute(array($habitation,$profession,$telephone,$id));

                                      // On redirige le membre vers son profil à jour !!!

                                    header('Location:profil.php?id='.$_SESSION['id']);

                                     }

     // Sinon si c'est le bouton envoyer2 qui existe !!!

    }elseif (isset($_POST['envoyer2']) AND $_POST['envoyer2'] = "Envoyer2") {

                  // On récupère les mots de passe sécurisées !!!

                  $mdpactuel = sha1($_POST['mdpactuel']);
                  $nouvmpd = sha1($_POST['nouvmpd']);
                  $confirmdp = sha1($_POST['confirmdp']);

                  // On vérifie si tous les champs du formulaire sont remplis !!!

                  if (!empty($_POST['mdpactuel']) AND !empty($_POST['nouvmpd']) AND !empty($_POST['confirmdp'])  ) {

                    // Requête préparée à la base pour vérifier si le mot de passe actuel est correcte !!!

                    $stmt= $bdd-> prepare('SELECT * FROM membres WHERE id= ?');
                    $stmt->execute(array($id));

                    $test= $stmt->fetch();

                    $testmdp= $test['motpass'];

                    // On vérifie le mot de passe actuel saisi et celui de la base de données !!!

                    if ($testmdp == $mdpactuel) {

                    // On vérifie si les deux nouveaux mots de passe coincident !!

                    if ($nouvmpd == $confirmdp) {


                                  // Mise à jour du mot de passe dans la base de données !!!

                                    $stmt= $bdd->prepare('UPDATE membres SET motpass = ? WHERE id = ?');
                                    $stmt->execute(array($nouvmpd,$id));

                                  // On redirige le membre vers son profil à jour !!!

                                    header('Location:profil.php?id='.$_SESSION['id']);


                  }else{

                        $error="Vos nouveaux mots de passe ne correspondent pas !";
                        }


                }else{

                      $error="Votre mot de passe actuel n'est pas correcte !!!";
                        }

                  }else{

                        $error="Veuillez saisir tous les champs pour verification !";
                       }

    // Sinon si c'est le bouton annulé qui existe alors on rédirige le membre sur son profil
    // sans modification !!!

    }elseif (isset($_POST['annuler']) AND $_POST['annuler'] = "annuler") {


        header('Location:profil.php?id='.$_SESSION['id']);

    }

    if (isset($_POST['enregistrer']) AND $_POST['enregistrer']="Enregistrer") {

        $latitude=htmlspecialchars($_POST['latitude']);
        $longitude=htmlspecialchars($_POST['longitude']);
        $localite=htmlspecialchars($_POST['localite']);

        if (!empty($_POST['latitude']) AND !empty($_POST['longitude']) AND !empty($_POST['localite'])) {

                    $stmt= $bdd->prepare('UPDATE membres SET lat_utilisateur = ?, long_utilisateur = ? WHERE id = ?');
                    $stmt->execute(array($latitude,$longitude,$id));

                    header('Location:profil.php?id='.$_SESSION['id']);

        }else{

          $err_localisation="Veuillez laisser tous les champs remplis !!!";
        }

    }

    ?>

<!DOCTYPE html>
<html>
<head>
    <title>Espace membre</title>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="styles/style1.css">

   <!-- Biliothèque google map api -->
   <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxHMB3-nltaB3XJIp5IxbsmV29PonBXCA&libraries=places"></script>
</head>

<!-- Script google map api -->

<script type="text/javascript">
 var geocoder = new google.maps.Geocoder();

            function geocodePosition(pos) {
                geocoder.geocode({
                    latLng: pos
                }, function(responses) {
                    if (responses && responses.length > 0) {
                        updateMarkerAddress(responses[0].formatted_address);
                    } else {
                        updateMarkerAddress('Aucune coordonnée trouvée!');
                    }
                });
            }


            function updateMarkerPosition(latLng) {
                document.getElementById('latitude').value = [
                    latLng.lat(),

                ].join(', ');

                document.getElementById('longitude').value = [
                    latLng.lng(),

                ].join(', ');

            }
            function updateMarkerAddress(str) {
                document.getElementById('searchTextField').value = str;
            }

            function initialize() {
                var latLng = new google.maps.LatLng(7.539988999999998, -5.547080000000051);
                var map = new google.maps.Map(document.getElementById('mapCanvas'), {
                    zoom: 6,
                    center: latLng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: false,
                    streetViewControl: false,

                });
                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    draggable: true,
                    title:"Vous pouvez me tirer-déposer pour la destination souhaitée."
                });

                var input = document.getElementById('searchTextField');
                var options = {componentRestrictions: {country: 'CI'}};
        var autocomplete = new google.maps.places.Autocomplete(input,options);

        autocomplete.bindTo('bounds', map);

                // Mettre à jour les informations de position actuelle.
                updateMarkerPosition(latLng);
                //geocodePosition(latLng);

                // Add dragging event listeners.
                google.maps.event.addListener(marker, 'dragstart', function() {
                    updateMarkerAddress('Dragging...');
                });

                google.maps.event.addListener(marker, 'drag', function() {
                    updateMarkerPosition(marker.getPosition());
                });

                google.maps.event.addListener(marker, 'dragend', function() {
                    geocodePosition(marker.getPosition());

                });

                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    input.className = '';
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // Informer l'utilisateur que l'endroit n'a pas été trouvé et le retour.
            input.className = 'pas trouvé';
            return;
          }

          // Si le lieu a une géométrie, le présenter sur une carte.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  //Pourquoi 17? Parce qu'il semble bon pour la vue.
          }
            marker.setPosition(place.geometry.location);
            updateMarkerPosition(marker.getPosition());
            //geocodePosition(marker.getPosition());

        });
            }

            // Gestionnaire Onload pour déclencher l'application.
            google.maps.event.addDomListener(window, 'load', initialize);
</script>



<body>
<header>

<!-- Menu de navigation -->

<?php include('navbar.php') ?>

</header>
<div class="container">

<h2>Informations à mettre à jour :</h2>
<div class="row">

    <div class="col-md-8">

      <!-- Formulaire modification email et avatar -->

          <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
          <h4>Modifier votre email</h4><br>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="email">Ancien email:</label>
                <div class="col-sm-8">

                 <input type="email" name="ancienemail" class="form-control" id="email" placeholder="Entrez email actuelle" value="<?php if (isset($_SESSION['id'])) { echo $userinfo['email'];} ?>">
                </div>
                </div>
              <div class="form-group">
              <label class="control-label col-sm-4" for="email">Nouveau email:</label>
                <div class="col-sm-8">
             <input type="email"  name="nouvemail" class="form-control" id="email" placeholder="Entrez nouveau email">
            </div>
             </div>
             <div class="form-group">

              <label class="control-label col-sm-4" for="image">Ajouter une image:</label>
              <div class="col-sm-8">

              <input type="file" name="image" id="image">
              </div>
              </div>
              <div class="form-group">
               <div class="col-sm-offset-4 col-sm-8">
             <button type="submit"  name="envoyer" class="btn btn-primary" value="Envoyer">Envoyer</button>
              </div>
               </div>
          </form><br>

          <!-- Affichage des messages d'alertes -->

          <center><?php if (isset($erreur)) {
              echo '<font color="red">'.$erreur.'</font>';}?></center>
          </div>
        <div class="col-md-4"></div>
</div>

<div class="row" id="info">

 <div class="col-md-4">

     <!-- Formulaire modofication informations personnelles -->
      <form class="form-horizontal" method="post" action="">
      <h4>Informations personnelles</h4><br>

               <div class="form-group">
                  <label class="control-label col-sm-" for="habitation" id="a">Habitation :</label>
                <div class="col-sm-">
                 <input type="text"  name="habitation" class="form-control" id="habitation" value="<?php if (isset($_SESSION['id'])) { echo $userinfo['habitat'];} ?>">
                </div>
                </div>
              <div class="form-group">
              <label class="control-label col-sm-" for="profession" id="c">Profession :</label>
                <div class="col-sm-">
             <input type="text"  name="profession" class="form-control" id="profession" value="<?php if (isset($_SESSION['id'])) { echo $userinfo['profession'];} ?>">
            </div>
             </div>
             <div class="form-group">
                  <label class="control-label col-sm-" for="telephone" id="e">Téléphone :</label>
                <div class="col-sm-">
                 <input type="tel"  name="telephone" class="form-control" id="telephone" value="<?php if (isset($_SESSION['id'])) { echo $userinfo['tel'];} ?>">
                </div>
                </div>

              <div class="form-group">
               <div class="col-sm-offset-4 col-sm-8">
             <button type="submit" name="envoyer1" class="btn btn-primary" value="Envoyer1">Envoyer</button>
              </div>
               </div>

               <center><?php if (isset($err)) {
              echo '<font color="red">'.$err.'</font>'; }?></center><br>

      </form>

      <form class="form-horizontal" method="post" action="">


       <div class="row" id="adresse">
       <h4>Localisation</h4><br>

               <div class="form-group">

                  <label class="control-label col-sm-" id="g">Latitude:</label>
                  <div class="col-sm-">
                  <input id="latitude" type="text" value="" class="form-control" id="h" name="latitude">
                  </div>
                </div>

              <div class="form-group" id="position">
                  <label class="control-label col-sm-" id="i">Longitude:</label>
                  <div class="col-sm-">
                  <input id="longitude" type="text" value="" class="form-control" id="j" name="longitude">
                  </div>
              </div>

              <div class="form-group">

                  <div class="row" id="row_position">
                  <label class="control-label" id="k">Localité:</label>
                  <div class="col-sm-11">
                  <input id="searchTextField" type="text" class="form-control" size="45" id="l" name="localite">
                  </div>
                  <div class="col-sm-1">
                  <button type="submit" name="enregistrer" class="btn btn-primary" value="Enregistrer">Sauvegarder ma position</button>
                  </div>
                  </div>


              </div>

                <center><?php if (isset($err_localisation)) {
              echo '<font color="red">'.$err_localisation.'</font>'; }?></center><br>

              </div>
        </form>





          <!-- Affichage des messages d'alertes -->


          </div>

          <!-- Carte google Map -->
        <div class="col-md-8">


                <div id="mapCanvas"></div>

        </div>

</div>

<div class="row">

        <div class="col-md-8">

     <!-- Formulaire modification mot de passe -->

      <form class="form-horizontal" method="post" action="">
         <h4>Modifiez votre mote de passe </h4><br>
               <div class="form-group">
                  <label class="control-label col-sm-4" for="password">Mot de passe actuel:</label>
                <div class="col-sm-8">
                 <input type="password" name="mdpactuel" class="form-control" id="password" placeholder="Entrez mot de passe actuel">
                </div>
                </div>
              <div class="form-group">
              <label class="control-label col-sm-4" for="password">Nouveau mot de passe:</label>
                <div class="col-sm-8">
             <input type="password" name="nouvmpd" class="form-control" id="password" placeholder="Nouveau mot de passe">
            </div>
             </div>
             <div class="form-group">
              <label class="control-label col-sm-4" for="password">Retapez le mot de passe saisi :</label>
                <div class="col-sm-8">
             <input type="password" name="confirmdp" class="form-control" id="password" placeholder="Retapez à nouveau">
            </div>
             </div>
              <div class="form-group">
               <div class="col-sm-offset-4 col-sm-8">
             <button type="submit" name="envoyer2" class="btn btn-primary" value="Envoyer2">Envoyer</button>
              </div>
               </div>
          </form>

          <!-- Affichage des messages d'alertes -->

          <center><?php if (isset($error)) {
              echo '<font color="red">'.$error.'</font>';}?></center><br>
          </div>
          <div class="col-md-4"></div>

    </div>
  <center>
    <div class="row" id="annuler">
    <div class="col-sm-offset-5 col-sm-7">

        <!-- Formulaire bouton annuler -->

        <form method="post" action="">
             <button type="submit" name="annuler" class="btn btn-primary" value="annuler" id="bouton"><span class="glyphicon glyphicon-arrow-left" id="precedent"></span> Précédent</button>

        </form>
    </div>

    </div>
  </center>
</div>



<div>
  <p><center><a href="inscription.php"><span class="glyphicon glyphicon-copyright-mark"></span>Copyright
   </a> </center>
  </p>
   </div>
</body>
</html>

<?php
}
else{

  header('Location:index.php');
}

?>
