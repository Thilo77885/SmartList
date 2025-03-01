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
if (isset($_SESSION['benutzername'])) {
    $user=$_SESSION['benutzername'];
 }else {
    header("Location: login.php");
     exit();
 }

 if ($_SERVER["REQUEST_METHOD"] == "GET"){
    if (isset($_GET["typ_aa"]) && isset($_GET["ersteller_aa"]) && isset($_GET["absender_aa"]) && isset($_GET["code_aa"])){
        $typ_aa=$_GET["typ_aa"];
        $ersteller_aa=$_GET["ersteller_aa"];
        $absender_aa=$_GET["absender_aa"];
        $code_aa=$_GET["code_aa"];
        if ($typ_aa=="accept"){
            $sql="DELETE FROM `Anfragen` WHERE Ersteller='$ersteller_aa' And Absender='$absender_aa' AND Passwort='$code_aa'";
            mysqli_query($conn, $sql);
            $sql="SELECT ID FROM `Einkaufslistennamen` WHERE Code='$code_aa'";
            $result_aa=mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result_aa);
            $row=$row['ID'];
            echo $row;
            $sql="INSERT INTO `Verknuepfungen` (ID,User) VALUES ('$row','$absender_aa')";/*Warte*/
            mysqli_query($conn, $sql);
            header("Location: system.php");
            exit();
        }else{


            $sql="DELETE FROM `Anfragen` WHERE Ersteller='$ersteller_aa' And Absender='$absender_aa' AND Passwort='$code_aa'";
            mysqli_query($conn, $sql);
            header("Location: system.php");
            exit();
        }
    }
 }



 $sql = "SELECT COUNT(*) AS anzahl FROM `Anfragen` WHERE Ersteller='$user'";
 $result = mysqli_query($conn, $sql); 
 $row = mysqli_fetch_assoc($result);
 $anzahl = $row['anzahl'];
$sql = "SELECT * FROM `Anfragen` WHERE Ersteller='$user'";
$result = mysqli_query($conn, $sql);
$array_listen = [];
while ($row = mysqli_fetch_assoc($result)) {
    $array_listen[] = $row;
    $code = $array_listen[$counter]['Code'];
    $sql="SELECT `Listenname` FROM `Einkaufslistennamen` WHERE Code='$code'";
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
        .body_system{
            background-color:grey;
            padding-top:65px;
        }
        .oberer_balken {
            position: fixed;
            background-color: #FFF8E1;
            width: 100%;
            top: 0;
            right: 0;
            left: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 10;
            height: 60px;
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            
        }
        .back_homepage_button{
            background-color:transparent;
            border:none;
        }
        .close{
         height:50px;
         background-color:transparent;
         border:none;
        }
        .title {
            font-family: 'Dancing Script', cursive;
            font-size: 48px;
            color: #4b2c20; 
            letter-spacing: 2px; 
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            font-weight: bold;
            flex-grow: 1; 
            text-align: center; 
        }
        .annehmen_aa,
        .ablehnen_aa{
            display:inline-block;
            width:80px;
            height:30px;
            font-weight:bold;

        }
        .annehmen_aa{
            background-color:green;
        }
        .ablehnen_aa{
            background-color:red;
        }
        .text_aa{
            font-size:17px;
        }
        .div_style_aa{
            border:2px solid black;
            padding-left:5px;
            margin-top:5px;
        }
        .form_aa{
            display:none;
        }
        .none{
            color:white;
        }


    </style>
</head>
<body class="body_system">
    <div class="oberer_balken">
            <button class="back_homepage_button" onclick="back()">
            <img src="close-150192_1280.png" class="close" >
            </button>
            <p class="title">SmartList</p>
    </div>
    <div class="chat_div">

    </div>
    <form class="form_aa" action="system.php" method="GET">
        <input name="typ_aa" class="typ_aa">
        <input name="ersteller_aa"  class="ersteller_aa">
        <input name="absender_aa"  class="absender_aa">
        <input name="code_aa" class="code_aa">


    </form>

    <script>
        let array_listen = <?php echo json_encode($array_listen); ?>;
        let array_listen_length=array_listen.length
        console.log(array_listen)
        console.log(array_listen_length)
        let zahl=0
        let array=[]
        while (zahl<array_listen_length){
            let html=`
            <div class="div_style_aa">
            <p class="text_aa"><span style="color: white;">${array_listen[zahl].Absender}</span> möchte auf ihre Liste</p>
            <p class="text_aa"><span style="color: white;">"${array_listen[zahl].Listenname}"</span> zugreifen</p>
            <button class="annehmen_aa" onclick="accept_aa('${array_listen[zahl].Ersteller}','${array_listen[zahl].Absender}','${array_listen[zahl].Passwort}')">Annehmen</button>
            <button class="ablehnen_aa" onclick="refuse_aa('${array_listen[zahl].Ersteller}','${array_listen[zahl].Absender}','${array_listen[zahl].Passwort}')">Ablehnen</button>
            </div>
            `
            zahl+=1
            array.push(html)
        }
        let div_aa=document.querySelector(".chat_div")
        if (array.length === 0){
            div_aa.innerHTML=`<p class="text_aa none">Keine Anfragen,bitte schauen sie später nocheinmal nach!</p>`
        }else{
            div_aa.innerHTML=array

        }
        
        



        function refuse_aa(ersteller,absender,code){
            let typ_aa_typ=document.querySelector(".typ_aa")
            let typ_aa_ersteller=document.querySelector(".ersteller_aa")
            let typ_aa_absender=document.querySelector(".absender_aa")
            let typ_aa_code=document.querySelector(".code_aa")
            typ_aa_typ.value="refuse"
            typ_aa_ersteller.value=ersteller
            typ_aa_absender.value=absender
            typ_aa_code.value=code
            let form_aa=document.querySelector(".form_aa")
            form_aa.submit()




        }
        function accept_aa(ersteller,absender,code){
            let typ_aa_typ=document.querySelector(".typ_aa")
            let typ_aa_ersteller=document.querySelector(".ersteller_aa")
            let typ_aa_absender=document.querySelector(".absender_aa")
            let typ_aa_code=document.querySelector(".code_aa")
            typ_aa_typ.value="accept"
            typ_aa_ersteller.value=ersteller
            typ_aa_absender.value=absender
            typ_aa_code.value=code
            let form_aa=document.querySelector(".form_aa")
            form_aa.submit()

        }




        function back(){
             window.location.href = 'https://thilo.saams.de/homepage.php'

        }
    </script>

    
</body>
</html>