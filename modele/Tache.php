<?php
require_once 'config/config.php';

class Tache
{
    public $id;
    public $titre;
    public $description;
    public $status;


    /**
     * Summary of obtenirTaches
     * @return array
     */
    public static function obtenirTaches()
    {
        $pdo = getConnexion(); //Connexion à la base de données

        $sql = "SELECT * FROM tache"; //Récupérer les tâches

        $query = $pdo->query($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC); //Retourne les données sous forme de tableau assosiatif
    }

    //Vérifier les doublons
    public static function verifierDoublon($titre, $date)
    {
        $pdo = getConnexion(); //Connexion à la base de données

        $sql = "SELECT COUNT(*)as count FROM tache WHERE titre = :titre AND assigned_date = :assigned_date";

        $query = $pdo->prepare($sql);
        $query->bindParam(':titre', $titre);
        $query->bindParam(':assigned_date', $date);
        $query->execute();

        return $query->fetchColumn() > 0;
    }

    //Créer une tâche
    public static function creerTache($titre, $description, $date)
    {
        $pdo = getConnexion(); //Connexion à la base de données

        //Inserer la table si elle n'existe pas 
        $sql = "INSERT INTO tache (titre, description, status,created_at,updated_at, assigned_date) VALUES (:titre, :description, :status,NOW(),NOW(), :assigned_date)";
        $query = $pdo->prepare($sql);

        $query->bindParam(':titre', $titre);
        $query->bindParam(':description', $description);
        $status = 'pending';
        $query->bindParam(':status', $status);
        $query->bindParam(':assigned_date', $date); //Ajout de la date personalisé

        return $query->execute(); // Renvoie true si l'insertion réussit
    }



    public static function terminerTache($id, $dateTerminee)
    {
        try {
            $pdo = getConnexion(); //Connexion à la base de données

            $sql = "UPDATE tache SET terminee = 1, date_terminee = :date_terminee WHERE id = :id";

            $query = $pdo->prepare($sql);
            $query->bindParam(':date_terminee', $dateTerminee);
            $query->bindParam(':id', $id);
            
            if($query->execute()){
                return true;
            } else{
                error_log('Echec de la mise à jour pour ID: '.$id);
                return false;
            }

        }catch (Exception $e){
            error_log('Erreur :'.$e->getMessage());
            return false;
        }




    }

    //Modifier une tâche
    public static function modifierTache($id, $titre, $description, $status)
    {
        $pdo = getConnexion(); //Connexion à la base de données

        $sql = "UPDATE tache SET titre = :titre, description = :description, status = :status, updated_at = NOW() WHERE id = :id";
        $query = $pdo->prepare($sql);

        $query->bindParam(':titre', $titre);
        $query->bindParam(':description', $description);
        $query->bindParam(':id', $id);
        $query->bindParam(':status', $status);

        return $query->execute();


    }

    //Supprimer une tache
    public static function supprimerTache($id)
    {
        $pdo = getConnexion(); //Connexion à la base de données

        $sql = "DELETE FROM tache WHERE id = :id";
        $query = $pdo->prepare($sql);
        $query->bindParam(':id', $id);
        return $query->execute();
    }


}
?>