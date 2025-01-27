CREATE DATABASE IF NOT EXISTS todo_list;

USE todo_list;

-- Table 'tache'
CREATE TABLE IF NOT EXISTS tache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    assigned_date DATE,
    terminee TINYINT(1) DEFAULT 0, -- Ajout pour marquer une tâche comme terminée
    date_terminee DATE DEFAULT NULL -- Ajout pour enregistrer la date de fin
);

-- Commentaire :
-- `terminee` est un indicateur binaire pour savoir si une tâche est terminée.
-- `date_terminee` enregistre la date précise où la tâche a été marquée comme terminée.
