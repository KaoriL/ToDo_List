<?php
require_once 'modele/Tache.php';

class TacheController
{

    public function afficherTaches()
    {
        $taches = Tache::obtenirTaches();

        //Diviser les tâches entre aujourdh'ui et futur

        $aujourdhui = date('Y-m-d');
        $tachesaujourdhui = [];
        $tachesFutures = [];

        foreach ($taches as $tache){
            if($tache['assigned_date']=== $aujourdhui){
                $tachesaujourdhui[] = $tache;
            }else{
                $tachesFutures[] = $tache;
            }
        }

        require_once 'vues/todo.php';
    }

    public function creerTache()
{
    $message = null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $titre = $_POST['titre'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['assigned_date'] ?? date('Y-m-d');

        // Vérifie les doublons
        if (Tache::verifierDoublon($titre, $date)) {
            $message = "Une tâche avec le même titre et la même date existe déjà.";
        } else {
            // Si pas de doublon, on essaie de créer la tâche
            $result = Tache::creerTache($titre, $description, $date);

            if ($result) {
                $message = "Tâche créée avec succès !";
            } else {
                $message = "Erreur lors de la création de la tâche.";
            }
        }
    }

    // Récupérer toutes les tâches après la création
    $taches = Tache::obtenirTaches();

    // Diviser les tâches entre aujourd'hui et futur
    $aujourdhui = date('Y-m-d');
    $tachesaujourdhui = [];
    $tachesFutures = [];

    foreach ($taches as $tache) {
        if ($tache['assigned_date'] === $aujourdhui) {
            $tachesaujourdhui[] = $tache;
        } else {
            $tachesFutures[] = $tache;
        }
    }

    // Charger la vue avec les tâches
    require_once 'vues/todo.php';
}


public function verifierDoublonAjax()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = $_POST['titre'] ?? '';
        $date = $_POST['assigned_date'] ?? '';

        $existe = Tache::verifierDoublon($titre, $date);
        echo json_encode(['existe' => $existe]);
        exit;
    }
}


    public function modifierTache()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $titre = $_POST['titre'];
            $description = $_POST['description'];

            $result = Tache::modifierTache($id, $titre, $description);
            if ($result) {
                header('Location: index.php?action=afficher');
                exit;
            } else {
                echo "Erreur lors de la modification de la tâche.";
            }
        }
    }

}

?>