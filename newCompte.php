<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'conx.php';

    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $lastname = isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $sexe = isset($_POST['sexe']) ? htmlspecialchars($_POST['sexe']) : '';
    $filiere = isset($_POST['filiere']) ? htmlspecialchars($_POST['filiere']) : '';
    $role = isset($_POST['role']) ? htmlspecialchars($_POST['role']) : '';

    if ($name && $lastname && $password && $email && $sexe && $filiere && $role) {
        // Vérifier que le rôle est valide
        if ($role === 'responsable' || $role === 'etd') {
            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare('INSERT INTO utilisateur (name, lastname, password, email, sexe, filiere, role) VALUES (?, ?, ?, ?, ?, ?, ?)');
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($conn->error));
            }

            $bind = $stmt->bind_param('sssssss', $name, $lastname, $hashedPassword, $email, $sexe, $filiere, $role);
            if ($bind === false) {
                die('bind_param() failed: ' . htmlspecialchars($stmt->error));
            }

            $exec = $stmt->execute();
            if ($exec) {
                echo "<p style='color: green; text-align: center;'>Utilisateur ajouté avec succès.</p>";
                header('Location: login.php');
                exit;
            } else {
                echo "<p style='color: red; text-align: center;'>Erreur lors de l'insertion : " . htmlspecialchars($stmt->error) . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p style='color: red; text-align: center;'>Rôle invalide.</p>";
        }

        $conn->close();
    } else {
        echo "<p style='color: red; text-align: center;'>Tous les champs sont obligatoires.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un nouveau compte utilisateur</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-top: 20px;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="radio"] + label {
            display: inline-block;
            margin-right: 15px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            text-transform: uppercase;
        }

        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <h2>Créer un nouveau compte utilisateur</h2>
    <div class="form-container">
        <form action="" method="post">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required>

            <label for="lastname">Prénom :</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label>Sexe :</label>
            <input type="radio" id="sexe_m" name="sexe" value="M" required>
            <label for="sexe_m">Masculin</label>
            <input type="radio" id="sexe_f" name="sexe" value="F" required>
            <label for="sexe_f">Féminin</label>

            <label for="filiere">Filière :</label>
            <input type="text" id="filiere" name="filiere" required>

            <label for="role">Rôle :</label>
            <select id="role" name="role" required>
                <option value="responsable">Responsable</option>
                <option value="etd">Étudiant</option>
            </select>

            <button type="submit">Créer</button>
        </form>
    </div>
</body>
</html>
