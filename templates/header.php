<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- templates/header.php -->
<header>
    <div class="headerContainer">
        <nav>
            <ul class="nav justify-content-center">
                <!-- Link para a index.php ou admin.php dependendo se é admin -->
                <li class="nav-item">
                    <a class="nav-link icon-link" aria-current="page" href="<?php echo isset($_SESSION['admin_logged_in']) ? 'admin.php' : 'index.php'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5" />
                        </svg>
                    </a>
                </li>

                <!-- Incluindo a navegação do navBar.php -->
                <?php include('navBar.php'); ?>
            </ul>
        </nav>
    </div>
</header>

<style>
    header {
        background-color: #E8E2D4;
        padding: 10px 0;
    }

    .headerContainer {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    nav ul {
        list-style-type: none;
        display: flex;
        justify-content: center;
        gap: 3%;
        padding-left: 0;
    }

    .nav-item {
        position: relative;
        display: flex;
        justify-content: center;
    }

    .nav-link {
        color: #17100C !important;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.6s ease;
        padding: 8px 12px;
    }

    .nav-link:hover,
    .shopIcon:hover {
        color: #ff9800 !important;
    }

    @media (max-width: 768px) {
        nav ul {
            flex-direction: row;
            /* Garante a linha mesmo em telas pequenas */
            align-items: center;
        }

        .nav-item {
            margin-bottom: 0;
        }
    }
</style>