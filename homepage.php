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
if (!$conn) {
    echo "Problem bei der Verbindung";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST["input_name_list"]) && isset($_POST["selected_image"]) && isset($_POST["selected_option"])){
         if ($_POST["selected_option"]=="Online"){
            $listenname=$_POST["input_name_list"];
            $motiv=$_POST["selected_image"];
            $option=$_POST["selected_option"];
            
            do{
               $zahl = random_int(1000000000, 9999999999);
               $sql="INSERT INTO `Einkaufslistennamen` (Benutzername,Listenname,Bild,Typ,Code) VALUES ('$user','$listenname','$motiv','$option','$zahl')";
               mysqli_query($conn, $sql);
               $error = mysqli_errno($conn);
               if ($error!==1062){
                  header("Location: homepage.php");
                  exit();
                  
               }
            }while(true);
         }else{
         $listenname=$_POST["input_name_list"];
         $motiv=$_POST["selected_image"];
         $option=$_POST["selected_option"];
         $sql="INSERT INTO `Einkaufslistennamen` (Benutzername,Listenname,Bild,Typ) VALUES ('$user','$listenname','$motiv','$option')";
         mysqli_query($conn, $sql);
         header("Location: homepage.php");
         exit();
      }
       };
   };
if ($_SERVER["REQUEST_METHOD"] == "GET"){
   if (isset($_GET["id_of_list"]) && isset($_GET["type_of_user"]) !== ""){
      $id_of_list=$_GET["id_of_list"];
      $type_of_user=$_GET["type_of_user"];
      $_SESSION['type_of_user_home']=$type_of_user;
      $_SESSION['id']=$id_of_list;
      header("Location: einkaufslisten.php");
      exit();
   }
}
$sql = "SELECT COUNT(*) AS anzahl FROM `Einkaufslistennamen` WHERE Benutzername='$user'";
$result = mysqli_query($conn, $sql); 
$row = mysqli_fetch_assoc($result);
$anzahl = $row['anzahl'];
$_SESSION['Anzahl_count']=$anzahl;
$sql = "SELECT * FROM `Einkaufslistennamen` WHERE Benutzername='$user'";
$result = mysqli_query($conn, $sql);

$array_listen = [];
while ($row = mysqli_fetch_assoc($result)) {
    $array_listen[] = $row;
}
$array_listen=json_encode($array_listen);

$_SESSION['Passwort_Liste']=$array_listen;

$sql="SELECT * FROM `Verknuepfungen` WHERE User='$user'";
$result_aa = mysqli_query($conn, $sql);
$array_listen_aa = [];
while ($row = mysqli_fetch_assoc($result_aa)) {
   $array_listen_aa[] = $row;
}
$length = count($array_listen_aa);
$zahl_aa=0;
$online_aa=[];
while ($zahl_aa<$length){
   $sql="SELECT * FROM `Einkaufslistennamen` WHERE ID='{$array_listen_aa[$zahl_aa]['ID']}'";
   $result_aa = mysqli_query($conn, $sql);
   $row = mysqli_fetch_assoc($result_aa);
   if ($row['Benutzername']==$user){
      $zahl_aa++;
   }else{
      $online_aa[]=$row;
      $zahl_aa++;
   }
   

}
$online_aa=json_encode($online_aa);








$sql="Select Aufrufe from `Aufrufe`";
$zahlaufrufe = mysqli_query($conn, $sql);
$listeaufrufe = mysqli_fetch_assoc($zahlaufrufe);
$zahlaufrufe=$listeaufrufe['Aufrufe'];
$zahlaufrufe+=1;
$sql="UPDATE Aufrufe SET Aufrufe =$zahlaufrufe";
mysqli_query($conn, $sql);
$_SESSION['aufrufe']=$zahlaufrufe;


mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <style>
      .picture_menu{
         height:30px;
      }
      .button_menu,.headline{
         display:inline;
         margin-left:5px;
         text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
      }
      .headline{
            @import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap');
            font-family: 'Dancing Script', cursive;
            font-size: 48px;
            color: #4b2c20; 
            text-align: center;
            letter-spacing: 2px; 
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            font-weight: bold;
            padding-left:10%;
      }
      .balken_oben{
         position:fixed;
         background-color:#FFF8E1;
         width:100%;
         top:0;
         right:0;
         left:0;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
         z-index:10;
         height:60px;
      }
      
      
      .small-image {
         width: 50px; 
         height: 50px;
      }
      .image-button {
         border: none;
         padding: 0;
         background: none;
         cursor: pointer;
         margin:10px;
      }
      .selected{
         border:4px solid rgba(89, 81, 60, 0.9);
         border-radius:10%;
      }
      .einkaufslisten_add_hidden{
         position:fixed;
         top:100px;
         left:50px;
         background-color:rgb(182, 165, 121);
         border-radius:5%;
         height:500px;
         width:300px;
         display:none;
      }
      .close{
         height:50px;
         background-color:transparent;
         padding:0;
      }
      .close_button{
         background-color:transparent;
         border:none;
         display:flex;
         margin-left:240px;
      }
      .generieren_submit{
         margin-top:10px;
         margin-left:50px;
         color:white;
         background-color:black;
         color: #f4f1e1;
         border:none;
         border-radius:5%;
         font-size:20px;
      }
      .button_menu_div{
         display:none;
         position:fixed;
         top:60px;
         width:250px;
         background-color:#FFF8E1;
         left:0;
         height:80%;
         border-radius:1%;
      }
      .aufrufe_menue,
      .connect_menue,
      .system_menue,
      .logout_menue{
         display:block;
         width:200px;
         font-size:20px;
         border-color:#FFEAB3;
         text-align:left;
         margin-top:5px;
         height:30px;
         margin-left:3px;
         border-radius:10%;
         background-color:#FFEAB3;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
         
         
      }
      .close_menue{
         background-color:#FFEAB3;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      }
      .aufrufe_menue{
         margin-top:380px;
      }
      .array_listen_div{
         margin-top:80px;
         width:400px;
      }
      .einkaufslisten,
      .array_listen{
         width:180px;
         height:180px;
         display:inline-block;
         padding:5px;
      }
      .array_listen{
         background-color:#FFF8E1;
         border: 2px solid blue;
         border-radius:10%;
         margin-top:5px;
      }
      .einkaufslisten{
         background-color:#FFF8E1;
         border: 2px solid black;
         border-radius:10%;
         margin-top:5px;
      }
      .picture_add{
         height:140px;
         width:auto;
      }
      .array_picture{
         height:120px;
         width:auto;
         margin-left:30px;
      }
      .einkaufslistenbutton{
         height:180px;
         width:180px;
         background-color:#FFF8E1;
         border:none;
      }
      .name_inputs_form{
         margin-left:10px;
         font-size:20px;
      }
      .input_name_list{
         margin-left:10px;
         height:20px;
         width:200px;
      }
      .option1,
      .option2{
         margin-left:10px;
         margin-top:10px;
      }
      .option1{
         margin-top:30px;
      }
      .generate_list_form{
         margin-top:100px;
         height:30px;
         font-size:20px;
         margin-left:10px;
         border-radius:10%;
         border:none;
         background-color:#FFEAB3;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      }
      .array_title{
         text-align:center;
         font-weight:bold;
      }
      .online{
         border-color:red;
      }
      .verbunden{
         border-color:yellow;
      }
      .body_homepage{
         background-color:black;
      }

   </style>
</head>
<body class="body_homepage">
   <div class="balken_oben">
      
      <button class="button_menu" onclick="button_menu()">
         <img src="th.jpg" class="picture_menu">
      </button>
      <p class="headline">SmartList</p>
      
   </div>
   
   <form class="einkaufslisten_add_hidden"  onsubmit="return generieren_check()"  method="POST" action="homepage.php">
   </form>
   <div class="button_menu_div">
   </div>
   <div class="array_listen_div">
      <div class="einkaufslisten" onclick="einkaufslisten_add()">
         <button class="einkaufslistenbutton">
            <img src="pngegg.png" class="picture_add">
         </button>
      </div>
   </div>
   <form class="id_input" id="id_input" type="hidden" method="GET" action="homepage.php">
   <input type="hidden" name="id_of_list" id="id_of_list">
   <input type="hidden" name="type_of_user" id="type_of_user">
   </form>

   <script>







      let array_js='';
      let aa=<?php echo $online_aa ?>;
      console.log(aa)
      aa_length=aa.length
      let zahl_long_aa=0;
      while(zahl_long_aa<aa_length){
         let html_online=""
         html_online=`
         <div class="array_listen online verbunden" onclick="finduser(${aa[zahl_long_aa].ID},'verbundener')">
         <img src="${aa[zahl_long_aa].Bild}" class="array_picture">
         <p class="array_title">${aa[zahl_long_aa].Listenname}</p>
         </div>
         `
         array_js+=html_online;
         zahl_long_aa++
      }
      




      let zahlaufrufe="<?php echo $zahlaufrufe ?>"
      let array_listen=<?php echo $array_listen ?>;
      console.log(array_listen)
      
      let anzahl_listen=Number("<?php echo $anzahl ?>")
      let array_zahl=0
      while(array_zahl<anzahl_listen){
         let html=""
         if (array_listen[array_zahl].Typ==="Online"){
         html=`
         <div class="array_listen online" onclick="finduser(${array_listen[array_zahl].ID},'ersteller')">
         <img src="${array_listen[array_zahl].Bild}" class="array_picture">
         <p class="array_title">${array_listen[array_zahl].Listenname}</p>
         </div>`
         }else{
            html=`
         <div class="array_listen" onclick="finduser(${array_listen[array_zahl].ID},'lokaler')">
         <img src="${array_listen[array_zahl].Bild}" class="array_picture">
         <p class="array_title">${array_listen[array_zahl].Listenname}</p>
         </div>`
         }


         array_js+=html;
         array_zahl+=1;
      }

      let array_div=document.querySelector(".array_listen_div")
      array_div.innerHTML+=array_js

      function finduser(id,typ){
         let idinput=document.querySelector("#id_of_list")
         idinput.value=id
         
         let type_of_user_aaa=document.querySelector("#type_of_user")
         type_of_user_aaa.value=typ
         
         console.log(idinput)
         document.querySelector('.id_input').submit();
         


      }
      function button_menu(){
         let menue_button=document.querySelector(".button_menu_div")
         
         menue_button.innerHTML=`
         <button class="close_menue" onclick="this.parentElement.style.display = 'none'"><img src="close-150192_1280.png" class="close" ></button>
         <button class="aufrufe_menue" onclick="aufrufe()">Aufrufe</button>
         <button class="connect_menue" onclick="website_change()">Online Verbinden</button>
         <button class="system_menue" onclick="window.location.href = 'https://thilo.saams.de/system.php'">System</button>
         <button class="system_menue" onclick="window.location.href = 'https://thilo.saams.de/profil.php'">Profil</button>
         <button class="logout_menue" onclick="logout()">Ausloggen</button>
         `
         menue_button.style.display = 'block';
         

      }
      function website_change(){
         window.location.href = "https://thilo.saams.de/connect.php";
      }
      
      function aufrufe(){
         window.location.href = 'https://thilo.saams.de/aufrufehomepage.php'
      }
      function logout(){
         window.location.href = "https://thilo.saams.de/login.php";
      }
      function einkaufslisten_add(){
         let element = document.querySelector(".einkaufslisten_add_hidden");
         element.innerHTML = `
            
            <button type="button" class="close_button" onclick="close();this.parentElement.style.display = 'none'"><img src="close-150192_1280.png" class="close" ></button>
            <p class="name_inputs_form">Name der Einkaufsliste:</p>
            <input maxlength="10" class="input_name_list" name="input_name_list" placeholder="Eingeben">
            <p class="name_inputs_form" >Wählen sie ein Bild:</p>
            <div class="image-container">
            <button type="button" class="image-button" name="image-button1" onclick="toggleSelection(this,'smiley-39984_1280.png',event);">
               <img src="smiley-39984_1280.png" alt="Bild 1" class="small-image">
            </button>
            <button type="button" class="image-button" name="image-button2" onclick="toggleSelection(this,'shopping-cart-1901584_960_720.webp',event);">
               <img src="shopping-cart-1901584_960_720.webp" alt="Bild 2" class="small-image">
            </button>
            <button type="button" class="image-button"  name="image-button3" onclick="toggleSelection(this,'euro-145386_1280.png',event);">
               <img src="euro-145386_1280.png" alt="Bild 3" class="small-image">
            </button>
            <button type="button" class="image-button" name="image-button4" onclick="toggleSelection(this,'abstract-1299319_1280.png',event);">
               <img src="abstract-1299319_1280.png" alt="Bild 4" class="small-image">
            </button>
            </div>
            <div>
               <label>
                  <input type="radio" class="option1" name="radiocheck" onclick="check('Lokal')" value="Lokal">Lokal
               </label>
               <br>
               <label>
                  <input type="radio" class="option2" name="radiocheck" onclick="check('Online')" value="Online"> Online
               </label>
            </div>
            <input type="submit" class="generate_list_form" value="Generieren">
            <input type="hidden" name="selected_image" id="selected_image" >
            <input type="hidden"  name="selected_option" id="selected_option" >
            
         `;
         element.style.display = "block"; 
      }




      function close(){
         
         let zustand1=document.querySelector(".selected_option")
         zustand1.value=""
         let pictureSelector=document.querySelector('.selected')
         pictureSelector.value=""

      }

      function toggleSelection(button,picture,event) {
         event.preventDefault();
         let button1=document.querySelector('.selected')
         if (button1){
            button1.classList.replace('selected','image-button');
            button1.setAttribute('selected','image-button');
         } 
         button.classList.replace('image-button','selected')
         button.setAttribute('image-button','selected');
         let pictureSelector=document.querySelector('#selected_image')
         pictureSelector.value=picture

      }
      function check(zustand_function){
         let zustand=document.querySelector("#selected_option")
         zustand.value=zustand_function
         console.log(document.querySelector("#selected_option").value)
         console.log(zustand)
      }

      function generieren_check(){
         
         let input = document.querySelector(".input_name_list").value;
         let option1 = document.querySelector(".option1").checked;
         let option2 = document.querySelector(".option2").checked;
         let picture = document.querySelector(".selected");
         if (input === "" || picture === null || (option1 === false && option2 === false)) {
            alert("Bitte füllen sie die Felder aus");
            console.log("false")
            return false
            
         } else {
            let element=document.querySelector(".einkaufslisten_add_hidden")
            element.style.display = "none";
            console.log("right")
            return true 
            
            
         }
      }

   </script>
</body>
</html>