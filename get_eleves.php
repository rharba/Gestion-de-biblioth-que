<?php
// Inclure le fichier de connexion à la base de données
include 'conx.php';

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

// Récupérer l'ID du responsable et la lettre de recherche de l'élève
$responsableId = isset($_GET['responsableId']) ? intval($_GET['responsableId']) : 0;
$lettre = isset($_GET['lettre']) ? $_GET['lettre'] : '';

// Préparer la requête SQL pour récupérer les élèves associés au responsable et correspondant à la lettre de recherche
$sql = "SELECT id, nom, prenom FROM eleve WHERE responsable_id = $responsableId AND (nom LIKE '%$lettre%' OR prenom LIKE '%$lettre%') ORDER BY nom";

$result = mysqli_query($conn, $sql);

if ($result === false) {
    // En cas d'erreur dans la requête SQL, retourner un tableau vide
    echo json_encode([]);
} else {
    // Créer un tableau pour stocker les résultats
    $eleves = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $eleves[] = $row;
    }

    // Retourner les résultats au format JSON
    echo json_encode($eleves);
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>
