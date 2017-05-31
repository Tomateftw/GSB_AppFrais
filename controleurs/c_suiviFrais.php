<?php
include("vues/v_sommaireC.php");
$action = $_REQUEST['action'];
switch ($action) {
    case 'selectionnerFicheDeFrais': {
            $listeFiche = $pdo->ListeFicheValider();
            include("vues/v_listeFicheFrais.php");
            break;
        }

    case 'voirDetailFrais': {
            $laFiche = $_REQUEST['lstFiche']; // récupère la fiche à valider 
            $split = explode("/", $laFiche); // sépare la variable dès qu'il y a le caractère slash.
            $leMois = $split[0]; // prend la variable mois, création d'un tableau
            $leVisiteur = $split[1]; //sa prend la valeur visiteur , tableau
            $_SESSION['mois'] = $leMois;
            $_SESSION['id'] = $leVisiteur;
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
            $numAnnee = substr($leMois, 0, 4);
            $numMois = substr($leMois, 4, 2);

            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);
            include("vues/v_suiviFrais.php");
            break;
        }

    case 'rembourserFiche': {
            $pdo->majEtatFicheFrais($_SESSION['id'], $_SESSION['mois'], 'RB');
            header('Location: index.php?uc=suiviFrais&action=selectionnerFicheDeFrais');
            break; // change l'état de l'affiche en remboursé ; l'affiche était en va (validé) et sa la passe en remboursé (rb)
        }
}
?>