<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .fundoBody {
            height: 330px;
            object-fit: cover;
        }

        main {
            position: relative;
            min-height: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .row-produtos {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .row-produtos a {
            display: flex;
            flex-direction: column;
            flex: 1 1 30%;
            color: inherit;
            text-decoration: none;
        }

        .row-produtos a:hover {
            color: inherit;
        }

        .col {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 250px;
            min-width: 200px;
            width: 100%;
            border: 1px solid rgb(155, 155, 155);
            transition: 0.6s ease;
            overflow: hidden;
            border-radius: 5px;
            padding: 10px;
        }

        .col:hover {
            border: 1px solid #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
        }

        .col:hover img {
            scale: 1.05;
        }

        .image-produto {
            position: relative;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: 0.5s;
        }

        .card-text {
            background-color: white;
            position: absolute;
            left: 4%;
            bottom: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.7);
            font-weight: bold;
            padding: 5px;
        }

        .card-title {
            font-size: 14px;
            color: #17100C;
            margin: 10px 0;
            font-weight: 600;
            text-align: center;
        }

        .btn-danger {
            text-decoration: none;
            width: 150px;
            background-color: #A91013;
        }

        .d-flex.justify-content-between.align-items-center {
            position: absolute;
            left: 93%;
            top: 0
        }
    </style>
</head>

<body>
    <?php
    session_start();
    include('../src/config/conexao.php');
    require_once('../src/classes/Produto.php');

    $produtoService = new Produto($pdo);
    $verTodos = isset($_GET['ver_todos']) && $_GET['ver_todos'] == '1';
    $quantidade = $verTodos ? 9999 : 3;

    try {
        $produtos = $produtoService->listarProdutos(1, $quantidade);
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
        $produtos = [];
    }

    include('../templates/header.php');
    ?>

    <img class="fundoBody" src="../public/images/fundoVermelhoPreto.jpeg" alt="">

    <main class="container my-5">
        <div class="d-flex justify-content-between align-items-center w-100">
            <a href="<?= $verTodos ? 'index.php' : '?ver_todos=1' ?>" class="btn btn-danger">
                <?= $verTodos ? 'Menos produtos' : 'Mais produtos' ?>
            </a>
        </div>

        <?php if (empty($produtos)): ?>
            <p>Não há produtos disponíveis no momento.</p>
        <?php else: ?>
            <div class="row-produtos">
                <?php foreach ($produtos as $produto): ?>
                    <a href="produto.php?id=<?= $produto['id'] ?>">
                        <div class="col">
                            <div class="image-produto">
                                <img src="../public/images/<?= htmlspecialchars($produto['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                <p class="card-text">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                            </div>
                            <p class="card-title"> <?= htmlspecialchars($produto['nome']) ?> </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include('../templates/footer.php'); ?>
</body>
</html>