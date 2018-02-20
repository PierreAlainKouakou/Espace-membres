<?php
    // Connexion à la base de données !!!

    include('connexbdd.php');

    // On vérifie si l'id du membre connecté existe !!!

    if (isset($_SESSION['id'])) {

          $id=intval($_SESSION['id']);

            $stmt = $bdd->prepare('SELECT * FROM membres WHERE id= ? ');
            $stmt->execute(array($id));
            $userinfo = $stmt->fetch();

      if (isset($_GET['id'])) {

        $id_comment=intval($_GET['id']);
        $nom_membre=htmlspecialchars($userinfo['nom']);

        if (isset($_POST['envoyer']) AND $_POST['envoyer'] = "envoyer") {

          $comment=htmlspecialchars($_POST['article']);

            if (!empty($_POST['article'])) {


              $stmt= $bdd->prepare('INSERT INTO commentaire (comment,id_membre,nom_membre,id_comment,image) VALUES (?,?,?,?,?)');
              $stmt->execute(array($comment,$id,$nom_membre,$id_comment,$userinfo['avatar']));

            }else{

              $erreur="Laissez un commentaire svp !!!";
            }



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
   <link rel="stylesheet" type="text/css" href="styles/style1.css">

</head>

<body>

<header>

<!-- Menu de navigation -->

<?php include('navbar.php') ?>

</header>

<div class="container">
  <div class="row" id="entete">
    <div class="col-sm-12">

            <h2>Articles postés</h2>
    </div>
  </div>
 <div class="row" id=content>
    <div class="col-sm-12">
            <div class="profile-content">

                <?php

                    // On vérifie l'id passé par l'URL !!!

                    if (isset($_GET['id'])) {

                          // On récupère l'id !!!

                          $id_new=intval($_GET['id']);

                                    // Requête préparée à la base données pour récupérer l'article dont l'id est passé par l'url !!!

                                    $stmt= $bdd->prepare('SELECT * FROM message WHERE id= :id ');
                                    $stmt->bindparam('id',$id_new);
                                    $stmt->execute();

                          $result= $stmt->fetch();

                            // On récupère la date et on change le format par défaut !!!

                            $date_creat=$result['date_cre'];

                                list($date, $time)=explode(" ", $date_creat);
                                list($year, $month, $day)=explode("-", $date);
                                list($hour, $min, $sec)=explode(":", $time);
                                  ?>
                                  <div class="row" id="article">
                                  <div class="col-sm-12">
                                  <h3><?=$result['titre']?></h3><br>
                                  <?=$result['post']?><br><br>
                                  Posté par<strong><a href="#"> <?=$result['nom_mbr']?> </a></strong>le <?=$date_creat="$day/$month/$year à $time"?><br><br>

                                  <?php
                                      $stmt= $bdd->prepare('SELECT * FROM commentaire WHERE id_comment= :id ORDER BY id DESC');
                                      $stmt->bindparam('id',$id_comment);
                                      $stmt->execute();

                                      while ($recup=$stmt->fetch()) {

                                        $date_comment=$recup['date_comment'];

                                       list($date, $time)=explode(" ", $date_comment);
                                       list($year, $month, $day)=explode("-", $date);
                                       list($hour, $min, $sec)=explode(":", $time);




                                       ?>

                                        <?= "---------------------------------------------------------------------------------------"  ?><br>
                                        <h4><a href="#"><img src="profil/avatar/<?php echo $recup['image'];?>" class="img-responsive" alt="" width="35" id="imagefloat"></a> <a href="#"><?= $recup['nom_membre']; ?></a>
                                        <?=

                                        '<p class="heure">'.$date_comment="$day/$month/$year à $time".'<p/>';
                                        ?>

                                         </h4><br>
                                        <p><?= $recup['comment']; ?></p> <br><br>

                                        <?php
                                      if ($result['id_membre'] !== $_SESSION['id']) {

                                         if ($recup['nom_membre'] !== $_SESSION['nom']) {


                                      }else{

                                        echo '<a href="#" class="post">'." Supprimer".'</a>';
                                      echo '<a href="#" class="post">'." Modifier".'</a>';
                                      }

                                      }else{

                                            if ($recup['nom_membre'] !== $_SESSION['nom']) {

                                        echo '<a href="#" class="post">'." Supprimer".'</a>';
                                      }else{

                                        echo '<a href="#" class="post">'." Supprimer".'</a>';
                                      echo '<a href="#" class="post">'." Modifier".'</a>';
                                      }


                                      } ?>


                                   <?php } ?><br>


                                  <a href="#" class="" onclick="document.getElementById('commenter').style.display = 'block'; this.style.display = 'none';">Commenter</a><span id="commenter">
                                  <form method="post" action="">
                                    <textarea rows="2.5" cols="41" name="article" placeholder="Commentaire ici..."></textarea><br>
                                    <button type="submit" name="envoyer" class="btn btn-primary" value="envoyer">Poster mon commentaire
                                    </button>


                                    </form> </span>
                                  </div>
                                  </div>


                         <?php } ?>


            </div>
    </div>

  </div>

    <div class="pieds">
  <center><a href="#"><span class="glyphicon glyphicon-copyright-mark"></span>Copyright
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
