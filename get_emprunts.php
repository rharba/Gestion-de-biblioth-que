<?php
require 'conx.php';

if (isset($_GET['eleveId'])) {
    $eleveId = htmlspecialchars($_GET['eleveId']);
    
    $stmt = $pdo->prepare('SELECT * FROM emprunts WHERE idEleve = ?');
    $stmt->execute([$eleveId]);
    $emprunts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($emprunts);
}
?>
