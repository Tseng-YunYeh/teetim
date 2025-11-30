<?php
$page = "panier";
include("commun/entete.inc.php");
?>
<main class="page-panier">
    <article class="amorce">
        <h1><?= $_->titrePage; ?></h1>
    </article>
    <article class="principal">
        <div class="panier-conteneur">
            <!-- Article 1 -->
            <div class="panier-article" data-prix-unitaire="29.50">
                <img src="images/produits/teeshirts/ts0001.webp" alt="Monstre douillet">
                <span class="produit-nom">Monstre douillet</span>
                <span class="produit-couleur" style="background-color: #5BC0BE;"></span>
                <span class="produit-taille">M</span>
                <input type="number" class="produit-quantite" value="5" min="1" max="99">
                <span class="produit-prix">147,50 $</span>
                <button class="btn-supprimer" aria-label="<?= $_->supprimer; ?>">
                    <span class="material-icons">delete</span>
                </button>
            </div>

            <!-- Article 2 -->
            <div class="panier-article" data-prix-unitaire="19.99">
                <img src="images/produits/teeshirts/ts0002.webp" alt="Bleu comme une orange">
                <span class="produit-nom">Bleu comme une orange</span>
                <span class="produit-couleur" style="background-color: #FF85C0;"></span>
                <span class="produit-taille">XS</span>
                <input type="number" class="produit-quantite" value="2" min="1" max="99">
                <span class="produit-prix">39,98 $</span>
                <button class="btn-supprimer" aria-label="<?= $_->supprimer; ?>">
                    <span class="material-icons">delete</span>
                </button>
            </div>

            <!-- Ligne de séparation -->
            <hr class="panier-separateur">

            <!-- Résumé -->
            <div class="panier-resume">
                <div class="resume-info">
                    <span>(<?= $_->totalItems; ?>: <span id="total-items">7</span>)</span>
                    <span class="resume-sousTotal"><?= $_->subTotal; ?>: <span id="sous-total">187,48</span> $</span>
                </div>
                <button class="btn-completer"><?= $_->completerCommande; ?></button>
            </div>
        </div>
    </article>
</main>
<?php
include("commun/pied2page.inc.php");
?>
