<?php
session_start();
require_once __DIR__ . '/classes/Login.php';
require_once __DIR__ . '/classes/LoginRepository.php';

$logins = LoginRepository::getAll();

if(isset($_SESSION['user_id'])) {
    $user = LoginRepository::select($_SESSION['user_id']);
    $userRole = $user->getRole();
    if($userRole != 'admin'){
        //Has the role user
        header('Location: provider_list.php');
    }
}
else {
    //No authenticated user
    header('Location: login_form.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login List</title>
    <meta name="author" content="Ricardo Sanchez">
    <meta name="description" content="a sample form to show database operations">
    <link rel="stylesheet" type="text/css" href="./main.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav_bar.part.php'; ?>
<h1>Login list</h1>

<table>
    <tr>
        <th>ID</th> <th>Email</th> <th>Password</th> <th>Role</th>
    </tr>
    <?php
    foreach ($logins as $login){
        echo "    <tr>\n";
        echo " <td> " . $login->getId() . "</td>";
        echo " <td> " . $login->getEmail() . "</td>";
        echo " <td> " . $login->getPassword() . "</td>";
        echo " <td> " . $login->getRole() . "</td>";
        echo "    </tr>\n";
    }
    ?>
</table>

</body>
</html>