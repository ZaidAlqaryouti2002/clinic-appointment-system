<?php
declare(strict_types=1);

require_once "classes/database.php";
require_once "classes/patient.php";

$database = new Database();

$conn = $database->getConnection();

$patient = new Patient($conn);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fname = trim($_POST["first_name"]);
    $lname = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $phone = trim($_POST["phone"]);

    if (
        $fname === "" ||
        $lname === "" ||
        $email === "" ||
        $password === "" ||
        $phone === ""
    ) {
        $message = "All fields are required";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
    }
    else {
        if ($patient->register($fname, $lname, $email, $password, $phone)) {
            $message = "Registration successful";
        } else {
            $message = "Registration failed";
        }
    }
}
?>

<?php if ($message !== "") : ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>First Name</label>
    <input type="text" name="first_name">

    <label>Last Name</label>
    <input type="text" name="last_name">

    <label>Email</label>
    <input type="email" name="email">

    <label>Password</label>
    <input type="password" name="password">

    <label>Phone</label>
    <input type="text" name="phone">

    <button type="submit">Register</button>
</form>

<p>
    Already have an account?
    <a href="login.php">Login</a>
</p>
