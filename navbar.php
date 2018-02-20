<!-- Menu de navigation -->


<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
       <a class="navbar-brand" href="home.php">

      <!-- Si l'id de la session ouverte existe alors
      on souhaite la bienvenue au membre connecté -->

       <?php if (isset($_SESSION['id'])) {

        echo "Bienvenue"." ".$_SESSION['prenoms'] ." "."!";

      } ?> </a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="profil.php?id=<?php echo $_SESSION['id']; ?>"><span class="glyphicon glyphicon-home"></span> Mon accueil</a></li>
            <li><a href="#">Actualités</a></li>
      <li><a href="#">Tutoriels</a></li>
       <li><a href="#">Forum</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">

      <li><a href="deconnexion.php"><span class="glyphicon glyphicon-log-out"></span> Déconnexion</a></li>
    </ul>
  </div>
</nav>
