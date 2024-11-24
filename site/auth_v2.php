<?php
require_once('include/sql.php');
if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $user = authenticate_v2($username, $password);

  if ($user) {
    //create session with id
    $session->login($user['id']);
    //Update Sign in time
    updateLastLogIn($user['id']);
    if ($user['user_level'] === '1') {
      $session->msg("s", "Hello " . $user['username'] . ", Welcome to OSWA-INV.");
      redirect('../admin.php', false);
    } else if ($user['user_level'] === '2') {
      $session->msg("s", "Hello " . $user['username'] . ", Welcome to OSWA-INV.");
      redirect('../special.php', false);
    } else {
      $session->msg("s", "Hello " . $user['username'] . ", Welcome to OSWA-INV.");
      redirect('index.php', false);
    }

  } else {

    $session->msg("d", "Sorry Username/Password incorrect.");
    redirect('login_v2.php', false);
    exit();
  }

  // } else {

  //    redirect('login_v2.php',false);

}
?>