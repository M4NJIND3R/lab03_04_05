<?php

  session_start();
  // If they're not logged in, redirect them
  if(!isset($_SESSION['user'])) unset($_SESSION['user']);
  // Logging out means just destroying the session variable 'user'
  unset($_SESSION['user']);
  // Then redirect with a success message
  $_SESSION['successes'][] = "You have been successfully logged out";
  header('Location: login.php');
  die();