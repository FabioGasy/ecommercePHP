<?php
session_start(); 
try{
 $bdd = new PDO('mysql:host=localhost;dbname=sitecommerce', 'root', '');
 $bdd -> setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
 $bdd ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
catch(Exception $e){
        echo "une erreur est survenue";
        die('Erreur : '.$e->getMessage());
      } 
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf8">
	 <link href="style/bootstrap.min.css" rel="stylesheet">
</head>
<header>
	<h1><Site E-commerce</h1></br>
  <ul class="menu ">
  		 <li> <a href="index.php">Accueil</a></li>
  		 <li><a href="boutique.php">Boutique</a></li>
  		 <li> <a href="panier.php">Panier</a></li>
  		 <li><a href="condition.php">Conditions Générales de Ventes</a></li> 
  </ul>
</header>
</html>