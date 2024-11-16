<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4; /* Couleur de fond de la page */
    margin: 0;
    padding: 20px;
}

fieldset {
    border: 1px solid #ddd;
    background-color: #fff;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 20px;
}

legend {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
}

label {
    display: block;
    margin: 10px 0 5px;
}
input[type="text"],
input[type="password"],
input[type="email"],
select,
input[type="file"] {
    width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;}
.inscrpt {
    max-width: 500px;
    margin: auto;
}

.fa-solid, .fa-regular { 
    margin-right: 5px;
}

input[type="radio"] {
    margin: 0 10px 0 5px;
}
body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            border-bottom: 5px solid orange;
        }

        header a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            margin-left: 20px;
            font-size: 16px;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        header ul {
            margin: 0;
            padding: 0;
        }

        header li {
            float: left;
            list-style: none;
            padding: 0 20px;
            font-size: 14px;
        }

        header #branding h1 {
            margin: 0;
            font-size: 24px;
        }

        #newletter {
            background: #3b4e5a;
            color: #fff;
            padding: 20px;
        }

        #newletter h1 {
            margin: 0;
            font-size: 24px;
        }

        form {
            margin-top: 20px;
        }
        button {
            padding: 10px 40px;
            border: none;
            border-radius: 9px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        footer {
            padding: 20px;
            font-size: 14px;
            margin-top: 20px;
            color: #fff;
            background-color: #e8491d;
            text-align: center;
        }
</style>
<?php 
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'books';
$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['validate'])) {
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prénom'];
    $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $genre = $_POST['Genre'];
    $filiere = $_POST['filiere'];
    $role = $_POST['role'];
    $sql = "INSERT INTO utilisateur (name, lastname, password, email, sexe, filiere,role) VALUES ('$nom', '$prenom', '$pwd', '$email', '$genre', '$filiere', '$role' )";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['auth'] = true;
   
        $_SESSION['id'] = mysqli_insert_id($conn); 
        $_SESSION['name'] = $nom;
        $_SESSION['lastname'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['sexe'] = $genre;
        $_SESSION['filiere'] = $filiere;
        $_SESSION['role'] = $role;
        header('Location:login.php');
    } else {
        echo "Erreur dans la requête : " . mysqli_error($conn);
    }
}

?>

<html> 
<head>
    <meta charset="utf-8">
    <meta name="description" content=" CRMEF Kenitra>
    <meta name="keywords" content="  CRMEF Kenitra">
    <title>المكتبة | CRMEF Kenitra</title>
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
<body>
<header>
    <div class="container">
        <div id="branding">
            <h1>المكتبة </h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Accueil</a></li>
            </ul>
        </nav>
    </div>
</header>
    <fieldset>
        <form method="post" action="" enctype="multipart/form-data"  >
            <div class="inscrpt">
                <label><i class="fa-solid fa-user"></i>Nom :</label>
                <input type="text" name="Nom" placeholder="entrez votre nom">
                <br>
                <label><i class="fa-solid fa-user"></i>Prénom :</label>
                <input type="text" name="Prénom" placeholder="entrez votre prénom">
                <br>
                <label>Mot de passe</label>
                <input type="password" name="password">
                <br>
                <label><i class="fa-solid fa-envelope"></i>Email :</label>
                <input type="email" name="email" placeholder="XXXXX@gmail.com">
                <br> 
                <label><i class="fa-solid fa-genderless"></i>Genre :</label>
                <input type="radio" name="Genre" value="H">H
                <input type="radio" name="Genre" value="F">F
                <br>
                <label><i class="fa-regular fa-code-branch"></i>Branche :</label>
                <select name="filiere">
                    <option value="DSI">DSI</option>
                    <option value="PME/PMI">PME/PMI</option>
                    <option value="SE">SE</option>
                    <option value="CPI">CPI</option>
                    <option value="ELT">ELT</option>
                    <option value="MI">MI</option>
                </select>
                <label for="role">Rôle :</label>
            <select id="role" name="role">
                <option value="etd">Etudiant</option>
                <option value="Responsable">Responsable</option>
            </select>
                <button type="submit" name="validate">Inscription</button>
            </div>
        </form>
    </fieldset>
    <footer>
    <p>CRMEF Kenitra &copy; 2024</p>
</footer>
</body>