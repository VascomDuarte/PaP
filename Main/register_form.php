<?php

// Include the config_servidor.php file to get access to the database
include 'config_servidor.php';

// Start a new session
session_start();

// Check if the submit button was clicked
if (isset($_POST['submit'])) {

   // Escape and store the email from the form
   $email = mysqli_real_escape_string($conn, $_POST['usermail']);
   
   // Hash the password from the form
   $pass = md5($_POST['password']);
   
   // Hash the confirmed password from the form
   $cpass = md5($_POST['cpassword']);
   
   // Escape and store the username from the form
   $username = mysqli_real_escape_string($conn, $_POST['uname']);
   
   // Store the birthdate from the form
   $date = $_POST['date'];
   
   // Escape and store the gender from the form
   $sex = mysqli_real_escape_string($conn, $_POST['gender']);
   
   // Hash and store the password for storage in the database
   $pwd_save = password_hash($pass, PASSWORD_BCRYPT);
   
   // Create a new DateTime object to store the current date and time
   $today = new DateTime();
   
   // Calculate the difference between the current date and the birthdate
   $diff = $today->diff(new DateTime($date));
   
   // Store the user's age in the message variable
   $message = $diff->y;
   
   // Select all records from the user table where the email or username match the input from the form
   $select = " SELECT * FROM user WHERE email = '$email' or username = '$username'";
   $result = mysqli_query($conn, $select);
   
   // If the user's age is less than 18, show an error message
   if ($message <= 18) {
      $error[] = 'Não possui idade suficiente';
   }
   // If the email or username already exists, show an error message
   elseif (mysqli_num_rows($result) > 0){
      $error[] = 'este utilizador/email já existe!';
   }
   // If the email and username do not already exist
   elseif(mysqli_num_rows($result) <= 0){
      // If the password and confirmed password do not match, show an error message
      if ($pass != $cpass) {
         $error[] = 'As palavras passes não correspondem!';
      } else {
         // If the password and confirmed password match, insert the user's information into the database
         $insert = "INSERT INTO user(username,email,password,gender,dtn_nascimento) VALUES('$username','$email','$pwd_save','$sex','$date')";
         mysqli_query($conn, $insert);
         // Redirect the user to the login form
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
   <!-- Link to the CSS stylesheet for styling the form -->
   <link rel="stylesheet" href="Css/acc_style.css">
   <!-- Title for the page -->
   <title>Register Page</title>
   <!-- Empty link for future use -->
   <link rel="" type= "" href="">
</head>

<body>

   <!-- Form container division -->
   <div class="form-container">

      <!-- Registration form -->
      <form action="" method="post">
         <!-- Form title -->
         <h3 class="title">Registro</h3>
         <!-- Error message handling -->
         <?php
         if (isset($error)) {
            foreach ($error as $error) {
               echo '<span class="error-msg">' . $error . '</span>';
            }
         }
         ?>
         <!-- Input fields for username, email, password, confirm password, birthdate, and gender -->
         <input type="text" name="uname" placeholder="introduza o seu username" class="box" required>
         <input type="email" name="usermail" placeholder="introduza o seu endereço email" class="box" required>
         <input type="password" name="password" placeholder="introduza a sua password" class="box" required>
         <input type="password" name="cpassword" placeholder="confirme a sua password" class="box" required>
         <input type="date" name="date" class="box"><br>
         <input type="radio" name="gender" value="masculino" id="male">
         <label for="male">Masculino</label>
         <input type="radio" name="gender" value="feminino" id="female">
         <label for="female">Feminino</label>
         <!-- Submit button -->
         <input type="submit" value="Registrar" class="form-btn" name="submit">
         <!-- Link to login page -->
         <p>Já possui uma conta? <a href="login_form.php">Faça login agora!</a></p>
      </form>

   </div>

</body>

</html>
