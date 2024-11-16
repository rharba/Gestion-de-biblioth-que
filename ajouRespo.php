<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire pour Ajouter un Responsable</title>
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

<h2>Formulaire pour Ajouter un Responsable</h2>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conx.php';

    $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '';
    $specialite = isset($_POST['specialite']) ? htmlspecialchars($_POST['specialite']) : '';
    $groupe = isset($_POST['groupe']) ? htmlspecialchars($_POST['groupe']) : '';

    if ($nom && $prenom && $specialite && $groupe) {
        $stmt = $conn->prepare('INSERT INTO responsable (nom, prenom, specialite, groupe) VALUES (?, ?, ?, ?)');
        if ($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
        }

        $bind = $stmt->bind_param('ssss', $nom, $prenom, $specialite, $groupe);
        if ($bind === false) {
            die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        }

        $exec = $stmt->execute();
        if ($exec) {
            header('Location: gestionbook.php');
            exit;
        } else {
            echo "<p style='color: red; text-align: center;'>Erreur lors de l'insertion : " . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<p style='color: red; text-align: center;'>Tous les champs sont obligatoires.</p>";
    }
}
?>

<!-- HTML FORM -->

<form method="post" action="">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required><br>

    <label for="specialite">Spécialité :</label>
    <input type="text" id="specialite" name="specialite" required><br>

    <label for="groupe">Groupe :</label>
    <input type="text" id="groupe" name="groupe" required><br>

    <button type="submit">Ajouter Responsable</button>
</form>

</body>
</html>
