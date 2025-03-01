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
if (empty($_SESSION['id'])) {
    header("Location: homepage.php");
   exit();
}
$id_liste=$_SESSION['id'];
if (!$conn) {
    echo "Problem bei der Verbindung";
}
if(empty($_SESSION['type_of_user_home'])){
    header("Location: homepage.php");
   exit();
}
if (isset($_SESSION['benutzername'])) {
    $user=$_SESSION['benutzername'];
 }else {
    header("Location: login.php");
             exit();
 }
$type_of_user_home=$_SESSION['type_of_user_home'];

if ($type_of_user_home=='ersteller'){
    $sql="SELECT User FROM `Verknuepfungen` WHERE ID=$id_liste";
    $answer=mysqli_query($conn, $sql);
    $answer_qq=[];
    while ($row=mysqli_fetch_assoc($answer)){
        $answer_qq[]=$row;
    }
}
$answer_qq=json_encode($answer_qq);

    



if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST["text_input"]) && isset($_POST["wichtigkeit_hidden"])){
        $inhalt_text=$_POST["text_input"];
        $wichtigkeit_text=$_POST["wichtigkeit_hidden"];
        $sql="INSERT INTO `Aufgaben` (IDListe,Inhalt,Wichtigkeit,Ersteller) VALUES ('$id_liste','$inhalt_text','$wichtigkeit_text','$user')";
        mysqli_query($conn, $sql);
        header("Location: einkaufslisten.php");
        exit();

    }
}
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    if (isset($_GET["id_selector_input"])&& $_GET["id_selector_input"] !== ""){
        $id_selector_input=$_GET["id_selector_input"];
        $sql="DELETE from `Aufgaben` WHERE IDValue=$id_selector_input";
        mysqli_query($conn, $sql);
        header("Location: einkaufslisten.php");
        exit();

    }elseif(isset($_GET["deleted_user"]) && $_GET["deleted_user"] !== ""){
        $delete_user=$_GET["deleted_user"];
        $sql="DELETE from `Verknuepfungen` WHERE ID=$id_liste AND `User`='$delete_user'";
        mysqli_query($conn, $sql);
        header("Location: einkaufslisten.php");
        exit();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    if (isset($_GET["remove_lokaler_ii"]) && $_GET["remove_lokaler_ii"]=="yes"){
        $sql="DELETE FROM `Einkaufslistennamen` WHERE ID=$id_liste";
        mysqli_query($conn, $sql);
        $sql="DELETE FROM `Aufgaben` WHERE IDListe=$id_liste";
        mysqli_query($conn, $sql);
        header("Location: homepage.php");
        exit();
    }elseif(isset($_GET["remove_verbundeden_ii"]) && $_GET["remove_verbundeden_ii"]=="yes"){
        $sql="DELETE FROM `Verknuepfungen` WHERE ID=$id_liste AND `User`='$user'";
        mysqli_query($conn, $sql);
        header("Location: homepage.php");
        exit();
    }elseif(isset($_GET["remove_ersteller_ii"]) && $_GET["remove_ersteller_ii"]=="yes"){
        $sql="SELECT Code FROM `Einkaufslistennamen` WHERE ID=$id_liste";
        $delete_ersteller=mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($delete_ersteller);
        $delete_ersteller = $row['Code'];
        $sql="DELETE FROM `Anfragen` WHERE Passwort=$delete_ersteller";
        mysqli_query($conn, $sql);
        $sql="DELETE FROM `Einkaufslistennamen` WHERE ID=$id_liste";
        mysqli_query($conn, $sql);
        $sql="DELETE FROM `Aufgaben` WHERE IDListe=$id_liste";
        mysqli_query($conn, $sql);
        $sql="DELETE FROM `Verknuepfungen` WHERE ID=$id_liste";
        mysqli_query($conn, $sql);
        header("Location: homepage.php");
        exit();


    }
}



$sql = "SELECT COUNT(*) AS anzahl FROM `Aufgaben` WHERE IDListe='$id_liste'";
$result = mysqli_query($conn, $sql); 
$row = mysqli_fetch_assoc($result);
$anzahl = $row['anzahl'];
$sql = "SELECT * FROM `Aufgaben` WHERE IDListe='$id_liste'";
$result = mysqli_query($conn, $sql);

$array_listen = [];
while ($row = mysqli_fetch_assoc($result)) {
    $array_listen[] = $row;
}
$array_listen=json_encode($array_listen); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
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
        .back_homepage_button {
            margin-right: auto; 
            border:none;
            background-color:transparent;
        }
        .add_div{
            position:fixed;
            width:100%;
            bottom:0;
            background-color: #FFF8E1;
            z-index: 10;
            display:flex;
            justify-content: center;
            align-items:center;
        }
        .close{
         height:50px;
         background-color: #FFF8E1;
         border:none;
        }
        .add_to_list{
            width:100%;
            background-color: #FFF8E1;
        }
        .add{
            
            font-family: 'Dancing Script', cursive;
            font-size:40px;
            padding:0;
            margin:0;
            background-color: #FFF8E1;
            
        }
        .text_input{
            height:80px;
            width:200px;
            margin-left:10px;
        }
        .close_form_picture{
            height:50px;
         
      }
        .form{
         position:fixed;
         top:100px;
         left:50px;
         background-color:rgb(182, 165, 121);
         border-radius:5%;
         height:500px;
         width:300px;
         display:none;
        }
        .close_form{
            background-color:transparent;
         border:none;
         display:flex;
         margin-left:240px;
        }
        .wichtig{
            margin-left:10px;
        }
        .option1,
        .option2,
        .option3{
            display:inline-block;
            font-size:15px;
            font-weight:bold;
            margin-top:0;
        }
        .option1{
            margin-top:60px;
        }
        .hinzufügen_input{
        margin-top:100px;
         height:30px;
         font-size:20px;
         margin-left:10px;
         border-radius:10%;
         border:none;
         background-color:#FFEAB3;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .array_listen{
            display: flex;
            background-color:#FFEAB3;
            min-height:10px;
            margin-top:10px;

        }
        .wichtigkeit_checkbox{
            
            width:60px;
            display: flex;
            align-items: center;
            border: 2px solid black;
            

        
            
            
        }
        .Sehr_wichtig_div{
            background-color:red;
        }
        .wichtig_div{
            background-color:orange;
        }
        .weniger_wichtig_div{
            background-color:green;
        }
        .text_inhalt_div{
            display:inline-block;
            margin-left:10px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .text_inhalt_p{
            max-width:280px;
        }
        .picture_delete{
            height:30px;
            background-color:transparent;
            border:none;
        }
        .delete_list{
            position:fixed;
            top:5px;
            z-index:20;
            right:0;
            background-color:transparent;
            border:none;
        }
        .hidden_delete{
            position:fixed;
         top:100px;
         left:50px;
         background-color:rgb(182, 165, 121);
         border-radius:5%;
         height:300px;
         width:300px;
         display:none;
        }
        .delete_list_p{
            font-weight:bold;
            font-size:25px;
        }
        .yes_delete,
        .no_delete{
            margin-top:100px;
         height:30px;
         font-size:20px;
         margin-left:10px;
         border-radius:10%;
         border:none;
         background-color:#FFEAB3;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .picture_qq{
            height:50px;
        }
        .close_qq{
            position:fixed;
            background-color:transparent;
            border:none;
            left:80%;
            top:80%;
        }
        .users_delete_picture{
            height:50px;
        }
        .users_name_p{
            color:white;
            margin-left:10px;
            font-weight:bold;
            font-size:15px;
        }
        .menue_of_users_qq{
            display:none;
        }
        .users_delete_div{
            display:inline-block;
        }
        .users_name_div{
            display:inline-block;
        }
        .users_overview_div{
            width:95%;
            display:flex;
            align-items:center;
            padding-left:10px;
            border:3px solid white;
            margin-top:5px;
        }
        .menue_of_users_qq{
            position:fixed;
            top:80px;
            background-color:grey;
            width:80%;
            min-height:50px;
        }
        .picture_uu{
            height:50px;
        }
        .button_uu{
            background-color:transparent;
            border:none;
        }
        .div_uu{
            position:fixed;
            background-color:transparent;
            border:none;
            left:80%;
            top:70%;
        }
        .delete_lokaler{
            display:none;
            
        }
        .delete_lokaler_div{
            position:fixed;
            background-color:grey;
            width:80%;
            box-shadow: 5px 5px 7px 0px white;
            top:80px;
            padding-left:10px;

        }
        .lokaler_ja_xx,
        .lokaler_nein_xx{
            background-color:green;
            height:30px;
            width:40%;
            display:inline-block;
            font-weight:bold;

        }
        .lokaler_nein_xx{
            background-color:red;
        }
        .delete_verbundener{
            display:none;
        }
        .delete_verbundener_div{
            position:fixed;
            background-color:grey;
            width:80%;
            box-shadow: 5px 5px 7px 0px white;
            top:80px;
            padding-left:10px;
        }
        .delete_ersteller{
            display:none;
        }

    </style>
</head>
<body style="padding-top:60px; padding-bottom:60px;background-color:black;">

    <div class="oberer_balken">
        <button class="back_homepage_button" onclick="back()">
        <img src="close-150192_1280.png" class="close" >
        </button>
        <p class="title">SmartList</p>
    </div>

    <form class="form" action="einkaufslisten.php" method="POST" onsubmit=" return onsubmit_check()"></form>
    <div class="inhalte_liste">
    </div>

    <div class="add_div">
    <button class="add_to_list"><p class="add" onclick="add()">Hinzufügen</p></button>
    </div>
    <form class="form_hidden_get" action="einkaufslisten.php" method="GET">
        <input type="hidden" class="id_selector_input" name="id_selector_input">
        <input type="hidden" class="delete_yes_no" name="delete_yes_no">
        <input type="hidden" class="deleted_user" name="deleted_user">
    </form>
    <div type="hidden" class="hidden_delete">
    </div>
    <div class="menue_of_users_button" type="hidden">
    </div>
    <div class="menue_of_users_qq">
    </div>
    <div class="delete_entire_list_xx" >
    </div>
    <form class="delete_lokaler"  action="einkaufslisten.php" method="GET" onsubmit="submit_delete_xx('lokaler')">
        <div class="delete_lokaler_div">
            <div class="delete_lokaler_p">
                <p class="lokaler_p_xx">Möchten sie wirklich diese lokale Liste löschen?</p>
            </div>

            <div class="buttons_xx">
                <button class="lokaler_ja_xx" onclick="document.querySelector('.remove_lokaler_ii').value='yes'">Löschen</button>
                <button class="lokaler_nein_xx" onclick="document.querySelector('.remove_lokaler_ii').value='no'">Abbrechen</button>
                <input type="hidden" class="remove_lokaler_ii" name="remove_lokaler_ii">
            </div>
        </div>
    </form>


    <form class="delete_verbundener"  action="einkaufslisten.php" method="GET" >
        <div class="delete_lokaler_div">
            <div class="delete_lokaler_p">
                <p class="lokaler_p_xx">Möchten sie wirklich diese Liste verlassen?</p>
            </div>

            <div class="buttons_xx">
                <button class="lokaler_ja_xx" onclick="document.querySelector('.remove_verbundeden_ii').value='yes'">Verlassen</button>
                <button class="lokaler_nein_xx" onclick="document.querySelector('.remove_verbundeden_ii').value='no'">Abbrechen</button>
                <input type="hidden" name="remove_verbundeden_ii" class="remove_verbundeden_ii">
            </div>
        </div>
    </form>


    <form class="delete_ersteller"  action="einkaufslisten.php" method="GET" >
        <div class="delete_lokaler_div">
            <div class="delete_lokaler_p">
                <p class="lokaler_p_xx">Möchten sie wirklich diese online Liste löschen</p>
            </div>

            <div class="buttons_xx" action="einkaufslisten.php" method="GET">
                <button class="lokaler_ja_xx" onclick="document.querySelector('.remove_ersteller_ii').value='yes'">Löschen</button>
                <button class="lokaler_nein_xx" onclick="document.querySelector('.remove_ersteller_ii').value='no'">Abbrechen</button>
                <input type="hidden" name="remove_ersteller_ii" class="remove_ersteller_ii">
            </div>
        </div>
    </form>
    


    
    <script>








        
        let type_of_user_home="<?php echo $type_of_user_home ?>";
        let answer_qq=null;
        if (type_of_user_home==="ersteller"){
            answer_qq=<?php echo $answer_qq ?>;
            console.log(answer_qq)
        }
        let delete_entire_list_xx=document.querySelector(".delete_entire_list_xx")
        if(type_of_user_home==="lokaler"){
            delete_entire_list_xx.innerHTML=`
            <div class="div_uu" onclick="delete_lokaler()">
            <button class="button_uu">
            <img src="trash-197586_1280.png" class="picture_uu">
            </button>
            </div>`
        }else if(type_of_user_home==="verbundener"){
            delete_entire_list_xx.innerHTML=`
            <div class="div_uu" onclick="delete_verbundener()">
            <button class="button_uu">
            <img src="trash-197586_1280.png" class="picture_uu">
            </button>
            </div>`
        }else if (type_of_user_home==="ersteller"){
            delete_entire_list_xx.innerHTML=`
            <div class="div_uu" onclick="delete_ersteller()">
            <button class="button_uu">
            <img src="trash-197586_1280.png" class="picture_uu">
            </button>
            </div>`
        }
        function delete_lokaler(){
            let delete_lokaler_xx=document.querySelector(".delete_lokaler")
            delete_lokaler_xx.style.display="block"

        }
        function delete_verbundener(){
            let delete_lokaler_xx=document.querySelector(".delete_verbundener")
            delete_lokaler_xx.style.display="block"

        }
        function delete_ersteller(){
            let delete_lokaler_xx=document.querySelector(".delete_ersteller")
            delete_lokaler_xx.style.display="block"

        }

        
        

    
    if (type_of_user_home==="ersteller"){
        let menue_of_users=document.querySelector(".menue_of_users_button")
        menue_of_users.innerHTML=`
        <button class="close_qq" onclick="menue_users_open()">
        <img src="gear-wheel-304395_1280.png" class="picture_qq">
        </button>`
        menue_of_users.style.display="block"
    }
    function menue_users_open(){
        let menue_of_users_qq=document.querySelector(".menue_of_users_qq")
        if (menue_of_users_qq.style.display!="none"){
            menue_of_users_qq.style.display="none"

        }else{
            
            let array_qq=''
            answer_qq_length=answer_qq.length
            let zahl_qq=0
            while (zahl_qq<answer_qq_length){
                let html_qq=`
                <div class="users_overview_div">
                    <div class="users_delete_div">
                        <button class="users_delete_button" onclick="delete_user_qq('${answer_qq[zahl_qq].User}')">
                            <img src="trash-197586_1280.png" class="users_delete_picture">
                        </button>
                    </div>
                    <div class="users_name_div">
                        <p class="users_name_p">${answer_qq[zahl_qq].User}</p>
                    </div>
                </div>`
                array_qq+=html_qq
                zahl_qq++
            }    
            if (array_qq!=''){
                menue_of_users_qq.innerHTML=array_qq

            }else{
                menue_of_users_qq.innerHTML=`
                <p class="users_name_p">Keine Benutzer sind mit ihrer Liste verbunden</p>`
            }
            
            menue_of_users_qq.style.display="block"
            console.log(array_qq)
        }
        
    }
    function delete_user_qq(user_delete){
        let deleter_input=document.querySelector(".deleted_user")
        deleter_input.value=user_delete
        let form_delete=document.querySelector(".form_hidden_get")
        form_delete.submit()

    }

    
      let array_listen=<?php echo $array_listen ?>;
      console.log(array_listen)
      let array_js1='';
      let array_js2='';
      let array_js3='';
      let anzahl_listen=Number("<?php echo $anzahl ?>")
      let array_zahl=0
      while(array_zahl<anzahl_listen){
         let html=""
         if (array_listen[array_zahl].Wichtigkeit==="Sehr wichtig!"){
         html=`
         <div class="array_listen" >
            <div class="Sehr_wichtig_div wichtigkeit_checkbox" onclick="delete_value(${array_listen[array_zahl].IDValue})">
            </div>
            <div class="text_inhalt_div">
                <p class="text_inhalt_p">${array_listen[array_zahl].Inhalt}</p>
            </div>
         </div>`
         array_js1+=html;
         }else if(array_listen[array_zahl].Wichtigkeit==="Wichtig"){
            html=`
         <div class="array_listen" >
            <div class="wichtig_div wichtigkeit_checkbox" onclick="delete_value(${array_listen[array_zahl].IDValue})">
            </div>
            <div class="text_inhalt_div">
                <p class="text_inhalt_p">${array_listen[array_zahl].Inhalt}</p>
            </div>
         </div>`
         array_js2+=html;
         }else{
            html=`
            <div class="array_listen">
                <div class="weniger_wichtig_div wichtigkeit_checkbox" onclick="delete_value(${array_listen[array_zahl].IDValue})">
                </div>
                <div class="text_inhalt_div">
                <p class="text_inhalt_p">${array_listen[array_zahl].Inhalt}</p>
                </div>
            </div>`
            array_js3+=html;
         }

         
         array_zahl+=1;
         let inhalte_liste=document.querySelector(".inhalte_liste")
         inhalte_liste.innerHTML=array_js1 + array_js2 + array_js3
      }

      function delete_value(id){
        let id_selector=document.querySelector(".id_selector_input")
        id_selector.value=id
        let form=document.querySelector(".form_hidden_get")
        form.submit()

      }



        function back(){
             window.location.href = 'https://thilo.saams.de/homepage.php'
        }
        
        function add(){
            let form=document.querySelector(".form")
            form.innerHTML=`
            <button type="button" class="close_form" onclick="close_form()">
            <img src="close-150192_1280.png" class="close_form_picture" >
            </button><br>
            <textarea class="text_input" name="text_input" placeholder="Inhalt..."></textarea>
            <div>
                <label class="wichtig">
                    <input type="radio" name="wichtigkeit" class="option" onclick="setwichtigkeit('Sehr wichtig!')"><p class="option1">Sehr wichtig!</p>
                </label><br>
                <label class="wichtig">
                    <input type="radio" name="wichtigkeit" class="option" onclick="setwichtigkeit('Wichtig')"><p class="option2">Wichtig</p>
                </label><br>
                <label class="wichtig">
                    <input type="radio" name="wichtigkeit" class="option"  onclick="setwichtigkeit('Weniger wichtig')"><p class="option3">Weniger wichtig</p>
                </label><br>
            </div>
            <input type="submit" value="Hinzufügen" class="hinzufügen_input">
            <input type="hidden" name="wichtigkeit_hidden" class="wichtigkeit_hidden">
            

            `
            document.querySelector(".form").style.display='block'
            let menue_of_users_qq=document.querySelector(".menue_of_users_qq")
            menue_of_users_qq.style.display="none"
        }
        function close_form(){
            document.querySelector(".form").style.display='none'

        }
        function setwichtigkeit(wichtig){
            let wichtigkeit_hidden=document.querySelector(".wichtigkeit_hidden")
            wichtigkeit_hidden.value=wichtig
            console.log(wichtigkeit_hidden.value)
        }
        function onsubmit_check(){
            if (document.querySelector(".wichtigkeit_hidden").value!="" && document.querySelector(".text_input").value!=""){
                return true
            }else{
                alert("Bitte füllen sie alles aus")
                return false
            }
        }

        
    </script>
</body>
</html>