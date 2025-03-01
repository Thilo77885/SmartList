<?php
session_start();
$server = "localhost";
$dbuser = "thilo";
$dbpassword = "wzEwM-x95WIOrT51luvb";
$db_name = "thilo123";
$conn = "";
$conn = mysqli_connect(
    $server,
    $dbuser,
    $dbpassword,
    $db_name);

if (isset($_SESSION['aufrufe'])) {
    $aufrufe = $_SESSION['aufrufe'];
} else {
    header("Location: homepage.php");
    exit();
}
$sql="Select Aufrufe from `Aufrufe`";
$zahlaufrufe = mysqli_query($conn, $sql);
$listeaufrufe = mysqli_fetch_assoc($zahlaufrufe);
$zahlaufrufe=$listeaufrufe['Aufrufe'];
$zahlaufrufe+=1;
$sql="UPDATE Aufrufe SET Aufrufe =$zahlaufrufe";
mysqli_query($conn, $sql);
$_SESSION['aufrufe']=$zahlaufrufe;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .body1{
            background-color:#FFEAB3;
        }
        .title{
            @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap');
            font-family: 'Dancing Script', cursive;
            font-size: 60px;
            color: #4b2c20; 
            text-align: center;
            letter-spacing: 2px; 
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            font-weight: bold;
             
        }
        .aufrufe_p{
            text-align: center;
            font-weight: bold;
            font-size:25px;
        }
        .aufrufe{
            text-align: center;
            font-weight: bold;
            font-size:25px;
        }
        .button-container {
        display: flex; 
        justify-content: center; 
        margin-top: 100px; 
        }
        .back {
        font-size: 25px;
        width: 100px;
        border-radius: 10%;
        border: 2px solid black;
        font-weight: bold;
        background-color: white;
        }
    </style>
</head>
<body class="body1">
    <p class="title">SmartList</p>
    <p class="aufrufe_p">Aufrufe:</p>
    <p class="aufrufe"><?php echo $aufrufe ?></p>
    <div class="button-container">
    <button class="back" onclick="window.location.href = 'https://thilo.saams.de/homepage.php'">Zurück</button>
    </div>
<script>
    
</script>
</body>

</html>