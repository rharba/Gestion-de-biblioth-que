<?php
// Assurez-vous d'avoir une connexion à votre base de données
require 'conx.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['eleveId'])) {
        $eleveId = $_GET['eleveId'];

        // Requête SQL pour récupérer l'historique des emprunts de l'élève par son ID
        $query = "SELECT codeLivre, nomLivre, dateEmprunt, dateFinEmprunt FROM emprunts WHERE idEleve = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $eleveId);
        $stmt->execute();
        $result = $stmt->get_result();

        $historique = array();
        while ($row = $result->fetch_assoc()) {
            $historique[] = $row;
        }

        // Fermeture de la requête
        $stmt->close();

        // Retourner les données sous forme de JSON
        echo json_encode($historique);
    } else {
        echo json_encode(array('error' => 'Paramètre eleveId non fourni.'));
    }
} else {
    echo json_encode(array('error' => 'Méthode non autorisée.'));
}

// Fermeture de la connexion
$conn->close();
?>
