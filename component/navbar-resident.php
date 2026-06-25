<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="dashboard-resident.php">
            RCMS Resident
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="dashboard-resident.php">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="complaint-submit.php">
                        Submit Complaint
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="complaint-history.php">
                        Complaint History
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        Profile
                    </a>
                </li>

            </ul>

            <span class="navbar-text text-white me-3">
                <?= $_SESSION['full_name']; ?>
            </span>

            <a href="../../auth/logout.php"
               class="btn btn-light btn-sm">
               Logout
            </a>

        </div>
    </div>
</nav>
