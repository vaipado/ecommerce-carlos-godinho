<?php
include('../src/config/conexao.php');
include('../src/classes/Produto.php'); // Caminho da classe

session_start(); // Certifique-se de iniciar a sessão

$produtoObj = new Produto($pdo);

// Verifica se o ID foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Produto não encontrado.";
    exit;
}

$id = (int)$_GET['id'];
$produto = $produtoObj->buscarProdutoPorId($id);

if (!$produto) {
    echo "Produto não encontrado.";
    exit;
}

// Incluindo o header
include('../templates/header.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto['nome']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<main>
    <div class="produto-container">
        <h1><?= htmlspecialchars($produto['nome']) ?></h1>
        <div class="produto-detalhes">
            <img src="../public/images/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
            <div class="produto-info">
                <p><strong>Descrição:</strong> <?= htmlspecialchars($produto['descricao']) ?></p>
                <p><strong>Preço:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
            </div>
        </div>
        
        <!-- Verificar se o usuário é administrador e redirecionar para admin.php -->
        <a href="<?php echo isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true ? 'admin.php' : 'index.php'; ?>" class="btn btn-primary">
            Voltar à lista de produtos
        </a>
    </div>
</main>

<?php include('../templates/footer.php'); ?>

<style>
    :root {
        --cor-secundaria: #28a745;
        --cor-fundo: #f8f9fa;
        --cor-texto: #343a40;
        --cor-texto-claro: #6c757d;
        --cor-fundo-card: #ffffff;
        --sombra-card: rgba(0, 0, 0, 0.1);
        --font-principal: 'Roboto', sans-serif;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: var(--cor-fundo);
        color: var(--cor-texto);
        line-height: 1.6;
        padding: 20px;
    }

    header h1 {
        font-size: 2.5rem;
        font-weight: bold;
    }

    /* Main Container */
    .produto-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
        background-color: var(--cor-fundo-card);
        border-radius: 12px;
        box-shadow: 0 10px 20px var(--sombra-card);
        text-align: center;
    }

    .produto-container h3 {
        font-size: 2rem;
        margin-bottom: 20px;
    }

    .produto-detalhes {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .produto-detalhes img {
        width: 100%;
        max-width: 350px;
        border-radius: 8px;
        object-fit: cover;
        box-shadow: 0 5px 15px var(--sombra-card);
        transition: box-shadow 0.3s ease;
    }

    .produto-detalhes img:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .produto-info {
        max-width: 500px;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 5px 15px var(--sombra-card);
    }

    .produto-info p {
        font-size: 1.125rem;
        margin-bottom: 15px;
        color: var(--cor-texto-claro);
    }

    .produto-info strong {
        font-weight: bold;
        color: var(--cor-texto);
    }

    .produto-info p:last-child {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .btn-primary {
        padding: 12px 25px;
        background-color: #A91013;
        color: white;
        border-radius: 8px;
        font-size: 1.1rem;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
        text-align: center;
        margin-top: 20px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .produto-detalhes {
            flex-direction: column;
            align-items: center;
        }

        .produto-detalhes img {
            max-width: 300px;
            margin-bottom: 20px;
        }

        .produto-info {
            max-width: 90%;
        }

        .produto-info p {
            font-size: 1rem;
        }

        .btn-primary {
            font-size: 1rem;
            padding: 10px 20px;
        }
    }
</style>

</body>
</html>
