<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">

        <button id="sidebarCollapseBtn"  type="button" class="btn btn-primary" onclick="activeSidebar()">
            <i class="bi bi-list"></i>
        </button>

        <script>
            // active sidebar
            function activeSidebar() {
                document.getElementById("sidebar").classList.toggle("close");
            }
        </script>
        
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown me-3">
                <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">New enrollment request</a></li>
                    <li><a class="dropdown-item" href="#">Payment due reminder</a></li>
                    <li><a class="dropdown-item" href="#">Staff meeting at 3 PM</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-light d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <img width="32px" height="32px" src="<?= $_SESSION['admin_user']['photo'] ? 'uploads/' . $_SESSION['admin_user']['photo'] : 'https://placehold.co/32' ?>" class="rounded-circle me-2" alt="Admin">
                    <span><?= $_SESSION['admin_user']['name'] ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>