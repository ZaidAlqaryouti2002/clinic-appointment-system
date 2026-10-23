<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["patient_id"])) {
    header("Location: ../login.php");
    exit;
}

?>
