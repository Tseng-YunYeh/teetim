/******************************************************************************/
// Seulement une des deux sections suivantes doit être active à la fois !
/******************************************************************************/
/*
    Gestion du formulaire *** synchrone ***
*/
// document.querySelectorAll("form.controle select").forEach(
//     eltSelect => eltSelect.addEventListener("change",
//         evt => evt.target.form.submit()
//     )
// );

/*
    Gestion du formulaire *** asynchrone ***
*/
document.querySelectorAll("form.controle select").forEach(
    eltSelect => eltSelect.addEventListener("change",
        evt => {
            evt.preventDefault();
            gererProduitsAsynchrone(evt.target.form);
        }
    )
);
/******************************************************************************/
/******************************************************************************/
/******************************************************************************/

/**
 * Gérer le tri en mode asynchrone.
 *
 * @param {HTMLFormElement} frm Formulaire contenant les critères de tri.
 * @returns {Promise<void>} Promesse résolue une fois l'affichage mis à jour.
 */
async function gererProduitsAsynchrone(frm) {
  // Envoyer une requête HTTP, attendre la réponse HTTP
  let reponseFiltreEtTri = await fetch("async/teeshirts.async.php?" 
                + new URLSearchParams(new FormData(frm)).toString());
  // Récupérer le contenu JSON de la réponse HTTP
  let contenu = await reponseFiltreEtTri.json();
  // Appeler une fonction qui gère la mise à jour de l'affichage
  afficherProduits(contenu);
}

/**
 * Mettre à jour le DOM avec les produits passés en paramètre.
 *
 * @param {Array<Object>} produits Liste des produits à afficher.
 * @returns {void}
 */
function afficherProduits(produits) {
  // Récupérer la langue courante !
  let langue = document.querySelector("html").lang;

  // Saisir le conteneur des produits
  let conteneurProduits = document.querySelector("main.page-produits article.principal");
  // Le vider...
  conteneurProduits.innerHTML = "";

  // Saisir le gabarit de produit
  let gabaritProduit = document.getElementById("gabarit-produit").content;

  let eltPrd;
  for(let prd of produits) {
    // On obtient un clone de l'élément contenu dans le gabarit de produit
    eltPrd = gabaritProduit.cloneNode(true);

    // Formatage du prix avec 2 décimales et symbole $
    eltPrd.querySelector(".montant").innerHTML = prd.prix.toFixed(2).replace('.', ',') + " $";

    // Nom du produit dans la langue courante
    let nomProduit = prd.nom[langue] || prd.nom.fr || "Nom non disponible";
    eltPrd.querySelector(".nom").innerHTML = nomProduit;
    eltPrd.querySelector(".image img").alt = nomProduit;
    eltPrd.querySelector(".image img").title = nomProduit;

    // Gestion de l'image (avec image par défaut si nécessaire)
    eltPrd.querySelector(".image img").src = "images/produits/teeshirts/" + prd.id + ".webp";
    // Note: La gestion de l'image par défaut se fera via l'événement onerror
    eltPrd.querySelector(".image img").onerror = function() {
      this.src = "images/produits/teeshirts/ts0000.webp";
    };

    // Gestion de l'affichage des ventes
    let eltVentes = eltPrd.querySelector(".ventes");
    if (prd.ventes > 0) {
      eltVentes.textContent = prd.ventes;
      eltVentes.classList.remove("aucunes");
    } else {
      eltVentes.textContent = "Aucune vente";
      eltVentes.classList.add("aucunes");
    }

    // On insère cet élément dans le conteneur des produits
    conteneurProduits.append(eltPrd);
  }
}

/******************************************************************************/
// Gestion du panier d'achats
/******************************************************************************/

/**
 * Calculer et mettre à jour le total des items et le sous-total du panier.
 *
 * @returns {void}
 */
function mettreAJourPanier() {
  // Sélectionner tous les articles du panier
  const articles = document.querySelectorAll('.panier-article');

  let totalItems = 0;
  let sousTotal = 0;

  // Parcourir chaque article
  articles.forEach(article => {
    const inputQuantite = article.querySelector('.produit-quantite');
    const prixUnitaire = parseFloat(article.dataset.prixUnitaire);
    const quantite = parseInt(inputQuantite.value) || 0;

    // Calculer le prix total pour cet article
    const prixTotalArticle = prixUnitaire * quantite;

    // Mettre à jour l'affichage du prix de l'article
    const spanPrix = article.querySelector('.produit-prix');
    spanPrix.textContent = prixTotalArticle.toFixed(2).replace('.', ',') + ' $';

    // Ajouter au total
    totalItems += quantite;
    sousTotal += prixTotalArticle;
  });

  // Mettre à jour l'affichage du total des items
  const spanTotalItems = document.getElementById('total-items');
  if (spanTotalItems) {
    spanTotalItems.textContent = totalItems;
  }

  // Mettre à jour l'affichage du sous-total
  const spanSousTotal = document.getElementById('sous-total');
  if (spanSousTotal) {
    spanSousTotal.textContent = sousTotal.toFixed(2).replace('.', ',');
  }
}

/**
 * Supprimer un article du panier.
 *
 * @param {HTMLButtonElement} bouton Bouton ayant déclenché la suppression.
 * @returns {void}
 */
function supprimerArticle(bouton) {
  const article = bouton.closest('.panier-article');
  if (article) {
    article.remove();
    mettreAJourPanier();
  }
}

// Initialisation du panier au chargement de la page
if (document.querySelector('.page-panier')) {
  // Écouter les changements de quantité
  document.querySelectorAll('.produit-quantite').forEach(input => {
    input.addEventListener('change', mettreAJourPanier);
    input.addEventListener('input', mettreAJourPanier);
  });

  // Écouter les clics sur les boutons supprimer
  document.querySelectorAll('.btn-supprimer').forEach(bouton => {
    bouton.addEventListener('click', () => supprimerArticle(bouton));
  });

  // Calculer le total initial
  mettreAJourPanier();
}
