<?php
require_once 'modele/Tache.php';

class TacheController
{

    ////////////////////// AFFICHER UNE TÂCHE/////////////////////////
    public function afficherTaches()
    {
        $taches = Tache::obtenirTaches();

        //Diviser les tâches entre aujourdh'ui et futur

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

        require_once 'vues/todo.php';
    }

    ////////////////////// CRÉER UNE TÂCHE/////////////////////////
    public function creerTache()
    {
        $message = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $date = $_POST['assigned_date'] ?? date('Y-m-d');



            //Vérification de la date (empecher les dates antérieures)
            $aujourdhui = date('Y-m-d');
            if ($date < $aujourdhui) {
                $message = "Impossible de créer une tâche à une date passée";
            } else {
                // Vérifie les doublons
                if (Tache::verifierDoublon($titre, $date)) {
                    $message = "Une tâche avec le même titre et la même date existe déjà.";
                } else {
                    // Si pas de doublon, on essaie de créer la tâche
                    $result = Tache::creerTache($titre, $description, $date);
                    if ($result) {
                        //Rediriger après la création réussi
                        header('Location: index.php?action=afficher');
                        exit;
                    } else {
                        $message = "Erreur lors de la création de la tâche.";
                    }
                }
            }

        }

        // Récupérer toutes les tâches après la création
        $taches = Tache::obtenirTaches();

        // Diviser les tâches entre aujourd'hui et futur
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

////////////////////// VÉRIFIER LES DOUBLONS D'UNE TÂCHE/////////////////////////
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


////////////////////// TERMINER UNE TÂCHE/////////////////////////
public function terminerTache()
{
    // Vérifie si la requête est bien en POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupère les données envoyées en JSON
        $data = json_decode(file_get_contents('php://input'), true);

        // Vérifie si un ID de tâche est fourni
        if (isset($data['id'])) {
            $id = $data['id'];
            $dateTerminee = date('Y-m-d'); // Date du jour

            // Met à jour la tâche dans la base de données
            $result = Tache::terminerTache($id, $dateTerminee);

            // Renvoie une réponse JSON au client
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Tâche marquée comme terminée']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de la tâche']);
            }
        } else {
            // Pas d'ID fourni
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID de la tâche manquant']);
        }
    } else {
        // Si la méthode HTTP n'est pas POST
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    }
    exit;
}





////////////////////// MODIFIER UNE TÂCHE/////////////////////////
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