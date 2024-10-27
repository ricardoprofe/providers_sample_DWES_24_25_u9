<?php
require_once __DIR__ . '/../classes/Login.php';
require_once __DIR__ . '/../classes/LoginRepository.php';

$welcomeMessage = '';
if(isset($_SESSION['user_id'])) {
    $user = LoginRepository::select($_SESSION['user_id']);
    if (!is_null($user)) {
        $userEmail = LoginRepository::select($_SESSION['user_id'])->getEmail() ?? '';
        $welcomeMessage = "Welcome $userEmail";
    }
}
?>
<!-- nav_bar.part.php -->
<nav>
    <span> <?= $welcomeMessage ?> </span> |
    <span>
        <?= isset($_SESSION['user_id']) ? "<a href='logout.php'>Logout</a>" : "" ?>
    </span>
</nav>