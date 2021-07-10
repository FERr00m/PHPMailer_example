<?
require_once('db.php');

// Проверка есть ли хеш
if ($_GET['hash']) {
  $hash = $_GET['hash'];

  $users = $dbh->query("SELECT * FROM users");

  foreach($users as $user) {
    if ($user['hash'] == $hash) {
      $dbh->query("UPDATE `users` SET `confirmed`=1 WHERE `hash`='$hash'");

      echo 'Email успешно подтверждён! :)';
    } else {
      echo 'Что то пошло не так с БД...';
    }
  }

  
} else {
  echo 'Что то пошло не так';
}
