
<?php
$categorie = filter_input(INPUT_POST, 'categorie', FILTER_SANITIZE_STRING);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['code'])) {
    include 'conx.php';

    // Récupérer le code du livre à supprimer
    $code_livre = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_STRING);

    // Préparer la requête SQL pour supprimer le livre
    $sql_suppression = "DELETE FROM `" . $categorie . "` WHERE a = '" . $code_livre . "'";

    // Exécuter la requête
    if (mysqli_query($conn, $sql_suppression)) {
        echo "<p>Livre supprimé avec succès.</p>";
        // Peut-être rediriger l'utilisateur vers une autre page après la suppression
        // header("Location: index.php");
        // exit();
    } else {
        echo "<p>Erreur lors de la suppression du livre: " . mysqli_error($conn) . "</p>";
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
}
?>
