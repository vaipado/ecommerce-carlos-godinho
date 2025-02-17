<?php
class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($usuario, $senha, $admin_attempt = false) {
        // Credenciais do administrador
        $admin_fixo = [
            "usuario" => "admin",
            "senha" => "1234"
        ];

        if ($admin_attempt) {
            // Verifica se o usuário e senha correspondem ao admin
            if ($usuario === $admin_fixo['usuario'] && $senha === $admin_fixo['senha']) {
                // Limpa a sessão de usuário comum, se existir
                unset($_SESSION['user_id']);
                unset($_SESSION['user_email']);
                unset($_SESSION['user_nome']);

                // Define a sessão do administrador
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_usuario'] = $admin_fixo['usuario'];
                header('Location: admin.php');
                exit();
            } else {
                return "Acesso negado.";
            }
        } else {
            // Limpa a sessão de administrador, se existir
            unset($_SESSION['admin_logged_in']);
            unset($_SESSION['admin_usuario']);

            // Lógica de login para usuários comuns
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$usuario]); // Aqui $usuario é o email do usuário comum
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($senha, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_nome'] = $user['nome'];
                header('Location: index.php');
                exit();
            } else {
                return "Credenciais inválidas.";
            }
        }
    }

    public function cadastrar($nome, $email, $senha, $confirmar_senha) {
        // Validação de senha
        if ($senha !== $confirmar_senha) {
            return "As senhas não coincidem.";
        }

        // Verifica se o email já está cadastrado
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return "Email já cadastrado.";
        }

        // Insere o novo usuário
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha_hash]);

        return "Cadastro realizado com sucesso!";
    }
}
?>