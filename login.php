<?

require_once('db.php');

session_start();

if ($_SESSION['login'] || $_SESSION['password']) {
  header('Location: content.php');
  die();
}

ini_set('session.gc.maxlifetime', 3600);


$users = $dbh->query("SELECT * FROM users");


if ($_POST['login']) {
  foreach($users as $user) {
    if ($user['login'] == $_POST['login'] && password_verify($_POST['password'], $user['password'])) {
      $_SESSION['login'] = $_POST['login'];
      $_SESSION['password'] = $_POST['password'];
      setcookie('usercolor', $_POST['color'], time() + 3600);
      header("Location: content.php" );
    }
  }

  echo 'Неправильный login или password!';
}

?>

<style>
  body {
    margin: 200px 300px;
  }

  input, p {
    font-size: 30px;
    margin: 10px;
  }
</style>

<form method="post">
  <p>Авторизируйтесь</p>
  <input type="text" name="login" required placeholder="login"> <br>
  <input type="password" name="password" required placeholder="password"> <br>
  <input type="submit">

  <select name="color">
    <option value="blue" >синий</option>
    <option value="red" >красный</option>
    <option value="yellow" >жёлтый</option>
    <option value="green" >зелёный</option>
  </select>
</form>