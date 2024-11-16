<?php

include 'conx.php';

if (isset($_POST['ok'])) {
    $userName = $_POST['email'];
    $password = $_POST['password'];

    // Utilisation d'une requête préparée pour éviter les injections SQL
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $userName);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Vérification du mot de passe avec password_verify
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $userName;
            if ($row['role'] == 'responsable') {
                header('Location: gestionbook.php');
            } elseif ($row['role'] == 'etd') {
                header('Location: ajouEleve.php');
            } else {
                header('Location: autre_page.php'); // Redirection vers une autre page pour d'autres rôles si nécessaire
            }
            exit;
        } else {
            echo "<script>alert('Mot de passe incorrect. Veuillez réessayer.');</script>";
        }
    } else {
        echo "<script>alert('Nom d'utilisateur ou Mot de passe invalide. Veuillez réessayer.');</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>المكتبة | CRMEF Kenitra</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        
        header {
            background: #007bff;
            color: #fff;
            padding: 20px 0;
            border-bottom: 4px solid #0056b3;
        }
        
        #branding h1 {
            margin: 0;
            text-align: center;
            font-size: 2em;
        }
        
        section {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        footer {
            background: #343a40;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }
        
        form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        form input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        
        form button {
            background: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        
        form button:hover {
            background: #218838;
        }
        
        p {
            margin: 0;
            padding: 0;
            text-align: center;
        }
        
        a {
            color: #007bff;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>المكتبة</h1>
            </div>
        </div>
    </header>
    <div class="container">
        <section>
            <form action="" method="post">
                <label for="email">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" required>
    
                <label for="password">كلمة المرور:</label>
                <input type="password" id="password" name="password" required>
    
                <button type="submit" name="ok">دخول</button>
            </form>
            <p>Pas encore inscrit ? <a href="newCompte.php">S'inscrire</a></p>
        </section>
    </div>
    <footer>
        <p>CRMEF Kenitra &copy; 2024</p>
    </footer>
</body>
</html>
