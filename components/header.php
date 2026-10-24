<?php if ($pageArea === "admin"): ?>
<header class="admin-header">
    <div>
        <strong>MedCare Administration</strong>
        <p>Clinic Appointment System</p>
    </div>
    <?php if (isset($adminName)): ?>
        <div class="admin-user">
            <strong><?= htmlspecialchars($adminName) ?></strong>
            <span>Administrator</span>
        </div>
    <?php else: ?>
        <span class="header-note">Clinic management</span>
    <?php endif; ?>
</header>
<?php else: ?>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?= $basePath ?>index.php" aria-label="MedCare home">
            <span class="brand-mark" aria-hidden="true"></span>
            <span>MedCare</span>
        </a>
        <?php if ($pageArea === "patient"): ?>
            <nav class="patient-nav" aria-label="Patient navigation">
                <a href="homepage.php">Home</a>
                <a href="patients-doctors.php">Doctors</a>
                <a href="all-appointments.php">My Appointments</a>
                <a class="logout-link" href="../logout.php">Logout</a>
            </nav>
        <?php elseif ($pageArea === "public"): ?>
            <nav class="public-nav" aria-label="Main navigation">
                <a class="admin-access" href="admin/login.php">Admin Login</a>
                <a href="login.php">Patient Login</a>
                <a class="button button-small" href="register.php">Create Account</a>
            </nav>
        <?php else: ?>
            <a class="back-link" href="<?= $basePath ?>index.php">Back to clinic home</a>
        <?php endif; ?>
    </div>
</header>
<?php endif; ?>
