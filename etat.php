<?php
// Inclure le fichier de connexion à la base de données
include 'conx.php';

// Vérification du formulaire pour le marquage de retour, non retour ou suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action === 'retour' || $action === 'nonretour') {
        $table = $action === 'retour' ? "retours" : "nonretours";

        // Utilisation de préparations de requêtes pour éviter les injections SQL
        $insertQuery = "INSERT INTO $table (id, codeLivre, nomLivre, categorie, idEleve, dateEmprunt, dateFinEmprunt)
                        SELECT id, codeLivre, nomLivre, categorie, idEleve, dateEmprunt, dateFinEmprunt
                        FROM emprunts WHERE id = ?";
        $deleteQuery = "DELETE FROM emprunts WHERE id = ?";

        // Utilisation de prepared statements
        $stmtInsert = mysqli_prepare($conn, $insertQuery);
        $stmtDelete = mysqli_prepare($conn, $deleteQuery);

        if ($stmtInsert && $stmtDelete) {
            mysqli_stmt_bind_param($stmtInsert, "i", $id);
            mysqli_stmt_bind_param($stmtDelete, "i", $id);

            // Exécution de la requête d'insertion dans la table de retour ou non retour
            if (mysqli_stmt_execute($stmtInsert)) {
                // Exécution de la requête de suppression dans la table des emprunts
                if (mysqli_stmt_execute($stmtDelete)) {
                    echo "success";
                } else {
                    echo "Erreur lors de la suppression : " . mysqli_stmt_error($stmtDelete);
                }
            } else {
                echo "Erreur lors de l'insertion dans la table $table : " . mysqli_stmt_error($stmtInsert);
            }

            // Fermeture des statements
            mysqli_stmt_close($stmtInsert);
            mysqli_stmt_close($stmtDelete);
        } else {
            echo "Erreur de préparation de la requête : " . mysqli_error($conn);
        }
    } elseif ($action === 'supprimer') {
        $table = $_POST['table'];
        $deleteQuery = "DELETE FROM $table WHERE id = ?";

        // Utilisation de prepared statement pour la suppression
        $stmtDelete = mysqli_prepare($conn, $deleteQuery);

        if ($stmtDelete) {
            mysqli_stmt_bind_param($stmtDelete, "i", $id);

            // Exécution de la requête de suppression
            if (mysqli_stmt_execute($stmtDelete)) {
                echo "success";
            } else {
                echo "Erreur lors de la suppression de l'emprunt : " . mysqli_stmt_error($stmtDelete);
            }

            // Fermeture du statement
            mysqli_stmt_close($stmtDelete);
        } else {
            echo "Erreur de préparation de la requête de suppression : " . mysqli_error($conn);
        }
    } else {
        echo "Action non valide.";
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
    exit(); // Arrêter l'exécution après traitement POST
}
?>
