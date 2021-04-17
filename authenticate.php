<?php


  // Connect to the database
  require_once("_connect.php");
  $conn = dbo();

  // Create our SQL with an email placeholder
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);  
  $email = strtolower($email);
  $pass = filter_input(INPUT_POST,'password');

  // Prepare the SQL
  $sql = "SELECT * FROM users WHERE email = :email";
  $stmt = $conn->prepare($sql);
  // Bind the value to the placeholder (incidently this will also sanitize the value)
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  // Execute
  $stmt->execute();
  // Check for errors
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  // Check if we have a user and their password is correct
  $authorize = false;
  if($user){
    $authorize = password_verify($pass,$user['password']);
  }

  
  // Add a session variable to keep track of the user
  session_start();
  if(!$authorize){
    $_SESSION['errors'][] = "your loing/password combination could not be found";
    $_SESSION['from_values'] = $_POST;
    header('Location: login.php');
    exit();
  }

  unset($user['password']);
  $_SESSION['user'] = $user;
  $_SESSION['successes'][]= "You have been successfully logged in.";
  // Redirect back to the form
  header('Location: profile.php');
  die();