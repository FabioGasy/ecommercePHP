<?php
	require_once('includes/header.php');
	require_once('includes/sidebar.php');
		if (isset($_GET['show'])) {
			$produit = $_GET['show'];
			$select = $bdd -> prepare("SELECT * FROM produit WHERE titre='$produit'");
			$select -> execute();
			$s = $select -> fetch(PDO::FETCH_OBJ);
			$descri = $s ->description;
			$descrifinale = wordwrap($descri,300,'</br>',false);
			
			
		
			
			?>
		</br><div style="text-align:center;">
			<img src="admin/img/<?= $s->titre; ?>.jpg"/> 
			<h1><?= $s->titre;?></h1>
			<h5><?= $descrifinale;?></h5>
			<h5>Stock: <?= $s->stock;?></h5>
				<?php
			if ($s->stock!=0) {
					?>
					<a href="panier.php?action=ajout&amp;l=<?= $s->titre;?>&amp;q=<?= $s->stock;?>&amp;p=<?= $s->prix;?>">Ajouter au panier</a><br><br><br>
					<?php
			}else{
				echo "<h5 style='color:red'>Stock épuisé</h5>";
			}

			?>
</div>


			<?php
		}

else{

		if (isset($_GET['categorie'])) {
	$categorie = $_GET['categorie'];
	$select = $bdd -> prepare("SELECT * FROM produit WHERE categorie='$categorie'");
	$select -> execute();



	while ($s = $select->fetch(PDO::FETCH_OBJ)){
		$length =100;
		$description = $s->description;
		$newdescription = substr($description,0,$length)."...";

		$descriptionfinale = wordwrap($newdescription,55,'</br>',false);
		?>
		<br><br><a href="?show=<?= $s->titre;?>"><img src="admin/img/<?= $s->titre; ?>.jpg"/></a>
		<a href="?show=<?= $s->titre;?>"><h2><?= $s->titre; ?></h2></a>
		<h5><?= $descriptionfinale; ?></h5>
		<h4><?= $s->prix;?> Ariary<h4>
		<h5>Stock: <?= $s->stock;?></h5>

			<?php
			if ($s->stock!=0) {
					?>
					<a href="panier.php?action=ajout&amp;l=<?= $s->titre;?>&amp;q=1&amp;p=<?= $s->prix;?>">Ajouter au panier</a><br><br><br>
					<?php
			}else{
				echo "<h5 style='color:red'>Stock épuisé</h5>";
			}

			?>
		
		<?php
	}
		}else{
	$select = $bdd ->query('SELECT * FROM categorie');

	while ($data=$select->fetch(PDO::FETCH_OBJ) ) {
		
		?>
			<a href="?categorie=<?= $data->name;?>"><h3><?= $data->name;?></h3> </a>
		<?php
		
		}
	}

	}
	 require_once('includes/footer.php');