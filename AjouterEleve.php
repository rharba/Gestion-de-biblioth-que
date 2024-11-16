<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter un élève</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    form {
        width: 50%;
        margin: 20px auto;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>

<h2>Ajouter un élève</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required><br>

    <label for="specialite">Spécialité :</label>
    <input type="text" id="specialite" name="specialite" required><br>

    <label for="groupe">Groupe :</label>
    <input type="text" id="groupe" name="groupe" required><br>

    <label for="numero_telephone">Numéro de téléphone :</label>
    <input type="text" id="numero_telephone" name="numero_telephone" required><br>

    <label for="responsableSelect">Responsable :</label>
    <select id="responsableSelect" name="responsable_id">
        <option value="">Sélectionnez un responsable</option>
        <?php
        // Inclure le fichier de connexion à la base de données
        include 'connexion.php';

        // Récupérer la liste des responsables depuis la base de données
        $sql_responsables = "SELECT id, nom, prenom FROM responsable";
        $result_responsables = mysqli_query($conn, $sql_responsables);

        // Afficher chaque responsable comme une option dans la liste déroulante
        while ($row_responsable = mysqli_fetch_assoc($result_responsables)) {
            echo "<option value='" . $row_responsable['id'] . "'>" . $row_responsable['nom'] . " " . $row_responsable['prenom'] . "</option>";
        }

        // Fermer la connexion à la base de données
        mysqli_close($conn);
        ?>
    </select><br>

    <button type="submit">Ajouter l'élève</button>
</form>

<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include 'connexion.php';

    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $specialite = $_POST['specialite'];
    $groupe = $_POST['groupe'];
    $numero_telephone = $_POST['numero_telephone'];
    $responsable_id = $_POST['responsable_id'];

    // Préparer la requête SQL pour insérer un nouvel élève
    $sql = "INSERT INTO eleve (nom, prenom, specialite, groupe, numero_telephone, responsable_id) VALUES ('$nom', '$prenom', '$specialite', '$groupe', '$numero_telephone', '$responsable_id')";

    // Exécuter la requête SQL
    if (mysqli_query($conn, $sql)) {
        echo "<p>Nouvel élève ajouté avec succès.</p>";
    } else {
        echo "<p>Erreur : " . $sql . "<br>" . mysqli_error($conn) . "</p>";
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
}
?>

</body>
</html>
