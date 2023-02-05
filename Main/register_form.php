<?php

include 'config_servidor.php';

session_start();

if (isset($_POST['submit'])) {

   $email = mysqli_real_escape_string($conn, $_POST['usermail']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $username = mysqli_real_escape_string($conn, $_POST['uname']);
   $date = $_POST['date'];
   $sex = mysqli_real_escape_string($conn, $_POST['gender']);
   $pwd_save = password_hash($pass, PASSWORD_BCRYPT);
   $today = new DateTime();
   $diff = $today->diff(new DateTime($date));
   $message = $diff->y;
   

   $select = " SELECT * FROM tb_utilizadores WHERE email = '$email' or username = '$username'";

   $result = mysqli_query($conn, $select);

   if ($message < 18) {
      $error[] = 'Não possui idade suficiente';
   }
   elseif (mysqli_num_rows($result) > 0){
      $error[] = 'este utilizador/email já existe!';
   }
   elseif(mysqli_num_rows($result) <= 0){
      if ($pass != $cpass) {
         $error[] = 'As palavras passes não correspondem!';
      } else {
         $insert = "INSERT INTO tb_utilizadores(email,username,pwd,gender,dtn) VALUES('$username','$email','$pwd_save','$sex','$date')";
         mysqli_query($conn, $insert);
         header('location:login_form.php');
      }
   }
 


}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="./Css/login.css">
   <title>Webchatify</title>
   <link rel="" type= "" href="">
</head>

<body>

   <div class="form-container">

      <form action="" method="post">
         <h3 class="title">Registro</h3>
         <?php
         if (isset($error)) {
            foreach ($error as $error) {
               echo '<span class="error-msg">' . $error . '</span>';
            }
         }
         ?>
         <input type="text" name="uname" placeholder="introduza o seu username" class="box" required>
         <input type="email" name="usermail" placeholder="introduza o seu endereço email" class="box" required>
         <input type="password" name="password" placeholder="introduza a sua password" class="box" required>
         <input type="password" name="cpassword" placeholder="confirme a sua password" class="box" required>
         <input type="date" name="date" class="box"><br>
         <input type="radio" name="gender" value="masculino" id="male">
         <label for="male">Masculino</label>
         <input type="radio" name="gender" value="feminino" id="female">
         <label for="female">Feminino</label>


         <input type="submit" value="Registrar" class="form-btn" name="submit">
         <p>Já possui uma conta? <a href="login_form.php">Faça login agora!</a></p>
      </form>

   </div>

</body>

</html>