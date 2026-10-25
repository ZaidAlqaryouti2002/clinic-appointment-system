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



// Delete Doctor


if (isset($_GET["delete"])) {

    $doctorId = (int) $_GET["delete"];

    $doctorToDelete = $doctorModel->getDoctorById($doctorId);

    if ($doctorToDelete) {

        try {

            if ($doctorModel->deleteDoctor($doctorId)) {

                $doctorModel->deleteImage($doctorToDelete["image"]);

                $message = "Doctor deleted successfully";

            } else {

                $message = "Failed to delete doctor";
            }

        } catch (PDOException $e) {

            $message = "Doctor cannot be deleted because they have appointments";
        }
    }
}



// Add / Update Doctor


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "add";

    $fname = trim($_POST["first_name"]);
    $lname = trim($_POST["last_name"]);
    $specialization = trim($_POST["specialization"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);


    // Validation
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


    
        // Update
        

        if ($action === "update") {

            $doctorId = (int) ($_POST["doctor_id"] ?? 0);

            $currentDoctor = $doctorModel->getDoctorById($doctorId);

            if (!$currentDoctor) {

                $message = "Doctor not found";

            } else {

                // Keep current image
                $image = $currentDoctor["image"];
                $oldImage = $currentDoctor["image"];
            }
        }


        
        // Image Upload
        

        if (
            $message === "" &&
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] === UPLOAD_ERR_OK
        ) {

            $newImage = $doctorModel->uploadImage($_FILES["image"]);

            if ($newImage === false) {

                $message = "Image upload failed or image type is not allowed";

            } else {

                $image = $newImage;
            }
        }


        
        // Save to Database
    

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

                    // If a new image was uploaded,
                    // delete the old image
                    if ($oldImage !== "" && $image !== $oldImage) {
                        $doctorModel->deleteImage($oldImage);
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



// Edit Doctor


if (isset($_GET["edit"])) {

    $doctorId = (int) $_GET["edit"];

    $editDoctor = $doctorModel->getDoctorById($doctorId);
}



// Get all doctors


$doctors = $doctorModel->getAllDoctors();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors | MedCare</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="admin-page page-doctors">
<div class="admin-layout">
    <?php require "../components/navbar.php"; ?>
    <div class="admin-content">
        <?php $pageArea = "admin"; $basePath = "../"; ?>
        <?php require "../components/header.php"; ?>
        <main class="dashboard-content">
<div class="page-heading"><h1>Manage Doctors</h1><p>Add, update, and manage your clinic’s doctor records.</p></div>

    

    <?php if ($message !== ""): ?>
        <p class="alert" role="alert" data-message="<?= htmlspecialchars($message) ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <section class="form-section">
    <?php if ($editDoctor): ?>

        <h2>Edit Doctor</h2>

        <form class="doctor-form" method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="doctor_id" value="<?= $editDoctor["doctor_id"] ?>">

            <div class="form-field">
    <label for="first_name">First Name</label>
    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($editDoctor["first_name"]) ?>" required>
</div>

            <div class="form-field">
    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($editDoctor["last_name"]) ?>" required>
</div>

            <div class="form-field">
    <label for="specialization">Specialization</label>
    <input type="text" id="specialization" name="specialization" value="<?= htmlspecialchars($editDoctor["specialization"]) ?>" required>
</div>

            <div class="form-field">
    <label for="phone">Phone</label>
    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($editDoctor["phone"]) ?>" required>
</div>

            <div class="form-field">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($editDoctor["email"]) ?>" required>
</div>

            <div class="form-field">
    <label for="image">Image</label>
    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
</div>

            <div class="form-actions"><button type="submit">Update Doctor</button>
            <a class="button button-secondary" href="doctors.php">Cancel</a></div>
        </form>

    <?php else: ?>

        <h2>Add Doctor</h2>

        <form class="doctor-form" method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">

            <div class="form-field">
    <label for="first_name">First Name</label>
    <input type="text" id="first_name" name="first_name" required>
</div>

            <div class="form-field">
    <label for="last_name">Last Name</label>
    <input type="text" id="last_name" name="last_name" required>
</div>

            <div class="form-field">
    <label for="specialization">Specialization</label>
    <input type="text" id="specialization" name="specialization" required>
</div>

            <div class="form-field">
    <label for="phone">Phone</label>
    <input type="text" id="phone" name="phone" required>
</div>

            <div class="form-field">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>
</div>

            <div class="form-field">
    <label for="image">Image</label>
    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
</div>

            <div class="form-actions"><button type="submit">Add Doctor</button></div>
        </form>

    <?php endif; ?>

    </section>
    <section class="records-section">
    <h2>Doctors</h2>

    <?php if (empty($doctors)): ?>

        <p class="empty-state">No doctors found.</p>

    <?php else: ?>

        <div class="table-scroll" role="region" aria-label="Manage Doctors" tabindex="0">
<table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Specialization</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
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
                            <a class="table-action" href="doctors.php?edit=<?= $doctor["doctor_id"] ?>">Edit</a>
                            <a class="table-action action-danger" href="doctors.php?delete=<?= $doctor["doctor_id"] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</div>

    <?php endif; ?>


</section>
        </main>
        <?php require "../components/footer.php"; ?>
    </div>
</div>
</body>
</html>
