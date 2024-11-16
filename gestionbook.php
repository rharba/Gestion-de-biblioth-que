
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Livres</title>
    <style>
        /* Reset de base */
        body, button, input, select {
            font-family: Arial, sans-serif;
        }

        /* Style général */
        body {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }

        form {
            margin-bottom: 20px;
        }

        /* Styles spécifiques aux boutons */
        button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 10px; /* Espacement entre les boutons */
        }

        /* Couleurs différentes pour chaque bouton */
        button[type="submit"] {
            background-color: #4CAF50; /* Vert */
            color: white;
        }

        button[type="submit"][formaction="gestionEmp.php"] {
            background-color: #008CBA; /* Bleu */
        }

        button[type="submit"][formaction="ajouRespo.php"] {
            background-color: #f44336; /* Rouge */
        }

        /* Style pour le formulaire principal */
        form#livresForm {
            margin-bottom: 40px;
        }

        /* Style pour les titres */
        h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <form action="newbook.php" method="post" style="margin-bottom: 20px;">
        <button type="submit">Ajouter un nouveau livre</button>
    </form>
    <form id="empruntForm" method="post" action="save.php">
        <!-- Vos champs de formulaire ici -->
        <button type="submit" formaction="gestionEm.php">les livre emprunter</button>
    </form>
    <form id="empruntForm" method="post" action="ajouRespo.php">
        <button type="submit" formaction="ajouRespo.php">ajouter Responsable</button>
    </form>
    <form action="" method="post" id="livresForm">
        <h2>Bienvenue à la Gestion des Livres</h2>
        <label>Catégorie :</label>
        <select name="categorie" class="select-w-25">
            <option value="arabe1">Arabe</option>
            <option value="mathematique">Mathematique</option>
            <option value="physique">Physique</option>
            <option value="informatique">Informatique</option>
            <option value="svt">SVT</option>
            <option value="education">علوم التربية الفرنسية</option>
            <option value="arabe1">علوم التربية العربية</option>
            <option value="historique">التاريخ و الجغرافيا</option>
            <option value="phylosophie">الفلسفة</option>
            <option value="tachrie">التشريع</option>
        </select>
        <br>
        <label>Recherche par nom de livre :</label>
        <input type="text" name="book_name">
        <button type="submit" name="action" value="rechercher">Rechercher</button>
        <button type="submit" name="lister_tout" value="true">Lister tous les livres</button>
    </form>

    <?php
    // Affichage des erreurs PHP
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['categorie'])) {
        include 'conx.php';

        // Valider et filtrer les entrées
        $categorie = filter_input(INPUT_POST, 'categorie', FILTER_SANITIZE_STRING);
        $book_name = isset($_POST['book_name']) ? trim($_POST['book_name']) : '';

        // Initialiser la variable SQL
        $sql = '';

        // Vérifier l'action soumise
        if (isset($_POST['action']) && $_POST['action'] == 'rechercher' && !empty($book_name)) {
            // Recherche par nom de livre
            $sql = "SELECT * FROM `" . $categorie . "` WHERE b LIKE '%" . mysqli_real_escape_string($conn, $book_name) . "%'";
        } elseif (isset($_POST['lister_tout'])) {
            // Lister tous les livres
            $sql = "SELECT * FROM `" . $categorie . "`";
        }

        // Exécuter la requête SQL si elle est définie
        if (!empty($sql)) {
            $result = mysqli_query($conn, $sql);

            if ($result) {
                echo "<h3>Résultats pour la catégorie: " . htmlspecialchars($categorie) . "</h3>";
                echo "<table>
                        <tr>
                            <th>Code</th>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Publicité</th>
                            <th>Année</th>
                            <th>Actions</th>
                        </tr>";
                // Afficher les résultats
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['a']) . "</td>
                            <td>" . htmlspecialchars($row['b']) . "</td>
                            <td>" . htmlspecialchars($row['c']) . "</td>
                            <td>" . htmlspecialchars($row['d']) . "</td>
                            <td>" . htmlspecialchars($row['e']) . "</td>
                            <td>
                                <form action='modifier.php' method='post' style='display:inline;'>
                                    <input type='hidden' name='code' value='" . htmlspecialchars($row['a']) . "'>
                                    <button type='submit'>Modifier</button>
                                </form>
                                <form action='supprimer.php' method='post' style='display:inline;'>
                                    <input type='hidden' name='code' value='" . htmlspecialchars($row['a']) . "'>
                                    <input type='hidden' name='categorie' value='" . htmlspecialchars($categorie) . "'>
                                    <button type='submit'>Supprimer</button>
                                </form>
                                <form action='emprinter.php' method='post' style='display:inline;'>
                                    <input type='hidden' name='code' value='" . htmlspecialchars($row['a']) . "'>
                                    <input type='hidden' name='nomLivre' value='" . htmlspecialchars($row['b']) . "'>
                                    <input type='hidden' name='categorie' value='" . htmlspecialchars($categorie) . "'>
                                    <button type='submit'>Emprunter</button>
                                </form>
                            </td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Erreur lors de l'exécution de la requête: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p>Aucune action définie ou le nom de livre est vide.</p>";
        }

        // Fermer la connexion à la base de données
        mysqli_close($conn);
    }
    ?>
</body>
</html>