<?php

	require_once('includes/header.php');
	require_once('includes/sidebar.php');
	require_once('includes/functionPanier.php');

	$erreur = false;

	$action = (isset($_POST['action'])?$_POST['action']:isset($_GET['action'])?$_GET['action']:null);

	if ($action !==null) {
		if (!in_array($action, array('ajout','suppression','refresh'))) {
			
			$erreur =true;

			$l = (isset($_POST['l'])?$_POST['l']:isset($_GET['l'])?$_GET['l']:null);
			$q = (isset($_POST['q'])?$_POST['q']:isset($_GET['q'])?$_GET['q']:null);
			$p = (isset($_POST['p'])?$_POST['p']:isset($_GET['p'])?$_GET['p']:null);

			$l = preg_replace('#\v#','', $l);
			$p = floatval($p);

			if (is_array($q)) {
				
				$qteProduit = array();
				$i = 0;

				foreach ($q as $contenu) {
					
					$qteProduit[$i++] = intval($contenu);
				}
			}else{

				$q = intval($q);
			}
		}
	}

	if (!$erreur) {
		switch($action){

			case 'ajout':
				ajouterArticle($l,$q,$p);
				break;
			case 'suppression':
				supprimerArticle($l);
				break;
			case 'refresh':
				for ($i=0; $i <count($qteProduit) ; $i++) { 
					modifierQTE($_SESSION['panier']['libelleProduit'][$i], round($qteProduit));
				}
				break;
			Default:

			break;


		}
	}


?>

<form method="POST" action="">

		<table width="400">
			<tr>
		<td colspan="4">Votre Panier</td>

			</tr>
			<tr>
		<td>Libellé produit</td>
		<td>Prix unitaire</td>
		<td>Quantité</td>
		<td>TVA</td>
		<td>Action</td>
			</tr>
		<?php

if (isset($_GET['deletepanier'])&& $_GET['deletepanier'] == true) {
	
	supprimerPanier();
}
	if (creationPanier()) {
		
		$nbProduit = count($_SESSION['panier'] ['libelleProduit']);

		
			if ($nbProduit <= 0) {
				
				echo "</br><p style='font-size:20px; color:Red;'>oops... Panier vide !</p>";
			}else{
				for ($i=0; $i <$nbProduit ; $i++) { 
				?>
						<tr>
			<td><br> <?= $_SESSION['panier']['libelleProduit'][$i]; ?> </td>
			<td><br> <input name="q[]" value="<?= $_SESSION['panier']['qteProduit'][$i];?>" size="5"></td>
			<td><br> <?= $_SESSION['panier']['prixProduit'][$i]; ?></td>
			<td><br> <?php echo  $_SESSION['panier']['tva']; ?></td>				
			<td><br> <a href="panier.php?action=suppression&amp;l=<?php echo rawurlencode($_SESSION['panier']['libelleProduit'][$i]) ;?>">Supprimer</a><td>
						</tr>
				<?php
				}
				?>

						<tr>
			<td colspan="2">
				<br> <p>Total :<?= globalMontant(); ?></p><br>
					 <p>Total avec TVA :<?= globalMontantTVA(); ?></p>
			</td>
						</tr>

						<tr>
			<td colspan="4">
				<input type="submit" value="rafraichir">
				<input type="hidden" name="action" value="refresh">
				<a href="?deletepanier=true">Supprimer le panier</a>
			</td>
						</tr>
				<?php
				}


			}
		
		
		?>	
		</table>
	</form>
	<?php
	require_once('includes/footer.php');

?>