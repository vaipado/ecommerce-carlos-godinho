<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aba de Administração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/styleAdmin.css">
</head>
<?php
session_start();
include('../src/config/conexao.php');
include('../src/classes/Produto.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$produtoObj = new Produto($pdo);
$mensagem = "";

// Adicionar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $imagem = $_FILES['imagem'];

    if ($produtoObj->adicionarProduto($nome, $descricao, $preco, $imagem)) {
        header("Location: admin.php?mensagem=sucesso");
        exit();
    } else {
        $mensagem = "Erro ao adicionar o produto.";
    }
}

// Excluir produto
if (isset($_GET['delete'])) {
    $id_produto = (int)$_GET['delete'];
    if ($produtoObj->excluirProduto($id_produto)) {
        header("Location: admin.php?mensagem=excluido");
        exit();
    } else {
        $mensagem = "Erro ao excluir o produto.";
    }
}

// Listar produtos
$produtos = $produtoObj->listarProdutos();

include('../templates/header.php');
?>

<main class="container my-5">
    <h2 class="mb-4">Gerenciamento de Produtos</h2>

    <?php if (!empty($mensagem)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <button onclick="document.getElementById('form-adicionar').classList.toggle('d-none')" class="btn btn-primary mb-4">Adicionar Novo Produto</button>

    <div id="form-adicionar" class="d-none">
        <h2>Adicionar Novo Produto</h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <label>Nome do Produto: <input type="text" name="nome" required class="form-control mb-2"></label><br>
            <label>Descrição: <textarea name="descricao" required class="form-control mb-2"></textarea></label><br>
            <label>Preço: <input type="number" name="preco" step="0.01" required class="form-control mb-2"></label><br>
            <label>Imagem: <input type="file" name="imagem" accept="image/*" required class="form-control mb-2"></label><br>
            <button type="submit" name="add_product" class="btn btn-success">Adicionar Produto</button>
        </form>
    </div>

    <h2>Produtos Cadastrados</h2>
    <?php if (empty($produtos)): ?>
        <p>Nenhum produto cadastrado ainda.</p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($produtos as $produto): ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="../public/images/<?= htmlspecialchars($produto['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                            <p class="card-text">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                            <p class="card-text"><?= htmlspecialchars(substr($produto['descricao'], 0, 80)) ?>...</p>
                        </div>
                        <div class="card-footer">
                            <!-- Botão para abrir o modal -->
                            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#excluirModal" data-produto-id="<?= $produto['id'] ?>">Excluir</a>
                            <a href="produto.php?id=<?= $produto['id'] ?>" class="btn btn-info">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="excluirModal" tabindex="-1" aria-labelledby="excluirModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excluirModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir este produto?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="admin.php" method="GET">
                    <input type="hidden" name="delete" id="produto_id">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../templates/footer.php'); ?>

<script>
    var excluirModal = document.getElementById('excluirModal');
    excluirModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; // Botão que abriu o modal
        var produtoId = button.getAttribute('data-produto-id'); // Recupera o ID do produto

        // Preenche o campo oculto com o ID do produto
        var modalBodyInput = excluirModal.querySelector('#produto_id');
        modalBodyInput.value = produtoId;
    });
</script>

</html>

<style>
    .hidden {
        display: none;
    }
</style>