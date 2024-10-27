<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/classes/Login.php';
require_once __DIR__ . '/classes/LoginRepository.php';

// define variables and set to empty values
$login = new Login();
$errors = [];
$opMsg = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login->setEmail(trim(strip_tags($_POST['email'])));
    $clearPassword = trim(strip_tags($_POST['pass']));
    $login->setPassword($clearPassword);
    $errors = $login->validate();

    if (empty($errors)) {
        if(isset($_POST['submit']) ){
            try {
                $idLogin = LoginRepository::checkCredential($clearPassword, $login);
                if($idLogin){
                    $opMsg = "Login successful";
                    $_SESSION['user_id'] = $idLogin;
                    header('Location: provider_list.php');
                } else {
                    $opMsg = "Login failed";
                }
                $login = new Login();
            } catch (Exception $e) {
                echo "Error checking login: " . $e->getMessage();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="author" content="Ricardo Sanchez">
    <meta name="description" content="a sample form to show database operations">
    <link rel="stylesheet" type="text/css" href="./main.css">
</head>
<body>
<h1>Login</h1>
<p> <?= $opMsg ?> </p>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  enctype="multipart/form-data">
    <label> E-mail: <br>
        <input type="text" name="email" value="<?= $login->getEmail();?>">  <span class="error"> <?= $errors['email'] ?? ''; ?> </span>
    </label>
    <label>Password: <br>
        <input type="password" name="pass" value="<?= $login->getPassword();?>"> <span class="error"> <?= $errors['password'] ?? ''; ?> </span>
    </label>

    <input type="submit" name="submit" value="Login">
</form>

</body>
</html>
