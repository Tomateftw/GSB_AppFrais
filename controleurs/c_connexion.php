<?php
if(!isset($_REQUEST['action']))
{
    $_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch($action)
{
    case 'demandeConnexion':
    {
        include("vues/v_connexion.php");
        break;
    }
    case 'valideConnexion':
    {
        $login = $_REQUEST['login'];
        $mdp = $_REQUEST['mdp'];
        $visiteur = $pdo->getInfosVisiteur($login,$mdp);
        
        $login_c = $_REQUEST['login'];
        $mdp_c = $_REQUEST['mdp'];
        $comptable = $pdo->getInfosComptable($login_c,$mdp_c);

        if(!is_array($visiteur) AND !is_array($comptable))
        {
            ajouterErreur("Login ou mot de passe incorrect");
            include("vues/v_erreurs.php");
            include("vues/v_connexion.php");
        }
        else
        {
            if($id = $visiteur['id'])
            {
                $nom =  $visiteur['nom'];
                $prenom = $visiteur['prenom'];
                connecter($id,$nom,$prenom);
                include("vues/v_sommaire.php");
            }
            elseif($id_c = $comptable['id'])
            {
                $nom_c =  $comptable['nom'];
                $prenom_c = $comptable['prenom'];
                connecterC($id_c,$nom_c,$prenom_c);
                include("vues/v_sommaireC.php");    
            }
        }
        break;
    }

    default :{
            include("vues/v_connexion.php");
            break;
    }
}
?>