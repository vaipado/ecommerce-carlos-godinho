<?php
class Carrinho {
    private $pdo;
    private $id_usuario;
    private $id_carrinho;

    public function __construct($pdo, $id_usuario) {
        $this->pdo = $pdo;
        $this->id_usuario = $id_usuario;
        $this->verificarOuCriarCarrinho();
    }

    private function verificarOuCriarCarrinho() {
        $stmt = $this->pdo->prepare("SELECT id FROM carrinho WHERE id_usuario = :id_usuario");
        $stmt->bindParam(':id_usuario', $this->id_usuario);
        $stmt->execute();
        $carrinho = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$carrinho) {
            $stmt = $this->pdo->prepare("INSERT INTO carrinho (id_usuario) VALUES (:id_usuario)");
            $stmt->bindParam(':id_usuario', $this->id_usuario);
            $stmt->execute();
            $this->id_carrinho = $this->pdo->lastInsertId();
        } else {
            $this->id_carrinho = $carrinho['id'];
        }
    }

    public function adicionarProduto($id_produto) {
        $stmt = $this->pdo->prepare("SELECT * FROM itens_carrinho WHERE id_carrinho = :id_carrinho AND id_produto = :id_produto");
        $stmt->bindParam(':id_carrinho', $this->id_carrinho);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            $stmt = $this->pdo->prepare("UPDATE itens_carrinho SET quantidade = quantidade + 1 WHERE id_carrinho = :id_carrinho AND id_produto = :id_produto");
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO itens_carrinho (id_carrinho, id_produto, quantidade, preco_unitario) VALUES (:id_carrinho, :id_produto, 1, (SELECT preco FROM produtos WHERE id = :id_produto))");
        }
        $stmt->bindParam(':id_carrinho', $this->id_carrinho);
        $stmt->bindParam(':id_produto', $id_produto);
        $stmt->execute();
    }

    public function removerProduto($id_item) {
        $stmt = $this->pdo->prepare("DELETE FROM itens_carrinho WHERE id = :id_item");
        $stmt->bindParam(':id_item', $id_item);
        $stmt->execute();
    }

    public function obterItensCarrinho() {
        $stmt = $this->pdo->prepare("SELECT i.id, i.id_produto, p.nome, p.preco, p.imagem, i.quantidade FROM itens_carrinho i JOIN produtos p ON i.id_produto = p.id WHERE i.id_carrinho = :id_carrinho");
        $stmt->bindParam(':id_carrinho', $this->id_carrinho);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularTotal() {
        $itens = $this->obterItensCarrinho();
        $total = 0;
        foreach ($itens as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
        return $total;
    }

    public function getIdCarrinho() {
        return $this->id_carrinho;
    }
}
