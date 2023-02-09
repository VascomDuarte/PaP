<?php

// Include the config_servidor.php file
include 'config_servidor.php';

// Start a new session
session_start();

// Check if the form has been submitted
if (isset($_POST['submit'])) {

   // Escape the email input and store it in the $email variable
   $email = mysqli_real_escape_string($conn, $_POST['usermail']);

   // Hash the password input and store it in the $pass variable
   $pass = md5($_POST['password']);

   // Select the user with the email that was inputted
   $sql  = " SELECT * FROM user WHERE email = '$email'";

   // Execute the query
   $result = mysqli_query($conn, $sql);

   // Check if the query execution was successful
   if ($result === false) {
      // If not, store an error message in the $error array
      $error[] = 'a password ou o email está incorreta';
   }

   // Check if there are any rows in the result set
   $count = mysqli_num_rows($result);
   if ($count === 0) {
     // If not, store an error message in the $error array
     $error[] = 'a password ou o email está incorreta';
   }

   // Fetch the row from the result set
   $row = mysqli_fetch_assoc( $result );

   // Store the password hash from the row in the $passwordHash variable
   $passwordHash = $row['password'];

   // Verify the hashed password with the password input
   $passwordCheck = password_verify( $pass, $passwordHash );

   // Check if the password verification was successful
   if( ! $passwordCheck ) {
      // If not, store an error message in the $error array
      $error[] = 'a password ou o email está incorreta';
   } else {
      // If the verification was successful, store the email and id in the session
      $_SESSION['usermail'] = $email;
      $_SESSION['id_user'] = $row['id'];
      $_SESSION['nome_user'] = $row['username'];

      // Redirect to the main.html page
      header('location:main.html');
   }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="Css/acc_style.css">

   <!-- Page title -->
   <title>Login Page</title>
</head>

<body>
   <!-- Login form container -->
   <div class="form-container">
      <!-- Login form -->
      <form action="" method="post">
         <!-- Form title -->
         <h3 class="title">Login</h3>

         <!-- PHP code to display any error messages -->
         <?php
            if (isset($error)) {
               foreach ($error as $error) {
                  echo '<span class="error-msg">' . $error . '</span>';
               }
            }
         ?>

         <!-- Email input -->
         <input type="email" name="usermail" placeholder="Introduza o seu email" class="box" required>
         <!-- Password input -->
         <input type="password" name="password" placeholder="introduza a sua password" class="box" required>
         <!-- Submit button -->
         <input type="submit" value="LOGIN" class="form-btn" name="submit">
         <!-- Link to register page -->
         <p>Não possui nenhuma conta? <a href="register_form.php">Registrar-se</a></p>
      </form>
   </div>
</body>

</html>

