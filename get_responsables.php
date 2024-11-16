<?php
// Inclure le fichier de connexion à la base de données
include 'conx.php';

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

// Récupérer la lettre de recherche du responsable
$lettre = isset($_GET['lettre']) ? $_GET['lettre'] : '';

// Préparer la requête SQL pour récupérer les responsables correspondant à la lettre de recherche
$sql = "SELECT id, nom, prenom FROM responsable WHERE nom LIKE '$lettre%' ORDER BY nom";

$result = mysqli_query($conn, $sql);

// Créer un tableau pour stocker les résultats
$responsables = array();
while ($row = mysqli_fetch_assoc($result)) {
    $responsables[] = $row;
}

// Retourner les résultats au format JSON
echo json_encode($responsables);
?>
