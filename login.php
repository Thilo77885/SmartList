<?php
session_start();
$server = "localhost";
$dbuser = "X";
$dbpassword = "X";
$db_name = "X";
$conn = "";
$conn = mysqli_connect(
    $server,
    $dbuser,
    $dbpassword,
    $db_name
);
if (!$conn) {
    echo "Problem bei der Verbindung";
}

$error_message = ""; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['merker'] == "anmelden") {
        if (empty($_POST['benutzername_anmelden']) && empty($_POST['passwort_anmelden'])) {
            $error_message = "Bitte füllen Sie die Felder aus";
        } elseif (empty($_POST['benutzername_anmelden'])) {
            $error_message = "Bitte füllen Sie das Feld Benutzername aus";
        } elseif (empty($_POST['passwort_anmelden'])) {
            $error_message = "Bitte füllen Sie das Feld Passwort aus";
        } else {
            $username = $_POST['benutzername_anmelden'];
            $password = $_POST['passwort_anmelden'];
            $sqll = "SELECT Passwort FROM `Konto` WHERE Benutzername='$username'";
            $password_verify = mysqli_query($conn, $sqll);
            $row = mysqli_fetch_assoc($password_verify);
            $hashed_password_output = $row['Passwort'];
            if (password_verify($password, $hashed_password_output)) {
                $_SESSION['benutzername'] = $username;
                $_SESSION['Passwort_Benutzer'] =$password ;
                header("Location: homepage.php");
                exit();
            } else {
                $error_message = "Kein Benutzer mit diesem Namen gefunden!";
            }
        }
    } elseif ($_POST['merker'] == "registrieren") {
        if (empty($_POST['benutzername_registrieren']) && empty($_POST['passwort_registrieren'])) {
            $error_message = "Bitte füllen Sie die Felder aus";
        } elseif (empty($_POST['benutzername_registrieren'])) {
            $error_message = "Bitte füllen Sie das Feld Benutzername aus";
        } elseif (empty($_POST['passwort_registrieren'])) {
            $error_message = "Bitte füllen Sie das Feld Passwort aus";
        } else {
            $username = $_POST['benutzername_registrieren'];
            $password = $_POST['passwort_registrieren'];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `Konto` (Benutzername,Passwort) VALUES ('$username', '$hashedPassword')";
            mysqli_query($conn, $sql);
            $_SESSION['benutzername'] = $username;
            $_SESSION['Passwort_Benutzer'] = $hashedPassword;
            header("Location: homepage.php");
            exit();
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        
        .anmelden {
            display: flex;
            flex-direction: column;
            background-color: #f4f1e1;
            height: 80vh;
            width: 85%;
            justify-content: center;
            align-items: center;
            padding: 20px;
            border-radius:5%;
            border: 3px solid black;
            
        }

        .anmelden p {
            margin: 10px 0;
        }

        .anmelden input[type="text"],
        .anmelden input[type="password"] {
            margin-bottom: 15px;
            padding: 10px;
            width: 100%;
            max-width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .anmelden input[type="submit"],
        .anmelden button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #8d8d8d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .anmelden input[type="submit"]:hover,
        .anmelden button:hover {
            background-color: #6c6c6c;
        }

        .error-message {
            color: red;
            margin-top: 15px;
            font-size: 14px;
        }
        .headline{
            padding-bottom:40px;
            @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap');
            font-family: 'Dancing Script', cursive;
            font-size: 48px;
            color: #4b2c20; 
            text-align: center;
            letter-spacing: 2px; 
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            padding: 20px;
            font-weight: bold;
            padding-top:20px;
                }
        .body{
            background-color: #6c6c6c;/*problem*/
        }
    </style>
</head>
<body>
    

    <form action="login.php" method="POST" class="anmelden">
        <p class="headline">SmartList</p>
        <input type="hidden" name="merker" value="anmelden">
        <p>Benutzername:</p>
        <input type="text" name="benutzername_anmelden" value="">
        <p>Passwort:</p>
        <input type="password" name="passwort_anmelden" value="">
        <?php if (!empty($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <input type="submit" name="submit_anmelden">
        <button type="button" onclick="registrieren_funktion()">Registrieren</button>
    </form>

    <script>
        function registrieren_funktion() {
            event.preventDefault();
            let registrieren = document.querySelector(".anmelden");
            registrieren.innerHTML = `
                 <p class="headline">SmartList</p>
                <p>Wählen Sie ein Benutzername:</p>
                <input type="hidden"  name="merker" value="registrieren">
                <input type="text" maxlength="15" name="benutzername_registrieren" value="">
                <p>Wählen Sie ein Passwort:</p>
                <input type="password" name="passwort_registrieren" value="">
                <!-- Fehlernachricht-Container (initial leer) -->
                <div class="error-message"></div>
                <input type="submit" name="submit_registrieren">
                <button type="button" onclick="anmelden_funktion()">Anmelden</button>`;
        }

        function anmelden_funktion() {
            event.preventDefault();
            let registrieren = document.querySelector(".anmelden");
            registrieren.innerHTML = `
             <p class="headline">SmartList</p>
                <input type="hidden" name="merker" value="anmelden">
                <p>Benutzername:</p>
                <input type="text" name="benutzername_anmelden" value="">
                <p>Passwort:</p>
                <input type="password" name="passwort_anmelden" value="">
                <!-- Fehlernachricht-Container (initial leer) -->
                <div class="error-message"></div>
                <input type="submit" name="submit_anmelden">
                <button type="button" onclick="registrieren_funktion()">Registrieren</button>`;
        }
    </script>

</body>
</html>
