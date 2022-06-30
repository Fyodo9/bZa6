<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="styleq.css">
</head>
    <body>
<?php
  if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    !empty($_GET['logout'])) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="Enter login and password"');
    if (!empty($_GET['logout']))
      header('Location: admin.php');
    print('<h1>401 Требуется авторизация</h1></div></body>');
    exit();
  }

  $user = 'u40077';
  $pass = '3053723';
  $db = new PDO('mysql:host=localhost;dbname=u40077', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  
  $login = trim($_SERVER['PHP_AUTH_USER']);
  $pass =  trim($_SERVER['PHP_AUTH_PW']);
  $stmtCheck = $db->prepare('SELECT admin_pass FROM admin WHERE admin_login = ?');
  $stmtCheck->execute([$login]);
  $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

  if ($row == false || $row['admin_pass'] != $pass) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="Invalid login or password"');
    print('<h1>401 Неверный логин или пароль</h1>');
    exit();
  }
?>
    <section>
        <h2>Администрирование</h2>
        <a href="./?quit=1">Выйти</a>
    </section>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {


  $stmtCount = $db->prepare('SELECT abname, count(fa.form_id) AS amount FROM abils AS ab LEFT JOIN usertab AS fa ON ab.abid = fa.abid GROUP BY ab.abid');
  $stmtCount->execute();
  print('<section>');

  while($row = $stmtCount->fetch(PDO::FETCH_ASSOC)) {
      print('<b>' . $row['abname'] . '</b>: ' . $row['amount'] . '<br/>');
  }
  print('</section>');

  $stmt1 = $db->prepare('SELECT form_id, fio, email, bd, sex, limbs, bio, login FROM formtab5');
  $stmt2 = $db->prepare('SELECT abid FROM usertab WHERE form_id = ?');
  $stmt1->execute();

while($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
      print('<section>');
      print('<h2>' . $row['login'] . '</h2>');
      $abilities = [false, false, false, false];
      $stmt2->execute([$row['form_id']]);
      while ($superrow = $stmt2->fetch(PDO::FETCH_ASSOC)) {
          $abilities[$superrow['abid']] = true;
      }
      foreach ($row as $key => $value)
        if (is_string($value))
          $row[$key] = strip_tags($value);
      include('adminform.php');
      print('</section>');
  }

}

else {

    if (array_key_exists('delete', $_POST)) {
        $user = 'u40077';
        $pass = '3053723';
        $db = new PDO('mysql:host=localhost;dbname=u40077', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
        $stmt1 = $db->prepare('DELETE FROM usertab WHERE form_id = ?');
        $stmt1->execute([$_POST['uid']]);
        $stmt2 = $db->prepare('DELETE FROM formtab5 WHERE form_id = ?');
        $stmt2->execute([$_POST['uid']]);
        header('Location: admin.php');
        exit();
    }


    $errors = FALSE;
    $values = [];
    if (empty($_POST['fio'])) {
        $errors = TRUE;
    }
    $values['fio'] = strip_tags($_POST['fio']);

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors = TRUE;
    }
    $values['email'] = strip_tags($_POST['email']);

    if (empty($_POST['bd'])) {
        $errors = TRUE;
    }
    $values['bd'] = $_POST['bd'];

    if (empty($_POST['sex'])) {
        $errors = TRUE;
    }
    $values['sex'] = strip_tags($_POST['sex']);

    if (empty($_POST['limbs'])) {
        $errors = TRUE;
    }
    $values['limbs'] = strip_tags($_POST['limbs']);

    if (empty($_POST['bio'])) {
        $errors = TRUE;
    }
    $values['bio'] = strip_tags($_POST['bio']);

    if (!isset($_POST['accept'])) {
        $errors = TRUE;
    }


    foreach (['0', '1', '2', '3'] as $value) {
        $values['abilities'][$value] = FALSE;
    }


    if (empty($_POST['abilities'])) {
        $errors = TRUE;
    } else {
        foreach ($_POST['abilities'] as $super) {
            $values['abilities'][$super] = TRUE;
        }
    }


    if (array_key_exists('update', $_POST)) {

        $user = 'u40077';
        $pass_db = '3053723';
        $db = new PDO('mysql:host=localhost;dbname=u40077', $user, $pass_db, array(PDO::ATTR_PERSISTENT => true));
        $stmt1 = $db->prepare('UPDATE formtab5 SET fio=?, email=?, bd=?, sex=?, limbs=?, bio=? WHERE form_id = ?');
        $stmt1->execute([$_POST['fio'], $_POST['email'], $_POST['bd'], $_POST['sex'], $_POST['limbs'], $_POST['bio'], $_POST['uid']]);

        $stmt2 = $db->prepare('DELETE FROM usertab WHERE form_id = ?');
        $stmt2->execute([$_POST['uid']]);

        $stmt3 = $db->prepare("INSERT INTO usertab SET form_id = ?, abid = ?");
        foreach ($_POST['abilities'] as $s)
            $stmt3->execute([$_POST['uid'], $s]);

        header('Location: admin.php');
        exit();
    }
}


?>
</body>

