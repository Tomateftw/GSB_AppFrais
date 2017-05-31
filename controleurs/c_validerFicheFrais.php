<?php

include("vues/v_sommaireC.php");

$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date("d/m/Y"));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = $_REQUEST['action'];

switch ($action) {
    case 'choisirVisiteur': {
            $lesVisiteurs = $pdo->getLesVisiteurs();
            $leMois = isset($_SESSION['lstMois']) ? $_SESSION['lstMois'] : null; // si c'est faux mettre a nul
            $lesClesV = array_keys($lesVisiteurs);
            $visiteurASelectionner = $lesClesV[0];
            $lastSixMonth = getLesSixDerniersMois();
            include("vues/v_listeVisiteur.php");
            break;
        }

    case 'fiche': {

            $lesVisiteurs = $pdo->getLesVisiteurs();
            $lesClesV = array_keys($lesVisiteurs);
            $visiteurASelectionner = $lesClesV[0];
            $lastSixMonth = getLesSixDerniersMois();
            $idVisiteur = isset($_REQUEST['lstVisiteurs']) ? $_REQUEST['lstVisiteurs'] : null;
            $leMois = isset($_REQUEST['lstMois']) ? $_REQUEST['lstMois'] : null;
            if ($idVisiteur && $leMois) {
                $_SESSION['idVisiteur'] = $idVisiteur;
                $_SESSION['lstMois'] = $leMois;
                $idVisiteur = $_SESSION['idVisiteur'];
                $leMois = $_SESSION['lstMois'];
            }
            $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
            include("vues/v_listeVisiteur.php");
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
            $numAnnee = substr($leMois, 0, 4);
            $numMois = substr($leMois, 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);
            include("vues/v_listeFiche.php");
            break;
        }

    case 'modification': {
            $leMois = isset($_SESSION['lstMois']) ? $_SESSION['lstMois'] : null;
            $lesFrais = $_REQUEST['lesFrais'];
            $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
            break;
        }

    case 'supprimer': {
            $id = $_REQUEST['id'];
            $pdo->refuserfrais($id);
            header('Location: index.php?uc=validerFicheFrais&action=fiche');
            break;
        }

    case 'reporter': {
            $id = $_REQUEST['id'];
            $MoisPlus = getMoisNext($numAnnee, substr($_SESSION['lstMois'], 4, 2)); // appel de la fonction qui ajoute 1 au mois
            // $ficheExiste = $pdo->estPremierFraisMois($idVisiteur,$MoisPlus); // un visiteur possède une fiche de frais pour le mois passé en argument
            
            /* if ($pdo->estPremierFraisMois($idVisiteur, $MoisPlus)) {
              $pdo->getMoisSuivant($numAnnee, $MoisPlus, $id);
              } else { */
            $pdo->creeNouvellesLignesFrais($idVisiteur, $MoisPlus);
            $req = "UPDATE `lignefraisforfait` SET `mois`='" . $MoisPlus . "' WHERE `idVisiteur`='" . $idVisiteur . "' and `idFraisForfait`='" . $id . "'";
            //} 
            //header('Location: index.php?uc=validerfichefrais&action=fiche');
            break;
        }
//            
}
?>