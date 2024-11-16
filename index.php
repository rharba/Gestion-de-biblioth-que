<?php
// Inclure le fichier de connexion à la base de données
include 'conx.php';

// Initialiser les variables de filtre
$actionFilter = isset($_GET['actionFilter']) ? $_GET['actionFilter'] : 'all';
$yearFilter = isset($_GET['yearFilter']) ? intval($_GET['yearFilter']) : date('Y');

// Construire la requête SQL en fonction des filtres
$sql = "SELECT e.id, e.codeLivre, e.nomLivre, e.categorie, CONCAT(el.nom, ' ', el.prenom) AS nomComplet, el.numero_telephone AS telephone, el.specialite, el.groupe, e.dateEmprunt, e.dateFinEmprunt, DATEDIFF(e.dateFinEmprunt, CURDATE()) AS tempsRestant 
        FROM emprunts e
        INNER JOIN eleve el ON e.idEleve = el.id
        WHERE YEAR(e.dateEmprunt) = $yearFilter";

// Ajouter la condition pour le filtre de l'état de retour
if ($actionFilter === 'retour') {
    $sql = "SELECT r.id, r.codeLivre, r.nomLivre, r.categorie, CONCAT(el.nom, ' ', el.prenom) AS nomComplet, el.numero_telephone AS telephone, el.specialite, el.groupe, r.dateEmprunt, r.dateFinEmprunt, DATEDIFF(r.dateFinEmprunt, CURDATE()) AS tempsRestant 
            FROM retours r
            INNER JOIN eleve el ON r.idEleve = el.id
            WHERE YEAR(r.dateEmprunt) = $yearFilter";
} elseif ($actionFilter === 'nonretour') {
    $sql = "SELECT nr.id, nr.codeLivre, nr.nomLivre, nr.categorie, CONCAT(el.nom, ' ', el.prenom) AS nomComplet, el.numero_telephone AS telephone, el.specialite, el.groupe, nr.dateEmprunt, nr.dateFinEmprunt, DATEDIFF(nr.dateFinEmprunt, CURDATE()) AS tempsRestant 
            FROM nonretours nr
            INNER JOIN eleve el ON nr.idEleve = el.id
            WHERE YEAR(nr.dateEmprunt) = $yearFilter";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<form action="gestionbook.php" method="post">
        <button type="submit">Retour Accueil</button>
    </form>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des emprunts</title>
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers le fichier CSS externe -->
</head>
<body>
    <form method="GET" action="index.php">
        <label for="actionFilter">Filtrer par état :</label>
        <select name="actionFilter" id="actionFilter">
            <option value="all" <?php if ($actionFilter === 'all') echo 'selected'; ?>>Tous</option>
            <option value="retour" <?php if ($actionFilter === 'retour') echo 'selected'; ?>>Retour</option>
            <option value="nonretour" <?php if ($actionFilter === 'nonretour') echo 'selected'; ?>>Non retour</option>
        </select>

        <label for="yearFilter">Année d'emprunt :</label>
        <input type="number" name="yearFilter" id="yearFilter" value="<?php echo htmlspecialchars($yearFilter); ?>">

        <button type="submit">Filtrer</button>
    </form>

    <?php
    if ($result) {
        // Afficher les informations dans un tableau
        echo "<table>";
        echo "<tr>
                <th>Nom Complet</th>
                <th>Numéro de téléphone</th>
                <th>Code du livre</th>
                <th>Catégorie</th>
                <th>Date d'emprunt</th>
                <th>Date de fin d'emprunt</th>
                <th>Temps Restant (jours)</th>
                <th>Action</th>
            </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nomComplet']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telephone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['codeLivre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['categorie']) . "</td>";
            echo "<td>" . htmlspecialchars($row['dateEmprunt']) . "</td>";
            echo "<td>" . htmlspecialchars($row['dateFinEmprunt']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tempsRestant']) . "</td>"; // Afficher le temps restant
            
            // Ajouter des boutons pour indiquer l'état et supprimer
            echo "<td>";
            echo "<button class='return-button' onclick='marquerRetour(" . $row['id'] . ")'>Retour</button>";
            echo "<button class='not-return-button' onclick='marquerNonRetour(" . $row['id'] . ")'>Non Retour</button>";
            echo "<button class='delete-button' onclick='supprimerEmprunt(" . $row['id'] . ", \"" . $actionFilter . "\")'>Supprimer</button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        // Afficher un message d'erreur si la requête a échoué
        echo "Erreur dans l'exécution de la requête : " . mysqli_error($conn);
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
    ?>
    <script>
    function marquerRetour(id) {
        if (confirm('Êtes-vous sûr de vouloir marquer ce livre comme retourné ?')) {
            fetch('etat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=retour&id=' + id
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    alert('Livre marqué comme retourné');
                    location.reload();
                } else {
                    alert('Erreur: ' + data);
                }
            });
        }
    }

    function marquerNonRetour(id) {
        if (confirm('Êtes-vous sûr de vouloir marquer ce livre comme non retourné ?')) {
            fetch('etat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=nonretour&id=' + id
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    alert('Livre marqué comme non retourné');
                    location.reload();
                } else {
                    alert('Erreur: ' + data);
                }
            });
        }
    }

    function supprimerEmprunt(id, actionFilter) {
        let table = 'emprunts';
        if (actionFilter === 'retour') {
            table = 'retours';
        } else if (actionFilter === 'nonretour') {
            table = 'nonretours';
        }

        if (confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?')) {
            fetch('etat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=supprimer&id=' + id + '&table=' + table
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    alert('Emprunt supprimé');
                    location.reload();
                } else {
                    alert('Erreur: ' + data);
                }
            });
        }
    }
    </script>
</body>
</html>
