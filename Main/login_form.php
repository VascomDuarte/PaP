<?php

include 'config_servidor.php';

session_start();

if (isset($_POST['submit'])) {

   $email = mysqli_real_escape_string($conn, $_POST['usermail']);
   $pass = md5($_POST['password']);
   

   $sql  = " SELECT * FROM tb_utilizadores WHERE email = '$email'";

   $result = mysqli_query($conn, $sql);

   if ($result === false) {
      $error[] = 'a password ou o email está incorreta';
   }

   $count = mysqli_num_rows($result);
   if ($count === 0) {
     $error[] = 'a password ou o email está incorreta';
   }

   $row = mysqli_fetch_assoc( $result );

   $passwordHash = $row['pwd'];
   $passwordCheck = password_verify( $pass, $passwordHash );
   if( ! $passwordCheck )
   {
      $error[] = 'a password ou o email está incorreta';
   }else
   {
      $_SESSION['usermail'] = $email;
      $_SESSION['id_user'] = $row['id'];
      $_SESSION['nome_user'] = $row['username'];
      header('location:chat_main.php');
   }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="/Css/login.css">
</head>

<body>

   <div class="form-container">

      <form action="" method="post">
         <h3 class="title">Login</h3>
         <?php
         if (isset($error)) {
            foreach ($error as $error) {
               echo '<span class="error-msg">' . $error . '</span>';
            }
         }
         ?>
         <input type="email" name="usermail" placeholder="Introduza o seu email" class="box" required>
         <input type="password" name="password" placeholder="introduza a sua password" class="box" required>
         <input type="submit" value="LOGIN" class="form-btn" name="submit">
         <p>Não possui nenhuma conta? <a href="register_form.php">Registrar-se</a></p>
      </form>

   </div>

</body>

</html>
