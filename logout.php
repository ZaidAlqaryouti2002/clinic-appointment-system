<?php

declare(strict_types=1);

session_start();

$isAdmin = isset($_SESSION["admin_id"]);

session_unset();
session_destroy();

if ($isAdmin) {
    header("Location: admin/login.php");
} else {
    header("Location: login.php");
}

exit;

?>
