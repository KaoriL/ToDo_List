<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css?v=1.7">
</head>

<body>
    <section>

        <!------------------------------------- Ajouter une tâche -------------------------------->
        <div class="add">
            <div class="add-todo">
                <h2 id="jour"></h2>
                <p id="date"></p>
            </div>
            <button class="add-Todolist" onclick="openPopup()">+</button>
        </div>


        <!------------------------------------- Tache du jour -------------------------------->
        <div class="Todo-day">
            <h2>Aujourd'hui</h2>
            <h4 class="no-tasks-message" style="display: none;">Il n'y a aucune tâche pour aujourd'hui.</h4>
            <button class="toggle-btn">Afficher les tâches</button>
            <ul class="task-list">
                <!--Affichage des tâches depuis la base de données-->
                <?php if (!empty($tachesaujourdhui)): ?>
                    <?php foreach ($tachesaujourdhui as $tache): ?>
                        <li>
                            <input class="checkbox-terminer" type="checkbox" data-id="<?= $tache['id'] ?>"
                                <?= $tache['date_terminee'] ? 'checked disabled' : '' ?>>
                            <?= htmlspecialchars($tache['titre']) ?>
                            <!--<a href="/modifier/<?= $tache['id'] ?>">Modifier</a>-->
                            <!--<a href="/supprimer/<?= $tache['id'] ?>">Supprimer</a>-->
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <!------------------------------------- Tache du futur -------------------------------->
        <div class="Todo-Futur">
            <h2>Futur</h2>
            <h4 class="no-tasks-message" style="display: <?= empty($tachesFutures) ? 'block' : 'none'; ?>">Aucune tâche
                prévue pour le futur.</h4>
            <button class="toggle-btn">Afficher les tâches</button>
            <ul class="task-list">
                <!-- Affichage des tâches pour le futur -->
                <?php if (!empty($tachesFutures)): ?>
                    <?php foreach ($tachesFutures as $tache): ?>
                        <li
                            onclick="ouvrirPopup(<?php echo $tache['id']; ?>, '<?php echo addslashes($tache['titre']); ?>', '<?php echo addslashes($tache['description']); ?>')">
                            <input type="checkbox" <?= $tache['status'] === 'completed' ? 'checked' : '' ?>>
                            <?= htmlspecialchars($tache['titre']) ?>
                            <span class="date"><?= date('d-m', strtotime($tache['assigned_date'])) ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <!------------------------------------- Tache terminée -------------------------------->

        <div>
            <h2>Tâches terminées aujourd'hui</h2>
            <?php if (!empty($tachesTermineesAujourdhui)): ?>
                <?php foreach ($tachesTermineesAujourdhui as $tache): ?>
                    <div class="tache terminee">
                        <label><?= htmlspecialchars($tache['titre']) ?></label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune tâche terminée aujourd'hui.</p>
            <?php endif; ?>
        </div>



    </section>
    <!---------------------------------------------- Pop up de création----------------------------------------------------------->

    <div id="popUp" class="popUp <?= !empty($message) ? 'open' : '' ?>">
        <div class="popUp-content">
            <h3>Créer une tâche</h3>

            <!--MESSAGE D'ERREUR -->
            <?php if (isset($message)): ?>
                <div class="error-message">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=creer" method="POST">

                <label for="creation-titre">Titre*</label>
                <input type="text" name="titre" id="creation-titre" placeholder="Entrez un titre" required>

                <label for="creation-description">Description</label>
                <textarea name="description" id="creation-description" rows="4"
                    placeholder="Descritpion de la tâche"></textarea>
                <label for="assigned_date">Date</label>
                <input type="date" name="assigned_date" id="assigned_date" value="<?= date('Y-m-d'); ?>"
                    min="<?= date('Y-m-d'); ?>">
                <div class="bouton">
                    <button class="input-ajout" type="submit">Ajouter</button>
                    <button class="close-popup" type="button" onclick="closePopup()">Annulez</button>
                </div>




            </form>
        </div>
    </div>

    </div>
    <!------------------------------------------ Pop-up de description ---------------------------------------------------------
<div id="popup-overlay" class="popup-overlay"></div>
<div id="popup" class="popup">
    <button class="close-popup" onclick="fermerPopup()">Fermer</button>
    <h3>Modifier la tâche</h3>
    <form id="form-modification" action="index.php?action=modifier" method="POST">
        <input type="hidden" name="id" id="popup-id">
        <label for="popup-titre">Titre</label>
        <input type="text" name="titre" id="popup-titre" required><br><br>
        <label for="popup-description">Description</label>
        <textarea name="description" id="popup-description" rows="4" required></textarea><br><br>
        <label for="popup-status">Status</label>
        <select name="status" id="popup-status" required>
            <option value="pending">En attente</option>
            <option value="completed">Terminée</option>
            <option value="in-progress">En cours</option>
        </select><br><br>
        <input type="submit" value="Sauvegarder">
    </form>
</div> -->

    <!-- ===================== SCRIPTS ===================== -->

    <script>


        //////Afficher la liste via le bouton
        document.addEventListener('DOMContentLoaded', () => {
            const taskList = document.querySelector('.task-list');
            const toggleBtn = document.querySelector('.toggle-btn');
            const task = document.querySelectorAll('.task-list li'); //Selectionner toutes les tâches
            const noTaskMessage = document.querySelector('.no-tasks-message'); //Selectionner l'élément pour le message

            //Vérifier si le nombre de tâches est supérieur à 2 
            if (task.length > 2) {
                toggleBtn.style.display = "inline-block";//Affiche le bouton
            } else {
                toggleBtn.style.display = "none";// Cache le bouton si mooin de 2 tâches
            }

            if (task.length === 0) {
                noTaskMessage.style.display = "block"; //Afficher le message aucune tâche

            } else {
                noTaskMessage.style.display = "none"//Ccaher le message si des tâches sont présentes
            }

            toggleBtn.addEventListener('click', () => {
                taskList.classList.toggle('open'); //Ajoiter ou supprimer la classe open

                if (taskList.classList.contains('open')) {
                    toggleBtn.textContent = "Masquer les tâches";
                } else {
                    toggleBtn.textContent = "Afficher les tâches";
                }




            });
        })


        ///////////////////////////// POP UP //////////////////////////////////////

        function openPopup() {
            document.getElementById("popUp").style.display = "flex";
        }

        function closePopup() {
            document.getElementById("popUp").style.display = "none";
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Vérifier si la popup doit rester ouverte
            const popUp = document.getElementById("popUp");
            if (popUp.classList.contains('open')) {
                popUp.style.display = "flex";
            }
        });




        /////////////////////////////// ENREGISTRER L'ÉTAT TERMINÉ /////////////////////////////////////////

        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.checkbox-terminer');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const tacheId = this.getAttribute('data-id');
                    const estTerminee = this.checked;

                    if (estTerminee) {
                        fetch('index.php?action=terminerTache', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: tacheId })
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Erreur réseau');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    alert(data.message); // Message de succès
                                    location.reload(); // Recharge la page pour actualiser
                                } else {
                                    alert(data.message || 'Erreur inconnue');
                                }
                            })
                            .catch(error => {
                                console.error('Erreur lors du fetch :', error);
                                alert('Une erreur est survenue.');
                            });
                    }
                });
            });
        });




        //////////////////////////////////// DATE ////////////////////////////////////////
        //Obtenir la date
        const today = new Date();
        //Formater le jour de la semaine
        const optionsJour = { weekday: 'long' };
        const jour = today.toLocaleDateString('fr-FR', optionsJour);

        //Formater la date
        const optionsDate = { day: 'numeric', month: 'long', year: 'numeric' };
        const date = today.toLocaleDateString('fr-FR', optionsDate);

        //Insrerer les valeurs dans les éléments html
        document.getElementById('jour').textContent = jour;
        document.getElementById('date').textContent = date;








    </script>

</body>

</html>