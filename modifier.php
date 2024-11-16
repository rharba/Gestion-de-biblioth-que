<?php
include 'conx.php';

$categorie = filter_input(INPUT_POST, 'categorie', FILTER_SANITIZE_STRING);
$code_livre = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);

$titre = '';
$auteur = '';
$publicite = '';
$annee = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['code']) && !isset($_POST['modifier'])) {
        // Préparer la requête SQL pour obtenir les détails du livre
        $sql_select = "SELECT * FROM `" . $categorie . "` WHERE code='" . $code_livre . "'";
        $result = mysqli_query($conn, $sql_select);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $titre = $row['b']; // Assurez-vous que le nom des colonnes correspond à votre base de données
            $auteur = $row['c'];
            $publicite = $row['d'];
            $annee = $row['e'];
        } else {
            echo "<p>Livre non trouvé.</p>";
        }
    } elseif (isset($_POST['modifier'])) {
        // Récupérer les informations du livre à mettre à jour
        $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
        $auteur = filter_input(INPUT_POST, 'auteur', FILTER_SANITIZE_STRING);
        $publicite = filter_input(INPUT_POST, 'publicite', FILTER_SANITIZE_STRING);
        $annee = filter_input(INPUT_POST, 'annee', FILTER_SANITIZE_STRING);

        // Préparer la requête SQL pour mettre à jour le livre
        $sql_update = "UPDATE `" . $categorie . "` SET b='" . $titre . "', c='" . $auteur . "', d='" . $publicite . "', e='" . $annee . "' WHERE code='" . $code_livre . "'";

        // Exécuter la requête
        if (mysqli_query($conn, $sql_update)) {
            echo "<p>Informations du livre mises à jour avec succès.</p>";
            header("Location: gestionbook.php");
            exit();
        } else {
            echo "<p>Erreur lors de la mise à jour des informations du livre: " . mysqli_error($conn) . "</p>";
        }
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
}
?>

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
    </style>
</head>
<body>
    <h2>Modifier les Informations du Livre</h2>
    <form method="POST" action="">
        <input type="hidden" name="categorie" value="<?php echo htmlspecialchars($categorie); ?>">
        <input type="hidden" name="code" value="<?php echo htmlspecialchars($code_livre); ?>">

        <label>Code du livre :</label>
        <input type="text" name="code_display" value="<?php echo htmlspecialchars($code_livre); ?>" disabled>

        <label>Titre :</label>
        <input type="text" name="titre" required value="<?php echo htmlspecialchars($titre); ?>">

        <label>Auteur :</label>
        <input type="text" name="auteur" required value="<?php echo htmlspecialchars($auteur); ?>">

        <label>Publicité :</label>
        <input type="text" name="publicite" required value="<?php echo htmlspecialchars($publicite); ?>">

        <label>Année :</label>
        <input type="text" name="annee" required value="<?php echo htmlspecialchars($annee); ?>">

        <button type="submit" name="modifier">Modifier</button>
    </form>
</body>
</html>
