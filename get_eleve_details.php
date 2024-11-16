<?php
// Include the database connection file
include('conx.php');

// Get the student ID from the GET request
$eleveId = isset($_GET['eleveId']) ? intval($_GET['eleveId']) : 0;

$response = [];

if ($eleveId > 0) {
    // Requête SQL pour récupérer les détails de l'élève
    $sql = "SELECT nom, prenom, numero_telephone, specialite, groupe FROM eleve WHERE id = $eleveId";
    $result = $conn->query($sql);

    if ($result === false) {
        // Erreur de requête SQL
        $response['error'] = $conn->error;
    } else {
        if ($result->num_rows > 0) {
            // Récupérer les données de l'élève
            $row = $result->fetch_assoc();
            $response = $row;
        } else {
            // Aucun élève trouvé avec l'ID fourni
            $response['error'] = 'Aucun élève trouvé avec l\'ID fourni';
        }
    }
} else {
    // ID d'élève invalide
    $response['error'] = 'ID d\'élève invalide';
}

// Assurez-vous que la réponse est au format JSON
header('Content-Type: application/json');
echo json_encode($response);

// Fermez la connexion à la base de données
$conn->close();
?>
