<!DOCTYPE html>
<html>
<head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
            line-height: 1.6;
        }

        h2 {
            color: #4682b4;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
        }

        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #4682b4;
        }

        form input[type="text"], form select {
            width: calc(100% - 12px);
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        form input[type="text"]:focus, form select:focus {
            border-color: #4682b4;
            outline: none;
        }

        form button {
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

        form button:hover {
            background-color: #5a9bd4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4682b4;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</style>
</head>
<body>
<form action="gestionbook.php" method="post">
        <button type="submit">Retour Accueil</button>
    </form>
    <!-- Formulaire pour ajouter un nouveau livre -->
    <h2>Ajouter un Nouveau Livre</h2>
    <form action="" method="post">
        <label>Catégorie :</label>
        <select name="categorie" class="select-w-25">
            <option value="arabe1">Arabe</option>
            <option value="mathematique">Mathematique</option>
            <option value="physique">Physique</option>
            <option value="informatique">Informatique</option>
            <option value="svt">SVT</option>
            <option value="education_sciences_fr">علوم التربية الفرنسية</option>
            <option value="education_sciences_ar">علوم التربية العربية</option>
            <option value="history_geography">التاريخ و الجغرافيا</option>
            <option value="philosophie">الفلسفة</option>
        </select>
        <label>Code :</label>
        <input type="text" name="code" required>
        <label>Titre :</label>
        <input type="text" name="titre" required>
        <label>Auteur :</label>
        <input type="text" name="auteur" required>
        <label>Publicité :</label>
        <input type="text" name="publicite" required>
        <label>Année :</label>
        <input type="text" name="annee" required>
        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <?php
    // Vérifie si le formulaire d'ajout a été soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter'])) {
        // Inclut le fichier de connexion à la base de données
        include 'conx.php';

        // Validate and sanitize inputs
        $categorie = filter_input(INPUT_POST, 'categorie', FILTER_SANITIZE_STRING);
        $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);
        $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
        $auteur = filter_input(INPUT_POST, 'auteur', FILTER_SANITIZE_STRING);
        $publicite = filter_input(INPUT_POST, 'publicite', FILTER_SANITIZE_STRING);
        $annee = filter_input(INPUT_POST, 'annee', FILTER_SANITIZE_STRING);

        // Prépare la requête SQL pour insérer un nouveau livre
        $sql = "INSERT INTO `" . $categorie . "` (`a`, `b`, `c`, `d`, `e`) VALUES ('$code', '$titre', '$auteur', '$publicite', '$annee')";

        // Exécute la requête SQL
        if (mysqli_query($conn, $sql)) {
            echo "Livre ajouté avec succès.";
        } else {
            echo "Erreur : " . mysqli_error($conn);
        }

        // Ferme la connexion à la base de données
        mysqli_close($conn);
    }
    ?>

    <!-- Le reste du code pour afficher et gérer la recherche et l'affichage des livres reste inchangé -->
</body>
</html>
