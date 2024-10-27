<?php
session_start();

require_once __DIR__ . '/classes/ProviderRepository.php';
require_once __DIR__ . '/classes/Provider.php';
require_once __DIR__ . '/classes/LoginRepository.php';

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

$provider = new Provider(); //object to store the form data
$errors = []; //array to store the error messages
$opMsg = ''; //operation message

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_REQUEST["id"])) {
        $provider = ProviderRepository::select($_REQUEST['id']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $provider->setId((int) $_POST['id']);
    $provider->setName(trim(strip_tags($_POST['name'])));
    $provider->setEmail(trim(strip_tags($_POST['email'])));
    $provider->setCif(trim(strip_tags($_POST['cif'])));

    $errors = $provider->validate();

    if (empty($errors)) {
        if(isset($_POST['save'])) {
            if ($provider->getId() == 0) {
                //New provider
                try {
                    $id = ProviderRepository::insert($provider);
                    if ($id) {
                        $opMsg = "New provider inserted with id $id";
                        $provider = new Provider(); //If no errors, make a new empty provider to clear the fields
                    }
                } catch (Exception $e) {
                    echo "Error inserting provider: " . $e->getMessage();
                }
            } else {
                //Update provider
                try {
                    ProviderRepository::update($provider);
                    $opMsg = "Provider updated";
                    $provider = new Provider();
                } catch (Exception $e) {
                    echo "Error updating provider: " . $e->getMessage();
                }
            }
        }

        if (isset($_POST['delete']) && $provider->getId() != 0) {
            try {
                ProviderRepository::delete($provider);
                $opMsg = "Provider deleted";
                $provider = new Provider();
            } catch (Exception $e) {
                echo "Error deleting provider: " . $e->getMessage();
            }
        }
    }
}  //end of POST

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Providers form sample</title>
    <meta name="author" content="Ricardo Sanchez">
    <meta name="description" content="a sample form to show validation techniques">
    <link rel="stylesheet" type="text/css" href="./main.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav_bar.part.php'; ?>
<h1>Edit Provider</h1>
<p> <?= $opMsg ?> </p>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="id">Id:</label>
    <input type="text" id="id" name="id" value="<?= $provider->getId() ?? '' ?>" readonly>

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?= $provider->getName() ?? '' ?>">
    <span class="error"> <?= $errors['name'] ?? '' ?> </span> <br><br>

    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?= $provider->getEmail() ?? '' ?>">
    <span class="error"> <?= $errors['email'] ?? '' ?> </span> <br><br>

    <label for="cif">CIF:</label>
    <input type="text" id="cif" name="cif" value="<?= $provider->getCif() ?? '' ?>">
    <span class="error"> <?= $errors['cif'] ?? '' ?> </span><br><br>

    <input type="submit" value="Save" name="save">
    <input type="submit" value="Delete" name="delete" <?= $provider->getId() == 0 ? 'disabled' : '' ?> >
</form>

</body>
</html>