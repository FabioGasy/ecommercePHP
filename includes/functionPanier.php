<?php

	
	function creationPanier(){
		try{
 $bdd = new PDO('mysql:host=localhost;dbname=sitecommerce', 'root', '');
 $bdd -> setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
 $bdd ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
catch(Exception $e){
        echo "une erreur est survenue";
        die('Erreur : '.$e->getMessage());
      } 

		if (!isset($_SESSION['panier'])) {
			
			$_SESSION['panier']         		   = array();
			$_SESSION['panier'] ['libelleProduit'] = array();
			$_SESSION['panier'] ['qteProduit']     = array();
			$_SESSION['panier'] ['prixProduit']    = array();
			$_SESSION['panier'] ['verrou']         = false;



				$select = $bdd -> query("SELECT tva FROM produit");
				$tva    = $select -> fetch(PDO::FETCH_OBJ);

				$_SESSION['panier']['tva'] = $data ->tva;


		}
		return true;
	}

	function ajouterArticle($libelleProduit,$qteProduit,$prixProduit){

		if (creationPanier() && !isVerouille()) {
			$positionProduit = array_search($libelleProduit, $_SESSION['panier']['libelleProduit']);

				if ($positionProduit !==false) {
					$_SESSION['panier'] ['libelleProduit'] [$positionProduit] +=$qteProduit;
					
				}else{

					array_push($_SESSION['panier']['libelleProduit'], $libelleProduit);
					array_push($_SESSION['panier']['qteProduit'], $qteProduit);
					array_push($_SESSION['panier']['prixProduit'], $prixProduit);
				}
		}else{

			echo "Erreur, veuillez contactez l\'administrateur du site";
		}

	}

	function modifierQTE($libelleProduit,$qteProduit){

		if (creationPanier() && !isVerouille()) {
				if ($qteProduit >0 ) {
					
					$positionProduit = array_search($_SESSION['panier']['libelleProduit'], $libelleProduit);

							if ($positionProduit !== false) {
								
								$_SESSION['panier']['libelleProduit'][$positionProduit] = $qteProduit;
							}
				}else{

					supprimerArticle($libelleProduit);
				}
		}else{

			echo "Erreur, veuillez contactez l\'administrateur du site";
		}


	}

	function supprimerArticle(){

		if (creationPanier() && !isVerouille()) {
			
			$tmp                   = array();
			$tmp['libelleProduit'] = array();
			$tmp['qteProduit']     = array();
			$tmp['prixProduit']    = array();
			$tmp['verrou']         = array();

				for ($i=0; $i<count($_SESSION['panier']['libelleProduit']) ; $i++) { 
						if ($_SESSION['panier']['libelleProduit'][$i] !== $libelleProduit) {
				
					array_push($_SESSION['panier']['libelleProduit'], $SESSION['panier']['libelleProduit'][$i]);
					array_push($_SESSION['panier']['qteProduit'],  $SESSION['panier']['qteProduit'][$i]);
					array_push($_SESSION['panier']['prixProduit'],  $SESSION['panier']['prixProduit'][$i]);
						}
				}

				$_SESSION['panier'] = $tmp;
				unset($tmp);
		}else{

			echo "Erreur, veuillez contactez l\'administrateur du site";
		}
	}

		function globalMontant(){

			$total = 0;
			for ($i=0; $i<count($_SESSION['panier']['libelleProduit']) ; $i++){

				$total += $_SESSION['panier']['qteProduit'][$i] * $_SESSION['panier']['prixProduit'][$i];
			}

			return $total;

		}
		function globalMontantTVA(){

			$total = 0;
			for ($i=0; $i<count($_SESSION['panier']['libelleProduit']) ; $i++){

				$total += $_SESSION['panier']['qteProduit'][$i] * $_SESSION['panier']['prixProduit'][$i];
			}

			return $total + $total * $_SESSION['panier']['tva'] /100;
		}

		function supprimerPanier(){

			if (isset($_SESSION['panier'])) {
				
				unset($_SESSION['panier']);
			}


		}


		function isVerouille(){

		if (isset($_SESSION['panier'])&&$_SESSION['panier']['verrou']) {

			return true;
			
		}else{
			return false;
		}
	}


	function compterArticles(){

		if (isset($_SESSION['panier'])) {
			
			return count($_SESSION['panier']['libelleProduit']);
		
	}else{

		return 0;
	}
}


	?>