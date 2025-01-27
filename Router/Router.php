<?php
require_once 'controller/TacheController.php';

class Router
{
    public function route()
    {
        //Récupérer l'action demandée depuis l'URL
        $action = isset($_GET['action']) ? $_GET['action'] : 'afficher';

        $controller = new TacheController();
 
        switch ($action) {
            case 'afficher':
                $controller->afficherTaches();
                break;
            case 'creer':
                $controller->creerTache();
                break;
            case 'modifier':
                $controller->modifierTache();
                break;
            case'verifierDoublon':
                $controller->verifierDoublonAjax();
                break;
            case'terminerTache':
                $controller->terminerTache();
                break;

            default:
            echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
            break;
        }



    }
}
?>