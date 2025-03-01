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
$user="";
$array_listen="";
$anzahl="";
if (isset($_SESSION['benutzername'])) {
   $user=$_SESSION['benutzername'];
}else {
   header("Location: login.php");
    exit();
}
if (isset($_SESSION['Passwort_Liste'])) {
    $array_listen=$_SESSION['Passwort_Liste'];
 }else {
    header("Location: homepage.php");
    exit();
 }
if (isset($_SESSION['Anzahl_count'])){
    $anzahl=$_SESSION['Anzahl_count'];
}else{
    header("Location: homepage.php");
    exit();
}
 

if (!$conn) {
    echo "Problem bei der Verbindung";
}
$error_aa="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (!empty($_POST['input_passwort_aa']) && !empty($_POST['input_name_aa'])){
        $benutzername_ersteller=$_POST['input_name_aa'];
        $passwort_ersteller=$_POST['input_passwort_aa'];
        $sql="SELECT ID FROM `Einkaufslistennamen` WHERE Benutzername='$benutzername_ersteller' AND Code='$passwort_ersteller'";
        $result_aa=mysqli_query($conn, $sql);
        if (mysqli_num_rows($result_aa)>0){
            $sql="SELECT Listenname FROM `Einkaufslistennamen` WHERE Code='$passwort_ersteller'";
            $listenname_result=mysqli_query($conn, $sql);
            $listenname_aa=mysqli_fetch_assoc($listenname_result);
            $listenname_aa=$listenname_aa['Listenname'];
            $sql="INSERT INTO `Anfragen` (Ersteller,Absender,Passwort,Listenname) VALUES ('$benutzername_ersteller','$user','$passwort_ersteller','$listenname_aa')";
            mysqli_query($conn, $sql);
            $error_aa="";
            header("Location: connect.php");
            exit();

        }else{
                $error_aa="Fehler beim Verbinden mit dem Konto";
            
            }

        

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        html, body {
        height: 100%;
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
        .body_connect{
            background-color:#D3D3D3;
            padding-top:60px;
        }
        .button_verbinden{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .passwort_anzeigen,
        .passwort_verbinden{
            height:15%;
            width:50%;
            background-color:#FFF8E1;
            border:none;
            margin-top:10%;
            font-weight:bold;
            font-size:20px;
        }
        .passwort_anzeigen_div{
            position:fixed;
            background-color:#FFF8E1;
            top:100px;
            width:340px;
            padding-bottom:10px;
        }
        .close_passwort_picture{
            height:50px;
            
        }
        .array_picture{
            height:40px;
            display:inline-block;
            margin-left:10px;
            
            
        }
        .passwort_anzeigen_div{
            display:none;
        }
        .passwort_anzeigen_p{
            font-size:20px;
            margin-left:10px;
        }
        .array_title{
            display:inline-block;
            margin-left:10px;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
        }
        .array_listen{
            display:flex;
            align-items:center;
            margin-bottom:15px;
            border:2px solid brown;
            width:300px;
            margin-left:10px;
        }
        .close_passwort_anzeigen{
        background-color:transparent;
            border:none;
        }
        .passwort_anzeigen_div{
            display:none;
        }
        .passwort_von_liste {
        position: fixed;
        top: 65px;   
        left: 0;     
        height: 100%;
        width: 100%;
        background-color: #FFF8E1;
        display: none;
        z-index: 20;  
}
        .close_picture_ii{
            height:40px;
        }
        .close_ii{
            background-color:transparent;
            border:none;
        }
        .closs_div_ii{
            display:flex;
            justify-content: flex-end;
        }
        .div_ii{
            display:flex;
            flex-direction:column;
            justify-content: center;
            align-items: center; 
            
        }
        .name_ii{
            
        }
        .form_aa{
            display:none;
            height:100%;
            width:100%;
            position:fixed;
            top:65px;
            left:0;
            background-color: #FFF8E1;
        }
        .div_aa{
            display:flex;
            flex-direction:column;
            justify-content: center;
            align-items: center;
        }
        .div_button_aa{
            display:flex;
            flex-direction:column;
            justify-content: flex-end;
            align-items: flex-end;
        }
        .name_aa,
        .passwort_aa{
            font-weight:bold;
            font-size:20px;
        }
        .input_name_aa,
        .input_passwort_aa{
            height:20px;
        }
        .submit_aa{
            height:30px;
            background-color:white;
            border:none;
            margin-top:20%;
            font-weight:bold;
            font-size:20px;
        }
        .name_ii{
            font-size:20px;
        }
        .werte_ii{
            font-size:20px;
            font-weight:bold;
        }


    </style>
</head>
<body class="body_connect">
    <div class="oberer_balken">
            <button class="back_homepage_button" onclick="back()">
            <img src="close-150192_1280.png" class="close" >
            </button>
            <p class="title">SmartList</p>
    </div>
    <div class="button_verbinden">
        <button class="passwort_anzeigen" onclick="passwort_anzeigen()">Code anzeigen</button>
        <button class="passwort_verbinden" onclick="passwort_verbinden()">Code eingeben</button>
    </div>
    <div class="passwort_anzeigen_div">
    </div>
    <div class="passwort_von_liste">
        <p>Hallo</p>
    </div>
    <form class="form_aa" name="form_aa" action="connect.php" method="POST" onsubmit=" return check_aa()">
        <div class="div_button_aa">
                <button class="close_passwort_anzeigen" type="button" onclick="close_passwort_eingeben()">
                <img src="close-150192_1280.png" class="close_passwort_picture" >
                </button>
        </div>
        <div class="div_aa">
            <p class="name_aa">
                Benutzername des Erstellers:
            </p>
            <input class="input_name_aa" name="input_name_aa">
            <p class="passwort_aa">
                Passwort der Einkaufsliste:
            </p>
            <input class="input_passwort_aa" name="input_passwort_aa">
            <input type="submit" class="submit_aa" value="Anfrage abschicken">
        </div>

    </form>
    
    
    <script>
        let error_aa="<?php echo $error_aa ?>"
        console.log(error_aa)
        if (error_aa){
            alert(error_aa)
        }




        function check_aa(){
            let input_name_aa=document.querySelector(".input_name_aa").value
            let input_passwort_aa=document.querySelector(".input_passwort_aa").value
            if (input_name_aa==="" || input_passwort_aa===""){
                alert("Bitte füllen sie beide Felder aus")
                return false;

            }else{
                return true;
            }

        }


        function passwort_verbinden(){
            let hidden=document.querySelector(".passwort_anzeigen_div")
            hidden.style.display="none"
            let passwort_verbinden_aa=document.querySelector(".form_aa")
            passwort_verbinden_aa.style.display="block";
            

        }
        function close_passwort_eingeben(){
            let passwort_verbinden_aa=document.querySelector(".form_aa")
            passwort_verbinden_aa.style.display="none";
        }










        let array_listen=<?php echo $array_listen ?>;
        console.log(array_listen)
        function passwort_anzeigen(){
            let hidden=document.querySelector(".passwort_anzeigen_div")

            

            let array_js=''
            let anzahl_listen=Number("<?php echo $anzahl ?>")
            console.log(anzahl_listen)
            let array_zahl=0
            while(array_zahl<anzahl_listen){
                
                let html=""
                if (array_listen[array_zahl].Typ==="Online"){
                html=`
                <div class="array_listen" onclick="code_in_input(${array_listen[array_zahl].Code})">
                <img src="${array_listen[array_zahl].Bild}" class="array_picture">
                <p class="array_title">${array_listen[array_zahl].Listenname}</p>
                <input type="hidden" class="hidden_input_wert">
                </div>`
                array_js+=html;
                array_zahl+=1;
                console.log(array_zahl)
            }else{
                array_zahl+=1;
            }
        }
        hidden.innerHTML=`
            <button class="close_passwort_anzeigen" onclick="close_passwort_anzeigen()">
            <img src="close-150192_1280.png" class="close_passwort_picture" >
            </button>
            <p class="passwort_anzeigen_p">Bitte wählen sie eine Liste aus:</p>
            ${array_js}
            `
            hidden.style.display="block"
            
    }
    function close_passwort_anzeigen(){
        let hidden=document.querySelector(".passwort_anzeigen_div")
        hidden.style.display="none"
    }
    /* div von passwort und user anzeigen */
    function code_in_input(passwort){
        let user="<?php echo $user ?>"
        let passwort_von_liste=document.querySelector(".passwort_von_liste")
        passwort_von_liste.innerHTML=`
        <div class="closs_div_ii">
            <button class="close_ii" onclick="close_function_ii()">
            <img src="close-150192_1280.png" class="close_picture_ii">
            </button>
        </div>

        <div class="div_ii">
            <p class="name_ii">Name des Erstellers:</p>
            <p class="werte_ii">${user}</p>
            <p class="name_ii">Passwort der Einkaufsliste:</p>
            <p class="werte_ii">${passwort}</p>
        </div>
        `



        passwort_von_liste.style.display="block"
        let hidden=document.querySelector(".passwort_anzeigen_div")
        hidden.style.display="none"

        


    }
    function close_function_ii(){
        let passwort_von_liste=document.querySelector(".passwort_von_liste")
        passwort_von_liste.style.display="none"
    }

        function back(){
             window.location.href = 'https://thilo.saams.de/homepage.php'
        }
    </script>
</body>
</html>