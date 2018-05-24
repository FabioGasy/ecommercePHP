<?php

session_start();
$user ='lita';
$password1='lita';

if (isset($_POST['submit'])) {
	# code...
$username = $_POST['username'];
$password = $_POST['password'];
if($username&&$password){
	if ($username==$user&&$password==$password1) {
	$_SESSION['username']=$username;
	header('Location: admin.php');
	}else{
		?>
		<script> alert ("identifiant érroné!!");</script>
	
		<?php
	}

	}else{
    echo 'veuillez remplir tous les champs!!';
}


}
?>
<link href="../style/bootstrap.min.css" rel="stylesheet">
<h1>Administration - Connexion</h1>
<form action="" method="POST">
	<h3>Pseudo :</h3><input type="text" name="username"></br></br>
	<h3>Mot de passe :</h3><input type="password" name="password"></br></br>
	<input type="Submit" name="submit" value="Connecter"></br></br>
</form>