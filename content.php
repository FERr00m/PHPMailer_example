<?php

session_start();
if (!$_SESSION['login'] || !$_SESSION['password']) {
  header('Location: login.php');
  die();
}

if ($_POST['unlogin']) {
  setcookie('usercolor');
  session_destroy();
  header('Location: index.php');
  die();
}

?>

<style>
  body {
    background-color: <?=$_COOKIE['usercolor']?>;
    margin: 200px 300px;
  }

  input, p {
    font-size: 30px;
    margin: 10px;
  }
</style>

<p>Сайт только для авторизированных пользователей</p>

<? echo "Привет, {$_SESSION['login']} <br>" ?>

<form method="post">
  <input type="submit" value="exit" name="unlogin">
</form>


<?
