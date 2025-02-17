<?php
session_start();
include('../src/config/conexao.php');
include('../src/classes/Carrinho.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$carrinho = new Carrinho($pdo, $_SESSION['user_id']);

// Adicionar produto ao carrinho
if (isset($_GET['adicionar']) && is_numeric($_GET['adicionar'])) {
    if ($carrinho->adicionarProduto((int)$_GET['adicionar'])) {
        $_SESSION['mensagem'] = "Produto adicionado ao carrinho!";
    } else {
        $_SESSION['mensagem'] = "Erro ao adicionar produto.";
    }
    header('Location: carrinho.php');
    exit();
}

// Remover produto do carrinho
if (isset($_GET['remover']) && is_numeric($_GET['remover'])) {
    if ($carrinho->removerProduto((int)$_GET['remover'])) {
        $_SESSION['mensagem'] = "Produto removido do carrinho!";
    } else {
        $_SESSION['mensagem'] = "Erro ao remover produto.";
    }
    header('Location: carrinho.php');
    exit();
}

try {
    $itens_carrinho = $carrinho->obterItensCarrinho();
    $total = $carrinho->calcularTotal();
} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro ao carregar o carrinho.";
    header('Location: carrinho.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <?php include('../templates/header.php'); ?>
    <main>
        <div class="container my-5">
            <h1>Carrinho de Compras</h1>
            <?php if (isset($_SESSION['mensagem'])): ?>
                <div class="alert alert-info"><?php echo $_SESSION['mensagem']; ?></div>
                <?php unset($_SESSION['mensagem']); ?>
            <?php endif; ?>
            <?php if (empty($itens_carrinho)): ?>
                <p>Seu carrinho está vazio.</p>
                <a href="loja.php" class="btn btn-primary">Continuar Comprando</a>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens_carrinho as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
                                <td><?php echo $item['quantidade']; ?></td>
                                <td>R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?></td>
                                <td>
                                    <a href="carrinho.php?remover=<?php echo $item['id']; ?>" class="btn btn-danger">Remover</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h3>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h3>
                <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
                <a href="../public/index.php" class="btn btn-primary">Continuar Comprando</a>
            <?php endif;