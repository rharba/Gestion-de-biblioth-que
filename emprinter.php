<?php
// Vérifier si des valeurs ont été transmises via POST
$codeLivre = isset($_POST['code']) ? htmlspecialchars($_POST['code']) : '';
$nomLivre = isset($_POST['nomLivre']) ? htmlspecialchars($_POST['nomLivre']) : '';
$categorie = isset($_POST['categorie']) ? htmlspecialchars($_POST['categorie']) : '';
$nomCompletEleve = isset($_POST['nomCompletEleve']) ? htmlspecialchars($_POST['nomCompletEleve']) : '';
?> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'emprunt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            padding: 20px;
        }

        h2 {
            color: #4682b4;
            text-align: center;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #4682b4;
        }

        input[type="text"], input[type="date"], select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="date"]:focus, select:focus {
            border-color: #4682b4;
            outline: none;
        }

        button {
            width: 100%;
            background-color: #4682b4;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #5a9bd4;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<h2>Formulaire d'emprunt</h2>

<form id="empruntForm" method="post" action="save.php">
    <label for="codeLivre">Code du livre :</label>
    <input type="text" id="codeLivre" name="codeLivre" value="<?php echo $codeLivre; ?>" required readonly><br>

    <label for="nomLivre">Nom du livre :</label>
    <input type="text" id="nomLivre" name="nomLivre" value="<?php echo $nomLivre; ?>" required readonly><br>

    <label for="categorie">Catégorie :</label>
    <input type="text" id="categorie" name="categorie" value="<?php echo $categorie; ?>" required readonly><br>

    <label for="searchResponsable">Rechercher un responsable (par la première lettre):</label>
    <input type="text" id="searchResponsable" maxlength="1" placeholder="A">
    <select id="responsableSelect">
        <option value="">Sélectionnez un responsable</option>
    </select><br>

    <label for="nomComplete">Rechercher un élève (par le nom complet):</label>
    <input type="text" id="nomComplete" name="nomComplete" placeholder="Entrez le nom complet de l'élève">
    <select id="eleveSelect">
        <option value="">Sélectionnez un élève</option>
    </select>
    <span id="nomCompletEleve" name="nomCompletEleve"></span>
    <label for="nomCompletEleve"></label>
    <br>

    <label for="telephone">Téléphone :</label>
    <input type="text" id="telephone" name="telephone" readonly required><br>

    <label for="specialite">Spécialité :</label>
    <input type="text" id="specialite" name="specialite" readonly required><br>

    <label for="groupe">Groupe :</label>
    <input type="text" id="groupe" name="groupe" readonly required><br>

    <label for="dateEmprunt">Date d'emprunt :</label>
    <input type="date" id="dateEmprunt" name="dateEmprunt" required><br>

    <label for="dateFinEmprunt">Date de fin d'emprunt :</label>
    <input type="date" id="dateFinEmprunt" name="dateFinEmprunt" readonly><br>

    <!-- Champ caché pour l'id de l'élève -->
    <input type="hidden" id="idEleve" name="idEleve">

    <button type="submit">Soumettre</button>
</form>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('searchResponsable').addEventListener('input', remplirResponsables);
    document.getElementById('responsableSelect').addEventListener('change', remplirEleves);
    document.getElementById('nomComplete').addEventListener('input', remplirEleves);
    document.getElementById('eleveSelect').addEventListener('change', afficherDetailsEleve);
    document.getElementById('dateEmprunt').addEventListener('change', calculateDateFinEmprunt);

    function remplirResponsables() {
        var lettre = document.getElementById('searchResponsable').value;

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_responsables.php?lettre=" + lettre, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var responsables = JSON.parse(xhr.responseText);
                    var select = document.getElementById('responsableSelect');
                    select.innerHTML = '<option value="">Sélectionnez un responsable</option>';
                    responsables.forEach(function(responsable) {
                        var option = document.createElement('option');
                        option.value = responsable.id;
                        option.textContent = responsable.nom + ' ' + responsable.prenom;
                        select.appendChild(option);
                    });
                } catch (e) {
                    console.error("Error parsing JSON:", e);
                    console.error("Response:", xhr.responseText);
                }
            }
        };
        xhr.send();
    }

    function remplirEleves() {
        var responsableId = document.getElementById('responsableSelect').value;
        var nomComplet = document.getElementById('nomComplete').value;

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_eleves.php?responsableId=" + responsableId + "&nom=" + encodeURIComponent(nomComplet), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var eleves = JSON.parse(xhr.responseText);
                    var select = document.getElementById('eleveSelect');
                    select.innerHTML = '<option value="">Sélectionnez un élève</option>';
                    eleves.forEach(function(eleve) {
                        var option = document.createElement('option');
                        option.value = eleve.id;
                        option.textContent = eleve.nom + ' ' + eleve.prenom;
                        select.appendChild(option);
                    });
                } catch (e) {
                    console.error("Error parsing JSON:", e);
                    console.error("Response:", xhr.responseText);
                }
            }
        };
        xhr.send();
    }

    function afficherDetailsEleve() {
        var eleveId = document.getElementById('eleveSelect').value;

        if (eleveId) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_eleve_details.php?eleveId=" + eleveId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var details = JSON.parse(xhr.responseText);

                        if (details.error) {
                            console.error("Error:", details.error);
                        } else {
                            document.getElementById('telephone').value = details.numero_telephone || '';
                            document.getElementById('specialite').value = details.specialite || '';
                            document.getElementById('groupe').value = details.groupe || '';
                            document.getElementById('nomCompletEleve').textContent = details.nom + ' ' + details.prenom;
                            document.getElementById('idEleve').value = eleveId; // Mise à jour de l'input caché
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", e);
                        console.error("Response:", xhr.responseText);
                    }
                } else {
                    console.error("Error fetching student details.");
                }
            };
            xhr.onerror = function() {
                console.error("Request error...");
            };
            xhr.send();
        }
    }

    function calculateDateFinEmprunt() {
        var dateEmprunt = new Date(document.getElementById('dateEmprunt').value);
        if (!isNaN(dateEmprunt)) {
            var dateFinEmprunt = new Date(dateEmprunt);
            dateFinEmprunt.setDate(dateEmprunt.getDate() + 7);
            var dd = String(dateFinEmprunt.getDate()).padStart(2, '0');
            var mm = String(dateFinEmprunt.getMonth() + 1).padStart(2, '0');
            var yyyy = dateFinEmprunt.getFullYear();

            dateFinEmprunt = yyyy + '-' + mm + '-' + dd;
            document.getElementById('dateFinEmprunt').value = dateFinEmprunt;
        }
    }

    remplirResponsables();
    remplirEleves();
});
</script>

</body>
</html>

