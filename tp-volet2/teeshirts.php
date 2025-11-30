<?php
$page = "teeshirts";
include("commun/entete.inc.php");
include("lib/catalogue.lib.php");

// Intégrer le fichier JSON contenant les produits
$catalogue = json_decode(file_get_contents("data/teeshirts.json"));

// On crée deux tableaux pour stocker les thèmes et les produits
$themes = [];
$compteProduits = []; // Tableau pour compter le nombre de produits par thème
$produits = [];
// Récupérer le filtre de thème depuis les paramètres GET
$filtre = isset($_GET['filtre']) ? $_GET['filtre'] : 'tous';

// On parcourt le catalogue pour sortir les thèmes et fusionner les produits
foreach ($catalogue as $codeTheme => $detailTheme) {
   // Nom du thème dans la bonne langue (ou en français si absent)
    $themes[$codeTheme] = isset($detailTheme->theme->$langue)
        ? $detailTheme->theme->$langue
        : ($detailTheme->theme->fr ?? "Nom de thème indisponible");

    // Compter le nombre de produits dans ce thème
    $compteProduits[$codeTheme] = count($detailTheme->produits);

    // Ajouter les produits seulement si le thème correspond ou si "tous" est sélectionné
    if ($filtre === 'tous' || $filtre === $codeTheme) {
        $produits = array_merge($produits, $detailTheme->produits);
    }
}

// Calculer le nombre total de produits pour l'option "Tous les produits"
$totalProduits = array_sum($compteProduits);

// Le tri des produits est fait à  la fin, après le filtrage.
$tri = obtenirCritereTri();
$produits = trierProduits($produits, $tri);
?>
<main class="page-produits page-teeshirts">
    <article class="amorce">
        <h1><?= $_->titrePage; ?></h1>
        <form class="controle" action="">
            <div class="filtre">
                <label for="filtre"><?= $_cat->etiquetteFiltre; ?></label>
                <select name="filtre" id="filtre">
                    <!-- Option par défaut -->
                    <option value="tous" <?= ($filtre === 'tous') ? 'selected' : ''; ?>><?= $_cat->tousLesProduits; ?> (<?= $totalProduits; ?>)</option>
                    <!-- Générer les options des thèmes dynamiquement -->
                    <?php foreach ($themes as $codeTheme => $nomTheme) : ?>
                        <option value="<?= $codeTheme; ?>" <?= ($filtre == $codeTheme) ? 'selected' : ''; ?>><?= $nomTheme; ?> (<?= $compteProduits[$codeTheme]; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="tri">
                <label for="tri"><?= $_cat->etiquetteTri; ?></label>
                <select name="tri" id="tri">
                    <option <?= ($tri=="aleatoire") ? "selected" : ""; ?> value="aleatoire"><?= $_cat->aleatoire; ?></option>
                    <option <?= ($tri=="ventesDesc") ? "selected" : ""; ?> value="ventesDesc"><?= $_cat->ventesDesc; ?></option>
                    <option <?= ($tri=="prixAsc") ? "selected" : ""; ?> value="prixAsc"><?= $_cat->prixAsc; ?></option>
                    <option <?= ($tri=="prixDesc") ? "selected" : ""; ?> value="prixDesc"><?= $_cat->prixDesc; ?></option>
                    <!-- Pour les besoins du TP, nous ne gérons pas le tri par nom et par date d'ajout -->
                    <!--
                        <option <?= ($tri=="nomAsc") ? "selected" : ""; ?> value="nomAsc"><?= $_cat->nomAsc; ?></option>
                        <option <?= ($tri=="nomDesc") ? "selected" : ""; ?> value="nomDesc"><?= $_cat->nomDesc; ?></option>
                        <option <?= ($tri=="dacDesc") ? "selected" : ""; ?> value="dacDesc"><?= $_cat->dacDesc; ?></option>
                    -->
                </select>
            </div>
        </form>
    </article>
    <article class="principal">
        <!-- Gabarit -->
        <?php foreach ($produits as $prd) : ?>
            <div class="produit">
                <span class="image">
                    <?php
                        /* Affichage de l'image du produit. Si l'image n'existe pas, afficher une image par défaut. Le nom du fichier image est basé sur l'ID du produit. */
                        $cheminImage = "images/produits/teeshirts/{$prd->id}.webp";
                        if (!file_exists($cheminImage)) {
                        $cheminImage = "images/produits/teeshirts/ts0000.webp";
                        }
                        // Nom du produit dans la langue courante (ou en français si absent)
                        $nomProduit = isset($prd->nom->$langue) ? $prd->nom->$langue : ($prd->nom->fr ?? "Nom non disponible");
                    ?>
                    <img src="<?= $cheminImage; ?>" alt="<?= $nomProduit; ?>" title="<?= $nomProduit; ?>">
                    <?php if ($prd->ventes > 0): ?>
                        <!-- Affichage du nombre de ventes -->
                        <span class="ventes"><?= $prd->ventes; ?></span>
                    <?php else: ?>
                        <span class="ventes aucunes">Aucune vente</span>
                    <?php endif; ?>
                </span>
                <span class="nom"><?= $nomProduit; ?></span>
                <span class="prix"><?= number_format($prd->prix, 2, ',', ' '); ?> $</span>
            </div>
        <?php endforeach; ?>
    </article>
</main>

<!-- Gabarit à utiliser pour implémenter l'affichage asynchrone des produits -->
<template id="gabarit-produit">
	<div class="produit">
		<span class="image">
			<img
				src="images/produits/teeshirts/ts0000.webp"
				alt="Nom du produit"
			>
			<span class="ventes"></span>
		</span>
		<span class="nom"></span>
		<span class="prix">
			<span class="montant"></span>
		</span>
	</div>
</template>
<?php
include("commun/pied2page.inc.php");
?>