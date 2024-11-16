<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire pour Ajouter un Élève</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        h2 {
            text-align: center;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        select,
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Formulaire pour Ajouter un Élève</h2>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conx.php'; // Assurez-vous que ce fichier contient le code de connexion à votre base de données

    $nomEleve = isset($_POST['nom_eleve']) ? htmlspecialchars($_POST['nom_eleve']) : '';
    $prenomEleve = isset($_POST['prenom_eleve']) ? htmlspecialchars($_POST['prenom_eleve']) : '';
    $specialiteEleve = isset($_POST['specialite_eleve']) ? htmlspecialchars($_POST['specialite_eleve']) : '';
    $groupeEleve = isset($_POST['groupe_eleve']) ? htmlspecialchars($_POST['groupe_eleve']) : '';
    $responsableId = isset($_POST['responsable_id']) ? htmlspecialchars($_POST['responsable_id']) : '';
    $numeroTelephone = isset($_POST['numero_telephone']) ? htmlspecialchars($_POST['numero_telephone']) : '';

    if ($nomEleve && $prenomEleve && $specialiteEleve && $groupeEleve && $responsableId && $numeroTelephone) {
        // Préparer la requête d'insertion
        $stmt = $conn->prepare('INSERT INTO eleve (nom, prenom, specialite, groupe, responsable_id, numero_telephone) VALUES (?, ?, ?, ?, ?, ?)');
        if ($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
        }

        $bind = $stmt->bind_param('ssssss', $nomEleve, $prenomEleve, $specialiteEleve, $groupeEleve, $responsableId, $numeroTelephone);
        if ($bind === false) {
            die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        }

        $exec = $stmt->execute();
        if ($exec) {
            echo "<p style='color: green; text-align: center;'>Élève ajouté avec succès.</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>Erreur lors de l'insertion : " . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<p style='color: red; text-align: center;'>Tous les champs sont obligatoires.</p>";
    }
}

// Récupérer les responsables pour le combobox
$responsables = [];
require 'conx.php';
$result = $conn->query('SELECT id, nom, prenom FROM responsable');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $responsables[] = $row;
    }
}
$conn->close();
?>

<form method="post" action="">
    <label for="nom_eleve">Nom :</label>
    <input type="text" id="nom_eleve" name="nom_eleve" required><br>

    <label for="prenom_eleve">Prénom :</label>
    <input type="text" id="prenom_eleve" name="prenom_eleve" required><br>

    <label for="specialite_eleve">Spécialité :</label>
    <input type="text" id="specialite_eleve" name="specialite_eleve" required><br>

    <label for="groupe _eleve">Groupe :</label>
    <input type="text" id="groupe_eleve" name="groupe_eleve" required><br>

    <label for="responsable_id">Responsable :</label>
    <select id="responsable_id" name="responsable_id" required>
        <option value="">Sélectionnez un responsable</option>
        <?php foreach ($responsables as $responsable): ?>
            <option value="<?php echo $responsable['id']; ?>">
                <?php echo $responsable['nom'] . ' ' . $responsable['prenom']; ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label for="numero_telephone">Numéro de téléphone :</label>
    <input type="text" id="numero_telephone" name="numero_telephone" required><br>

    <button type="submit" name="ajouter_eleve">Ajouter Élève</button>
</form>

</body>
</html>

