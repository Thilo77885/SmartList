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
if (isset($_SESSION['benutzername'])) {
    $user=$_SESSION['benutzername'];
 }else {
    header("Location: login.php");
             exit();
 }
if (isset($_SESSION['Passwort_Benutzer'])){
    $password_output=$_SESSION['Passwort_Benutzer'];

}else{
    header("Location: login.php");
             exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["konto_qq"]) && $_POST["konto_qq"]!==""){
        $button=$_POST["konto_qq"];
        if ($button=="benutzer"){
            $newbenutzername=$_POST["benutzer_change"];
            echo $newbenutzername;
            $sql="UPDATE `Konto` SET Benutzername='$newbenutzername' WHERE Benutzername='$user'";
            mysqli_query($conn, $sql);

            $sql="UPDATE `Einkaufslistennamen` SET Benutzername='$newbenutzername' WHERE Benutzername='$user'";
            mysqli_query($conn, $sql);

            $sql="UPDATE `Verknuepfungen` SET `User`='$newbenutzername' WHERE `User`='$user'";
            mysqli_query($conn, $sql);

            $sql="UPDATE `Anfragen` SET Ersteller='$newbenutzername' WHERE Ersteller='$user'";
            mysqli_query($conn, $sql);

            $sql="UPDATE `Anfragen` SET Absender='$newbenutzername' WHERE Absender='$user'";
            mysqli_query($conn, $sql);


            header("Location: login.php");
            exit();

        }elseif($button=="passwort"){
            $newpassword=$_POST["password_change"];
            $gehashterWert = password_hash($newpassword, PASSWORD_DEFAULT);
            $sql="UPDATE `Konto` SET Passwort='$gehashterWert' WHERE Benutzername='$user'";
            mysqli_query($conn, $sql);
            header("Location: login.php");
            exit();

        }elseif($button=="delete"){
            $sql="DELETE FROM `Konto` WHERE Benutzername='$user'";
            mysqli_query($conn, $sql);

            $sql="DELETE FROM `Einkaufslistennamen` WHERE Benutzername='$user'";
            mysqli_query($conn, $sql);

            $sql="DELETE FROM `Verknuepfungen` WHERE `User`='$user'";
            mysqli_query($conn, $sql);

            $sql="DELETE FROM `Anfragen` WHERE Ersteller='$user'";
            mysqli_query($conn, $sql);

            $sql="DELETE FROM `Anfragen` WHERE Absender='$user'";
            mysqli_query($conn, $sql);

            $sql="DELETE FROM `Aufgaben` WHERE Ersteller='$user'";
            mysqli_query($conn, $sql);
            
            header("Location: login.php");
            exit();



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
        margin: 0;
        padding: 0;
        }
        .body{
            background-color:grey;
            height:100%;
        }
        .close{
            height:50px;
        }
        .back_homepage_button{
            background-color:transparent;
            border:none;
        }
        .button{
            background-color:#FFF8E1;
            border:none;
            display:block;
            width:300px;
            height:100px;
            margin-top:10px;
            font-size:20px;
            font-weight:bold;
        }
        .options{
            display:flex;
            height:100%;
            width:100%;
            justify-content:center;
            align-items:center;
            flex-direction:column;
            
        }
        .form_post{
            display:none;
            background-color:#FFF8E1;
            position:fixed;
            top:60px;
            left:10px;
            right:10px;
            border:3px solid black;
            border-radius:5%;
            

            
            
        }
        .flex{
            display:flex;
            flex-direction:column;
            width:100%;
            height:400px;
            align-items:center;
            
        
        }
        .verify_p{
            font-size:20px;
        }
        .submit_login{
            margin-top:100px;
            border:none;
            height:50px;
            width:150px;
            font-size:20px;

        }
        .form_typ_value{
            display:none;
            position:fixed;
            top:80px;
            background-color:#FFF8E1;
            width:100%;
            border:5px solid black;
            border-radius:5%;
        }
        .seperate{
            display:flex;
            height:400px;
            width:100%;
            justify-content:center;
            flex-direction:column;
            align-items:center;
            
        }
        .change_benutzer,
        .change_password{
            height:50px;
            font-weight:bold;
            font-size:20px;
            margin-top:40px;
        }
        .delete_konto{
            background-color:red;
            font-weight:bold;
            margin-top:30px;
            height:100px;
            width:200px;
            font-size:30px;
            border-radius:5%;

        }
        
        
    </style>
</head>
<body class="body">
    
        <button class="back_homepage_button" onclick="window.location.href = 'https://thilo.saams.de/homepage.php';">
        <img src="close-150192_1280.png" class="close" >
        </button>
    <div class="options">
        <button class="button" onclick="login('Benutzername')">Benutzername ändern</button>
        <button class="button" onclick="login('Passwort')">Passwort ändern</button>
        <button class="button" onclick="login('loeschen')">Konto löschen</button>
    </div>
    <div class="form_post">
    
    </div>
    <form class="form_typ_value" action="profil.php" method="POST">
    </form>
    <script>
        let aktion=""
        function login(typ){
            let form_typ_value=document.querySelector(".form_typ_value")
            form_typ_value.style.display="none"
            let form_post=document.querySelector(".form_post")
            if (typ==="Benutzername"){
                aktion="Benutzername ändern"
            }else if(typ==="Passwort"){
                aktion="Passwort ändern"
            }else{
                aktion="Konto löschen"
                
            }
            form_post.innerHTML=`
                    
                    <button type="button" onclick="close_log_in()" class="back_homepage_button">
                    <img src="close-150192_1280.png" class="close" >
                    </button>
                    <div class="flex">
                    <p class="verify_p">Bitte verifizieren sie sich bevor sie ihr ${aktion}:</p>
                    <p>Benutzername:</p>
                    <input class="benutzername" name="benutzername">
                    <p>Passwort:</p>
                    <input class="passwort" type="password" name="passwort">
                    <button onclick="check_submit()" class="submit_login">Bestätigen</button>
                    </div>
                `
            form_post.style.display="block";

            
        }
        function check_submit(){
            let user=document.querySelector(".benutzername").value
            let password=document.querySelector(".passwort").value

            if (user==="" || password===""){
                    alert("Bitte füllen sie die Felder aus!")
            }else{
                
                let user_data="<?php echo $user ?>";
                let password_data="<?php echo $password_output ?>";
            if(user===user_data && password===password_data){
                let form_typ_value=document.querySelector(".form_typ_value")
                if (aktion==="Benutzername ändern"){
                    form_typ_value.innerHTML=`
                    <button type="button" onclick="close_delete()" class="back_homepage_button">
                    <img src="close-150192_1280.png" class="close" >
                    </button>
                    <div class="seperate">
                    <p class="change_p">Neuer Benutzername:</p>
                    <input  class="benutzer_change" name="benutzer_change">
                    <p class="change_p">Neuen Benutzername bestätigen:</p>
                    <input class="benutzer_change_verify">
                    <button class="change_benutzer" type="button" onclick="benutzer_change_onsubmit()">Benutzername ändern</button>
                    </div>
                    <input type="hidden" name="konto_qq" class="konto_qq">
                    `
                }else if(aktion==="Passwort ändern"){
                    form_typ_value.innerHTML=`
                    <button type="button" onclick="close_delete()" class="back_homepage_button">
                    <img src="close-150192_1280.png" class="close" >
                    </button>
                    <div class="seperate">
                    <p class="change_p">Neues Passwort:</p>
                    <input type="password" class="password_change" name="password_change">
                    <p class="change_p">Neues Passwort bestätigen:</p>
                    <input type="password" class="password_change_verify">
                    <button class="change_password" type="button" onclick="password_change_onsubmit()">Passwort ändern</button>
                    <input type="hidden" name="konto_qq" class="konto_qq">
                    </div>
                    `
                }else{
                    form_typ_value.innerHTML=`
                    <button type="button" onclick="close_delete()" class="back_homepage_button">
                    <img src="close-150192_1280.png" class="close" >
                    </button>
                    <div class="seperate">
                    <button class="delete_konto" onclick="absenden_delete()">
                    Konto löschen
                    </button>
                    </div>
                    <input type="hidden" name="konto_qq" class="konto_qq">
                    `
                }
                form_typ_value.style.display="block"
                let form_post=document.querySelector(".form_post")
                form_post.style.display="none"
                
                
            }else{
                alert("Falsche Anmeldedaten")
                document.querySelector(".benutzername").value=""
                document.querySelector(".passwort").value=""

            }
                
            }
        }
        function close_log_in(){
            let form_post=document.querySelector(".form_post")
            form_post.style.display="none";
        }
        function close_delete(){
            let form_typ_value=document.querySelector(".form_typ_value")
            form_typ_value.style.display="none";
        }
        function absenden_delete(){
            let input=document.querySelector(".konto_qq")
            input.value="delete"
            let form=document.querySelector(".form_typ_value")
            form.submit()
        }
        function password_change_onsubmit(){
            let password_change=document.querySelector(".password_change")
            let password_change_verify=document.querySelector(".password_change_verify")
            if (password_change.value==="" || password_change_verify.value===""){
                alert("Bitte füllen sie beide Felder aus")
                password_change.value=""
                password_change_verify.value=""
            }else if(password_change.value===password_change_verify.value){
                let form=document.querySelector(".form_typ_value")
                let input=document.querySelector(".konto_qq")
                input.value="passwort"
                form.submit()

            }else{
                alert("Bitte achten sie darauf in beiden Feldern das gleiche Passwort einzugeben")
                password_change.value=""
                password_change_verify.value=""
            }
        }
        function benutzer_change_onsubmit(){
            let benutzer_change=document.querySelector(".benutzer_change")
            let benutzer_change_verify=document.querySelector(".benutzer_change_verify")
            if (benutzer_change.value==="" || benutzer_change_verify.value===""){
                alert("Bitte füllen sie beide Felder aus")
                benutzer_change.value=""
                benutzer_change_verify.value=""
            }else if(benutzer_change.value===benutzer_change_verify.value){
                let form=document.querySelector(".form_typ_value")
                let input=document.querySelector(".konto_qq")
                input.value="benutzer"
                form.submit()

            }else{
                alert("Bitte achten sie darauf in beiden Feldern das gleiche Passwort einzugeben")
                benutzer_change.value=""
                benutzer_change_verify.value=""
            }
        }
    </script>
</body>
</html>