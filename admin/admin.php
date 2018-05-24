<?php
session_start();
?>
<link href="../style/bootstrap.min.css" rel="stylesheet">
<h1>Bienvenue, <?= $_SESSION['username'];?></h1></br>
<a href="?action=ajouter">Ajouter un produit</a> | 
<a href="?action=modifieretsupprimer">Modifier / Supprimer un produit</a></br></br>
<a href="?action=ajouterCategorie">Ajouter categorie</a> | 
<a href="?action=modifieretsupprimercategorie">Modifier / Supprimer une categorie</a></br></br>


<a href="?action=options">Options</a></br></br>
<?php
  		 	try{
 $bdd = new PDO('mysql:host=localhost;dbname=sitecommerce', 'root', '');
 $bdd -> setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
 $bdd ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
catch(Exception $e){
        echo "une erreur est survenue";
        die('Erreur : '.$e->getMessage());
			} 
if(isset($_SESSION['username'])){
		if(isset($_GET['action'])){
	if ($_GET['action']== 'ajouter') {
	if (isset($_POST['submit'])) {
		

		$titre = $_POST['titre'];
  $description = $_POST['description'];
  		 $prix = $_POST['prix'];
    $categorie = $_POST['categorie'];
   	$poids     = $_POST['poids'];
   	$stock	   = $_POST['stock'];



  		 $img = $_FILES['img']['name'];
  		 $img_tmp = $_FILES['img']['tmp_name'];
  		 if (!empty($img_tmp)) {
  		 	$image = explode('.',$img);
  		 	$image_ext = end($image);

  		 	if (in_array(strtolower($image_ext),array('png','jpg','jpeg'))==false) {
  		 		echo "veuillez rentrez une image ayant pour extension : png,jpg ou jpeg";
  		 	}else{
  		 		$image_size = getimagesize($img_tmp);
  		 			if ($image_size['mime']=='image/jpeg') {
  		 				$image_src = imagecreatefromjpeg($img_tmp);
  		 			}else if ($image_size['mime']=='image/png') {
  		 				$image_src = imagecreatefrompng($img_tmp);
  		 			}else{
  		 				$image_src = false;
  		 				echo "veuillez rentrez une image valide!!";
  		 			}

  		 			if ($image_src!==false) {
  		 				$image_width = 200;
  		 				if ($image_size[0]==$image_width) {
  		 					
  		 					$image_final = $image_src;
  		 				}else{

  		 					$new_width[0] = $image_width;
  		 					$new_height[1]= 200;
  		 					$image_final  = imagecreatetruecolor($new_width[0],$new_height[1]);

  		 					imagecopyresampled($image_final,$image_src,0,0,0,0,$new_width[0],$new_height[1],$image_size[0],$image_size[1]);
  		 				}
  		 				imagejpeg($image_final,'img/'.$titre.'.jpg');
  		 			}
  		 	}
  		 	
  		 }else{
  		 	echo "veuillez mettre une image!!";
  		 }
  		 if($titre&&$description&&$prix&&$stock){

  		 	
  		 

  		 	$select = $bdd ->query("SELECT prix FROM poids WHERE name='$poids'");
  		 	$s = $select ->fetch(PDO::FETCH_OBJ);
  		 	$shipping = $s -> prix;
  		 

  		 	$oldprix =$prix;
  		 	$select1 = $bdd ->query("SELECT tva FROM produit");
  		 	$rs = $select1 ->fetch(PDO::FETCH_OBJ);
  		 	$tva = $rs -> tva;


  		 	$prixFin = $oldprix +$shipping;
  		 	$prixFinale = $prixFin+($prixFin*$tva/100);


			$insert=$bdd->query("INSERT INTO produit(titre,description,prix,categorie,poids,shipping,tva,prixFinale,stock) VALUES ('$titre','$description','$prix','$categorie','$poids','$shipping','$tva','$prixFinale','$stock')");
			
			
			
  		 }else{
  		 	echo "veuillez remplir tous les champs !!";
  		 }
	}
	?>
<form action="" method="POST" enctype="multipart/form-data">
		<h3>Titre du produit :</h3><input type="text" name="titre"/>
		<h3>Description du produit :</h3><textarea name="description"></textarea>
		<h3>Prix :</h3><input type="text" name="prix"/>
		<h3>Stock :</h3><input type="text" name="stock"/></br></br>
		<h3>Categorie :</h3><select name="categorie">
				<?php 
					$select = $bdd ->query("SELECT * FROM categorie");
					while ($s = $select ->fetch(PDO::FETCH_OBJ)) {
				?>
				<option><?= $s->name;?></option>
				<?php
					 } 
				?>
							</select>
		<h3>Poids : plus de</h3><select name="poids">
			<?php 
			$select = $bdd ->query("SELECT name FROM poids");
			while ($res = $select ->fetch(PDO::FETCH_OBJ)) {
					

				?>
				<option><?= $res->name;?></option>
				<?php
					 } 
				?>
						</select>
		<h3>Image :</h3><input type="file" name="img"/></br> 
		<input type="submit" name="submit" value="Enregistrer">
</form>

	<?php
        }elseif ($_GET['action']=='modifieretsupprimer') {

		$select = $bdd -> prepare("SELECT * FROM produit");
		$select -> execute();
		?>
		<table class="table">
	 <thead>
      		<tr>
        		<th>Nom</th>
        		<th>Description</th>
        		<th>Prix</th>
        		<th>Categorie</th>
        		<th>Poids</th>
        		<th>Stock</th>
        		<th>Options</th>
      		</tr>
    </thead>



		<?php
		while ($s =$select->fetch(PDO::FETCH_OBJ)) {
			echo "<tr>";
            echo "<td>".$s->titre."</td>";
            echo "<td>".$s->description."</td>";
            echo "<td>".$s->prix."</td>";
            echo "<td>".$s->categorie."</td>";
            echo "<td>".$s->poids."</td>";
            echo "<td>".$s->stock."</td>";
            
			?>
			
			<td>
			<a href="?action=modifier&amp;id=<?=$s->id;?>">Modifier</a>
			<a href="?action=effacer&amp;id=<?=$s->id;?>" onClick="return confirm('Voulez-vous vraiment effacez ce contenu ?')">Supprimer</a>
		</td></br>
            </table>
			<?php
		}
			}
			elseif ($_GET['action']=='modifier') {
				$id=$_GET['id'];
				$select = $bdd -> prepare("SELECT * FROM produit WHERE id=$id");
				$select -> execute();
				$data=$select->fetch(PDO::FETCH_OBJ);
				?>
			<form action="" method="POST">
					<h3>Titre du produit :</h3><input type="text" name="titre" value="<?=$data->titre;?>"/>
					<h3>Description du produit :</h3><textarea name="description" ><?=$data->description;?></textarea>
					<h3>Prix :</h3><input type="text" name="prix" value="<?=$data->prix;?>"/>
					<h3>Stock :</h3><input type="text" name="stock" value="<?=$data->stock;?>"/>
					<h3>Categorie :</h3><select name="categorie">
				<?php 
					$select = $bdd ->query("SELECT * FROM categorie");
					while ($s = $select ->fetch(PDO::FETCH_OBJ)) {
				?>
				<option><?= $s->name;?></option>
				<?php
					 } 
				?>
					<input type="submit" name="submit" value="Modifier">
			</form>
				<?php
				if (isset($_POST['submit'])) {
						$titre       = $_POST['titre'];
  						$description = $_POST['description'];
  		 				$prix        = $_POST['prix'];
  		 				$categorie   = $_POST['categorie'];
  		 				$stock       = $_POST['stock']; 

  		 			$update = $bdd ->prepare("UPDATE produit SET titre='$titre',description='$description',prix='$prix',categorie='$categorie',stock='$stock' WHERE id=$id");
  		 			$update -> execute();
  		 			header('Location: admin.php?action=modifieretsupprimer');
				}

			}
			elseif ($_GET['action']=='effacer') {
		$id=$_GET['id'];
		$delete = $bdd -> prepare("DELETE FROM produit WHERE id=$id");
		$delete -> execute();
		header('Location: admin.php?action=modifieretsupprimer');
			}
			elseif($_GET['action']=='ajouterCategorie'){
					if (isset($_POST['submit'])) {
						$name = $_POST['name'];
							if ($name) {
								$insert=$bdd->prepare("INSERT INTO categorie (name) VALUES ('$name')");
								$insert ->execute();
							}else{

								echo "veuillez remplir le champs s'il vous plait !!";
							}
					}
				?>
				<form action="" method="POST">
					<h3>Nom de la categorie :</h3><input type="text" name="name"><br><br>
					<input type="submit" name="submit" value="ajouter">



				</form>
				<?php
			}elseif ($_GET['action']=='modifieretsupprimercategorie') {
					$select = $bdd -> prepare("SELECT * FROM categorie");
					$select -> execute();
				?>
			<table class="table">
	 	<thead>
      		<tr>
        		<th>Nom du categorie</th>
      		</tr>
   		 </thead>
   		 		<?php
				while ($s =$select->fetch(PDO::FETCH_OBJ)) {
			echo "<tr>";
            echo "<td>".$s->name."</td>";
            
			?>
			
			<td><a href="?action=modifiercategorie&amp;id=<?=$s->id;?>">Modifier</a></td>
			<td><a href="?action=effacercategorie&amp;id=<?=$s->id;?>" onClick="return confirm('Voulez-vous vraiment effacez ce contenu ?')">Supprimer</a></td></br>
			
			<?php
			}
			}elseif ($_GET['action']=='modifiercategorie'){
				$id=$_GET['id'];
				$select = $bdd -> prepare("SELECT * FROM categorie WHERE id=$id");
				$select -> execute();
				$data=$select->fetch(PDO::FETCH_OBJ);
				?>
			<form action="" method="POST">
					<h3>Nom de la categorie :</h3><input type="text" name="name" value="<?=$data->name;?>"/></br>
					<input type="submit" name="submit" value="Modifier">
			</form>
				<?php
				if (isset($_POST['submit'])) {
						$name = $_POST['name'];

  		 			$update = $bdd ->prepare("UPDATE categorie SET name='$name' WHERE id=$id");
  		 			$update -> execute();
  		 			header('Location: admin.php?action=modifieretsupprimercategorie');
				}


			}elseif ($_GET['action']=='effacercategorie'){
		$id=$_GET['id'];
		$delete = $bdd -> prepare("DELETE FROM categorie WHERE id=$id");
		$delete -> execute();
		header('Location: admin.php?action=modifieretsupprimercategorie');

			}elseif ($_GET['action']=='options') {
				?>
					<h2>Frais de port :</h2><br>
					<h3>Options de poids(plus de)</h3>
				<?php
				$select = $bdd ->query("SELECT * FROM poids");

				while ($s = $select -> fetch(PDO::FETCH_OBJ)) {
					?>
					<form action="" method="POST">
						<input type="text" name="poids" value ="<?= $s -> name;?>"> <a href="?action=modifierPoids&amp;name=<?= $s -> name;?>">Modifier</a>
					</form>

					<?php
				
				}

				$select = $bdd ->query("SELECT tva FROM produit");
				$s = $select -> fetch(PDO::FETCH_OBJ);
					if (isset($_POST['submit2'])) {
						$tva = $_POST['tva'];
						if ($tva) {
							$supdate = $bdd ->query("UPDATE produit SET tva=$tva");
						}
					}
				?>
					<h3>TVA :</h3>
					<form action="" method="POST">
						<input type="text" name="tva" value="<?= $s -> tva;?>">
						<input type="submit" name="submit2" value="Modifier">
					</form>
					<?php
				
			}elseif ($_GET['action']=='modifierPoids') {

				$poidsTaloha = $_GET['name'];
				$select = $bdd ->query("SELECT * FROM poids WHERE name=$poidsTaloha");
				$rs = $select -> fetch(PDO::FETCH_OBJ);

						if (isset($_POST['submit'])) {
							 
							$poids = $_POST['poids'];
							$prix = $_POST['prix'];

							if ($poids&&$prix) {

							$update = $bdd ->query("UPDATE poids SET name='$poids' ,prix='$prix' WHERE name=$poidsTaloha");

							}
						}
					?>
					<h2>Frais de port :</h2><br>
					<h3>Options de poids(plus de)</h3>
					<form action="" method="POST">
						<h3>Poids :</h3><input type="text" name="poids" value="<?= $_GET['name'];?>">
						<h3>Correspond à :</h3><input type="text" name="prix" value="<?= $rs -> prix ;?>"><br><br>
						<input type="submit" value="Modifier" name="submit">
					</form>
				<?php              
			}

			else{
				die('une erreur s\'est produite');
				}
				
				}else{

				}
			}else{
	header('Location: ../index.php');
			}