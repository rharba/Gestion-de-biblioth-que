<?php
// Vérifier si des valeurs ont été transmises via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeLivre = isset($_POST['codeLivre']) ? htmlspecialchars($_POST['codeLivre']) : '';
    $nomLivre = isset($_POST['nomLivre']) ? htmlspecialchars($_POST['nomLivre']) : '';
    $categorie = isset($_POST['categorie']) ? htmlspecialchars($_POST['categorie']) : '';
    $nom = isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : '';
    $telephone = isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : '';
    $specialite = isset($_POST['specialite']) ? htmlspecialchars($_POST['specialite']) : '';
    $groupe = isset($_POST['groupe']) ? htmlspecialchars($_POST['groupe']) : '';
    $dateEmprunt = isset($_POST['dateEmprunt']) ? htmlspecialchars($_POST['dateEmprunt']) : '';
    $dateFinEmprunt = isset($_POST['dateFinEmprunt']) ? htmlspecialchars($_POST['dateFinEmprunt']) : '';

    // Insérer les informations dans la table emprinter
    include 'conx.php';
    $sql = "INSERT INTO emprinter (codeLivre, nomLivre, categorie, nom, prenom, telephone, specialite, groupe, dateEmprunt, dateFinEmprunt)
            VALUES ('$codeLivre', '$nomLivre', '$categorie', '$nom', '$prenom', '$telephone', '$specialite', '$groupe', '$dateEmprunt', '$dateFinEmprunt')";

    if (mysqli_query($conn, $sql)) {
        echo "Enregistrement effectué avec succès.";
    } else {
        echo "Erreur: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>