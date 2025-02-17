<?php
session_start();
include('../src/config/conexao.php');
require_once('../src/classes/Usuario.php');

$usuario = new Usuario($pdo);
$erro_login = "";
$erro_cadastro = "";
$sucesso = "";

$admin_mode = isset($_GET['admin']) && $_GET['admin'] == '1';
$aba_ativa = 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $usuario_input = $admin_mode ? trim($_POST['usuario']) : trim($_POST['email']);
        $senha = $_POST['senha'];
        $admin_attempt = isset($_POST['admin_login']) && $_POST['admin_login'] == '1';

        $erro_login = $usuario->login($usuario_input, $senha, $admin_attempt);
    }

    if (!$admin_mode && isset($_POST['cadastrar'])) {
        $email = trim($_POST['email']);
        $nome = trim($_POST['nome']);
        $senha = $_POST['senha'];
        $confirmar_senha = $_POST['confirmar_senha'];

        // Validação de senha
        if ($senha !== $confirmar_senha) {
            $erro_cadastro = "As senhas não coincidem.";
        } else {
            $erro_cadastro = $usuario->cadastrar($nome, $email, $senha, $confirmar_senha);

            if ($erro_cadastro === "Cadastro realizado com sucesso!") {
                $sucesso = $erro_cadastro;
                $erro_cadastro = "";
            } else {
                $aba_ativa = 'cadastro';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $admin_mode ? 'Login como Administrador' : 'Login'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .nav-tabs .nav-link {
            color: blue;
        }
        .nav-tabs .nav-link.active {
            color: black !important;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 id="titulo-form"><?php echo $admin_mode ? 'Login como Administrador' : 'Login'; ?></h2>
        <?php if (!$admin_mode): ?>
            <div class="nav nav-tabs">
                <button id="login-tab" class="nav-link <?php echo $aba_ativa === 'login' ? 'active' : ''; ?>" onclick="showTab('login')">Login</button>
                <button id="cadastro-tab" class="nav-link <?php echo $aba_ativa === 'cadastro' ? 'active' : ''; ?>" onclick="showTab('cadastro')">Cadastro</button>
            </div>
        <?php endif; ?>

        <div id="login-form" class="mt-3">
            <form action="login.php<?php echo $admin_mode ? '?admin=1' : ''; ?>" method="POST">
                <?php if ($admin_mode): ?>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuário:</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="text" class="form-control" id="email" name="email" required>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <input type="hidden" name="admin_login" value="<?php echo $admin_mode ? '1' : ''; ?>">
                <?php if (!empty($erro_login)): ?>
                    <div class="alert alert-danger"><?php echo $erro_login; ?></div>
                <?php endif; ?>
                <button type="submit" name="login" class="btn btn-primary">Entrar</button>
            </form>
        </div>

        <?php if (!$admin_mode): ?>
            <div id="cadastro-form" class="mt-3" style="display: <?php echo $aba_ativa === 'cadastro' ? 'block' : 'none'; ?>;">
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome de Usuário:</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">@</span>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping" required maxlength="11">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha:</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
                    </div>
                    <?php if (!empty($erro_cadastro)): ?>
                        <div class="alert alert-danger"><?php echo $erro_cadastro; ?></div>
                    <?php elseif (!empty($sucesso)): ?>
                        <div class="alert alert-success"><?php echo $sucesso; ?></div>
                    <?php endif; ?>
                    <button type="submit" name="cadastrar" class="btn btn-success">Cadastrar</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <?php
    include '../templates/footer.php';
    ?>

    <script>
        function showTab(tab) {
            document.getElementById('login-form').style.display = (tab === 'login') ? 'block' : 'none';
            document.getElementById('cadastro-form').style.display = (tab === 'cadastro') ? 'block' : 'none';

            document.getElementById('login-tab').classList.toggle('active', tab === 'login');
            document.getElementById('cadastro-tab').classList.toggle('active', tab === 'cadastro');

            // Altera o título dinamicamente
            document.getElementById('titulo-form').innerText = (tab === 'login') ? 'Login' : 'Cadastro';
        }

        window.onload = function() {
            <?php if ($aba_ativa === 'cadastro') : ?>
                showTab('cadastro');
            <?php else : ?>
                showTab('login');
            <?php endif; ?>
        };
    </script>
</body>
</html>