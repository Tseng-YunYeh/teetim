<?php
// Inclure la librairie de fonctions de comparaison (pour le tri des produits)
include("../lib/catalogue.lib.php");

// Gestion du catalogue
$catalogue = json_decode(file_get_contents("../data/teeshirts.json"));
// Tableau pour les produits
$produits = [];

// Récupérer le filtre de thème depuis les paramètres GET
$filtre = isset($_GET['filtre']) ? $_GET['filtre'] : 'tous';

// Parcourir le catalogue et filtrer selon le thème sélectionné
foreach ($catalogue as $codeTheme => $detailTheme) {
	// Ajouter les produits seulement si le thème correspond ou si "tous" est sélectionné
	if ($filtre === 'tous' || $filtre === $codeTheme) {
		$produits = array_merge($produits, $detailTheme->produits);
	}
}

// Gestion du tri.
$tri = obtenirCritereTri();
$produits = trierProduits($produits, $tri);

// Gestion de la réponse.
echo json_encode($produits);
?>