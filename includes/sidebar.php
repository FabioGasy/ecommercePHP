<div class="sidebar">
<h4>Derniers Articles</h4>

<?php 

	$select = $bdd -> prepare("SELECT * FROM produit ORDER BY id DESC LIMIT 0,2");
	$select -> execute();
	while ($s = $select->fetch(PDO::FETCH_OBJ)){
		$length =100;
		$description = $s->description;
		$newdescription = substr($description,0,$length)."...";
		?>
		<div style="text-align:center;">
			<img width="50" height="50" src="admin/img/<?= $s->titre; ?>.jpg"/>
		<h2 style="color:white;"><?= $s->titre; ?></h2>
		<h5 style="color:white;"><?= $newdescription; ?></h5>
		<h4 style="color:white;"><?= $s->prix;?></h4>
		</div>
	<?php
		}
	?>
</div>