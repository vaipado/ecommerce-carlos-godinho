<li class="nav-item">
    <a class="nav-link icon-link" href="carrinho.php">
        CARRINHO
    </a>
</li>
<li class="nav-item dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php
        if (isset($_SESSION['admin_logged_in'])) {
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
                  </svg> ' . htmlspecialchars($_SESSION['admin_usuario']);
        } elseif (isset($_SESSION['user_id'])) {
            echo "USUÁRIO @" . htmlspecialchars($_SESSION['user_nome']);
        } else {
            echo "ENTRAR";
        }
        ?>
    </button>
    <ul class="dropdown-menu">
        <?php if (isset($_SESSION['admin_logged_in']) || isset($_SESSION['user_id'])): ?>
            <li><a class="dropdown-item icon-link" href="logout.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open-fill" viewBox="0 0 16 16">
                        <path d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15zM11 2h.5a.5.5 0 0 1 .5.5V15h-1zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1" />
                    </svg>Sair</a></li>
        <?php else: ?>
            <li><a class="dropdown-item" href="login.php">LOGIN</a></li>
            <li><a class="dropdown-item" href="login.php?admin=1">ENTRAR COMO ADMINISTRADOR</a></li>
        <?php endif; ?>
    </ul>
</li>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

    .dropdown-item:hover {
        color: #ff9800 !important;
        transition: color 0.6s ease !important;
    }

    .btn.btn-secondary.dropdown-toggle {
        background-color: #202020;
        border: solid 1px white;
    }

    .btn.btn-secondary.dropdown-toggle:hover {
        border: solid 1px #333;
    }

    .dropdown-menu:hover,
    .dropdown-menu:hover .dropdown-item {
        background-color: #333;
        color: white;
    }
</style>