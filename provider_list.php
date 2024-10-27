<?php
session_start();
require_once __DIR__ . '/classes/ProviderRepository.php';
require_once __DIR__ . '/classes/Provider.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login_form.php');
}

$providers = ProviderRepository::getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Providers list</title>
    <meta name="author" content="Ricardo Sanchez">
    <meta name="description" content="a sample list of providers">
    <link rel="stylesheet" type="text/css" href="./main.css">
</head>
<body>
<?php include __DIR__ . '/partials/nav_bar.part.php'; ?>
<h1>Provider List</h1>
<form action="provider_form.php" method="get" id="form1" style="border: none"></form>
<p class="centered">
    <button type='submit' form='form1' name='id' > Create new provider </button>
</p>
    <table>
        <tr>
            <th> </th>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>CIF</th>
        </tr>
        <?php
        foreach ($providers as $provider){
            echo "    <tr>\n";
            echo "      <td style='text-align: center'> <button type='submit' form='form1' name='id' value='"
                . $provider->getId() . "' > Edit/View </button> </td>";
            echo " <td> " . $provider->getId() . "</td>";
            echo " <td> " . $provider->getName() . "</td>";
            echo " <td> " . $provider->getEmail() . "</td>";
            echo " <td> " . $provider->getCif() . "</td>\n";
            echo "    </tr>\n";
        }
        ?>
    </table>
</body>
</html>
