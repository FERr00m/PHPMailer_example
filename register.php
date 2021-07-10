<?
require 'lib/phpmailer/Exception.php';
require 'lib/phpmailer/PHPMailer.php';
require 'lib/phpmailer/SMTP.php';


require_once('db.php');


if ($_POST['register']) {

  if ($_REQUEST['password'] !== $_REQUEST['password_repeat']) {
    echo 'Пароли не совпадают';
  } else {
    $login = $_POST['login'];
    $email = $_POST['email'];
    // Пароль хешируется
    $password = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
    // хешируем хеш, который состоит из логина и времени
    $hash = md5($login . time());

    $dbh->query("INSERT INTO users (login, password, email, hash) VALUES ('$login', '$password', '$email', '$hash')");
 

    // Переменные, которые отправляет пользователь
    $login = $_POST['login'];
    $email = $_POST['email'];
    //$text = $_POST['text'];
    // $file = $_FILES['myfile'];

    // Формирование самого письма
    $title = "Подтвердите свой email";
    $body = "
    <h2>Новое письмо</h2>
    <b>Login:</b> $login<br>
    <b>Почта:</b> $email<br><br>
    <b>Сообщение:</b><br>
    Перейдите по этой <a href='http://practicephpcookies:81/confirmed.php?hash=$hash'>ссылке</a>, чтобы подтвердить свой email.
    ";

    // Настройки PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
      $mail->isSMTP();   
      $mail->CharSet = "UTF-8";
      $mail->SMTPAuth   = true;
      //$mail->SMTPDebug = 2;
      $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

      // Настройки вашей почты
      $mail->Host       = 'smtp.gmail.com'; // SMTP сервера вашей почты
      $mail->Username   = 'example@gmail.com'; // Логин на почте
      $mail->Password   = 'password'; // Пароль на почте
      $mail->SMTPSecure = 'ssl';
      $mail->Port       = 465;
      $mail->setFrom('example@gmail.com', 'Имя отправителя'); // Адрес самой почты и имя отправителя

      // Получатель письма
      $mail->addAddress("$email");  
      //$mail->addAddress('youremail@gmail.com'); // Ещё один, если нужен

      // Прикрипление файлов к письму
      // if (!empty($file['name'][0])) {
      //     for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
      //         $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
      //         $filename = $file['name'][$ct];
      //         if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
      //             $mail->addAttachment($uploadfile, $filename);
      //             $rfile[] = "Файл $filename прикреплён";
      //         } else {
      //             $rfile[] = "Не удалось прикрепить файл $filename";
      //         }
      //     }   
      // }
      // Отправка сообщения
      $mail->isHTML(true);
      $mail->Subject = $title;
      $mail->Body = $body;    

      // Проверяем отравленность сообщения
      if ($mail->send()) {$result = "success";} 
      else {$result = "error";}

    } catch (Exception $e) {
        $result = "error";
        $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
    }

    // Отображение результата
    //echo json_encode(["result" => $result, "status" => $status]);

      }
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
  <p>Регистрация</p>
    <p>Логин: <input type="text" name="login" required></p>
    <p>EMail: <input type="email" name="email" required></p>
    <p>Пароль: <input type="password" name="password" required></p>
    <p>Повторите пароль: <input type="password" name="password_repeat" required></p>
    <p><input type="submit" value="Зарегистрироваться" name="register"></p>
</form>
