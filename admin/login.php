<?php

declare(strict_types=1);

session_start();

require_once "../classes/database.php";
require_once "../classes/admin.php";

$database = new Database();
$conn = $database->getConnection();

$admin = new Admin($conn);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email === "" || $password === "") {
        $message = "Email and Password are required";
    } else {

        $loggedAdmin = $admin->login($email, $password);

        if ($loggedAdmin) {

            $_SESSION["admin_id"] = $loggedAdmin["admin_id"];
            $_SESSION["admin_name"] = $loggedAdmin["name"];

            header("Location: dashboard.php");
            exit;

        } else {
            $message = "Invalid Email or Password";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>

    <h1>Admin Login</h1>

    <?php if ($message !== ""): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>

    </form>

</body>
</html>