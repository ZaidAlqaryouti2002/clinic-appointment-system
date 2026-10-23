<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../classes/database.php";
require_once "../classes/doctor.php";

$database = new Database();
$conn = $database->getConnection();

$doctorModel = new Doctor($conn);

$message = "";
$editDoctor = false;

if (isset($_GET["delete"])) {
    $doctorId = (int) $_GET["delete"];
    $doctorToDelete = $doctorModel->getDoctorById($doctorId);

    if ($doctorToDelete) {
        try {
            if ($doctorModel->deleteDoctor($doctorId)) {
                if ($doctorToDelete["image"] !== "") {
                    $imagePath = "../uploads/doctors/" . $doctorToDelete["image"];

                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $message = "Doctor deleted successfully";
            } else {
                $message = "Failed to delete doctor";
            }
        } catch (PDOException $e) {
            $message = "Doctor cannot be deleted because they have appointments";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "add";
    $fname = trim($_POST["first_name"]);
    $lname = trim($_POST["last_name"]);
    $specialization = trim($_POST["specialization"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);

    if (
        $fname === "" ||
        $lname === "" ||
        $specialization === "" ||
        $phone === "" ||
        $email === ""
    ) {
        $message = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
    } else {
        $doctorId = 0;
        $image = "";
        $oldImage = "";

        if ($action === "update") {
            $doctorId = (int) ($_POST["doctor_id"] ?? 0);
            $currentDoctor = $doctorModel->getDoctorById($doctorId);

            if (!$currentDoctor) {
                $message = "Doctor not found";
            } else {
                $image = $currentDoctor["image"];
                $oldImage = $currentDoctor["image"];
            }
        }

        if ($message === "" && isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $allowedExtensions = ["jpg", "jpeg", "png", "webp"];
            $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions)) {
                $message = "Only JPG, JPEG, PNG and WEBP images are allowed";
            } else {
                $image = time() . "_" . basename($_FILES["image"]["name"]);
                $targetPath = "../uploads/doctors/" . $image;

                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
                    $message = "Image upload failed";
                }
            }
        } elseif ($message === "" && isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $message = "Image upload failed";
        }

        if ($message === "") {
            if ($action === "update") {
                if (
                    $doctorModel->updateDoctor(
                        $doctorId,
                        $fname,
                        $lname,
                        $specialization,
                        $phone,
                        $email,
                        $image
                    )
                ) {
                    if ($oldImage !== "" && $image !== $oldImage) {
                        $oldImagePath = "../uploads/doctors/" . $oldImage;

                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $message = "Doctor updated successfully";
                } else {
                    $message = "Failed to update doctor";
                }
            } else {
                if (
                    $doctorModel->addDoctor(
                        $fname,
                        $lname,
                        $specialization,
                        $phone,
                        $email,
                        $image
                    )
                ) {
                    $message = "Doctor added successfully";
                } else {
                    $message = "Failed to add doctor";
                }
            }
        }
    }
}

if (isset($_GET["edit"])) {
    $editDoctor = $doctorModel->getDoctorById((int) $_GET["edit"]);
}

$doctors = $doctorModel->getAllDoctors();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors</title>
</head>
<body>

    <h1>Manage Doctors</h1>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="doctors.php">Doctors</a>
        <a href="patients.php">Patients</a>
        <a href="appointments.php">Appointments</a>
        <a href="../logout.php">Logout</a>
    </nav>

    <?php if ($message !== ""): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($editDoctor): ?>

        <h2>Edit Doctor</h2>

        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="doctor_id" value="<?= $editDoctor["doctor_id"] ?>">

            <label>First Name</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($editDoctor["first_name"]) ?>" required>

            <label>Last Name</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($editDoctor["last_name"]) ?>" required>

            <label>Specialization</label>
            <input type="text" name="specialization" value="<?= htmlspecialchars($editDoctor["specialization"]) ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($editDoctor["phone"]) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($editDoctor["email"]) ?>" required>

            <label>Image</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">

            <button type="submit">Update Doctor</button>
            <a href="doctors.php">Cancel</a>
        </form>

    <?php else: ?>

        <h2>Add Doctor</h2>

        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">

            <label>First Name</label>
            <input type="text" name="first_name" required>

            <label>Last Name</label>
            <input type="text" name="last_name" required>

            <label>Specialization</label>
            <input type="text" name="specialization" required>

            <label>Phone</label>
            <input type="text" name="phone" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Image</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">

            <button type="submit">Add Doctor</button>
        </form>

    <?php endif; ?>

    <h2>Doctors</h2>

    <?php if (empty($doctors)): ?>

        <p>No doctors found.</p>

    <?php else: ?>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($doctors as $doctor): ?>
                    <tr>
                        <td><?= $doctor["doctor_id"] ?></td>

                        <td>
                            <?php if ($doctor["image"] !== ""): ?>
                                <img
                                    src="../uploads/doctors/<?= htmlspecialchars($doctor["image"]) ?>"
                                    width="80"
                                    alt="Doctor Image"
                                >
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($doctor["first_name"]) ?>
                            <?= htmlspecialchars($doctor["last_name"]) ?>
                        </td>

                        <td><?= htmlspecialchars($doctor["specialization"]) ?></td>
                        <td><?= htmlspecialchars($doctor["phone"]) ?></td>
                        <td><?= htmlspecialchars($doctor["email"]) ?></td>

                        <td>
                            <a href="doctors.php?edit=<?= $doctor["doctor_id"] ?>">Edit</a>
                            <a href="doctors.php?delete=<?= $doctor["doctor_id"] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>
