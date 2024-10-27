<?php declare(strict_types=1);
require_once __DIR__ . '/classes/Login.php';
require_once __DIR__ . '/classes/LoginRepository.php';

// define variables and set to empty values
$login = new Login();
$errors = [];
$opMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login->setEmail(trim(strip_tags($_POST['email'])));
    $login->setPassword(trim(strip_tags($_POST['pass'])));
    $errors = $login->validate();

    if (empty($errors) && LoginRepository::searchByEmail($login) > 0) {
        $errors['email'] = "* Email already exists";
    }

    if (empty($errors)) {
        if(isset($_POST['submit']) ){
            //New login
            try {
                $login->setPassword(password_hash($login->getPassword(), PASSWORD_DEFAULT));
                LoginRepository::insert($login);
                $opMsg = "Register successful";//If no errors, make a new empty login to clear the fields
                $login = new Login();
            } catch (Exception $e) {
                echo "Error registering user: " . $e->getMessage();
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
<h1>Register</h1>
<p> <?= $opMsg ?> </p>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  enctype="multipart/form-data">
    <label> E-mail: <br>
        <input type="text" name="email" value="<?= $login->getEmail();?>">  <span class="error"> <?= $errors['email'] ?? ''; ?> </span>
    </label>
    <label>Password: <br>
        <input type="password" name="pass" value="<?= $login->getPassword();?>"> <span class="error"> <?= $errors['password'] ?? ''; ?> </span>
    </label>

    <input type="submit" name="submit" value="Register">
</form>

</body>
</html>
