<?php
require 'conx.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeLivre = htmlspecialchars($_POST['codeLivre']);
    $nomLivre = htmlspecialchars($_POST['nomLivre']);
    $categorie = htmlspecialchars($_POST['categorie']);
    $idEleve = htmlspecialchars($_POST['idEleve']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $specialite = htmlspecialchars($_POST['specialite']);
    $groupe = htmlspecialchars($_POST['groupe']);
    $dateEmprunt = htmlspecialchars($_POST['dateEmprunt']);
    $dateFinEmprunt = htmlspecialchars($_POST['dateFinEmprunt']);

    // Debugging output
    echo "Code Livre: $codeLivre<br>";
    echo "Nom Livre: $nomLivre<br>";
    echo "Catégorie: $categorie<br>";
    echo "ID de l'élève: $idEleve<br>";
    echo "Téléphone: $telephone<br>";
    echo "Spécialité: $specialite<br>";
    echo "Groupe: $groupe<br>";
    echo "Date Emprunt: $dateEmprunt<br>";
    echo "Date Fin Emprunt: $dateFinEmprunt<br>";

    // Vérifiez si l'élève existe dans la table 'eleve'
    $checkStmt = $conn->prepare('SELECT id FROM eleve WHERE id = ?');
    $checkStmt->bind_param('s', $idEleve);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $stmt = $conn->prepare('INSERT INTO emprunts (codeLivre, nomLivre, categorie, idEleve, telephone, specialite, groupe, dateEmprunt, dateFinEmprunt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        if ($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
        }

        $bind = $stmt->bind_param('sssssssss', $codeLivre, $nomLivre, $categorie, $idEleve, $telephone, $specialite, $groupe, $dateEmprunt, $dateFinEmprunt);
        if ($bind === false) {
            die('bind_param() failed: ' . htmlspecialchars($stmt->error));
        }

        $exec = $stmt->execute();
        if ($exec) {
            header('Location: gestionbook.php');
            exit;
        } else {
            echo "Erreur lors de l'insertion : " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    } else {
        echo "Erreur : l'élève avec l'ID $idEleve n'existe pas.";
    }

    $checkStmt->close();
}

$conn->close();
?>
