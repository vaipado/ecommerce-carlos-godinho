<?php
class Produto
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Busca um produto pelo ID.
     *
     * @param int $id ID do produto.
     * @return array Dados do produto.
     */
    public function buscarProdutoPorId($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produto: " . $e->getMessage());
        }
    }

    /**
     * Lista todos os produtos com suporte a paginação.
     *
     * @param int $pagina Número da página.
     * @param int $itens_por_pagina Quantidade de itens por página.
     * @return array Lista de produtos.
     */
    public function listarProdutos($pagina = 1, $itens_por_pagina = 6)
    {
        try {
            $offset = ($pagina - 1) * $itens_por_pagina;
            $stmt = $this->pdo->prepare("SELECT * FROM produtos ORDER BY nome ASC LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $itens_por_pagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar produtos: " . $e->getMessage());
        }
    }

    /**
     * Adiciona um novo produto.
     *
     * @param string $nome Nome do produto.
     * @param string $descricao Descrição do produto.
     * @param float $preco Preço do produto.
     * @param array $imagem Dados do arquivo de imagem.
     * @return bool True se o produto foi adicionado com sucesso.
     */
    public function adicionarProduto($nome, $descricao, $preco, $imagem)
    {
        try {
            // Validações
            if (empty($nome) || empty($descricao) || empty($preco) || empty($imagem['name'])) {
                throw new Exception("Todos os campos são obrigatórios.");
            }

            if (!is_numeric($preco) || $preco <= 0) {
                throw new Exception("O preço deve ser um número positivo.");
            }

            // Validação da imagem
            $permitidos = ['jpg', 'jpeg', 'png', 'gif'];
            $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));

            if (!in_array($extensao, $permitidos)) {
                throw new Exception("Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.");
            }

            if ($imagem['size'] > 5000000) { // 5MB
                throw new Exception("O arquivo de imagem deve ter no máximo 5MB.");
            }

            // Diretório onde as imagens serão salvas
            $diretorioImagens = "../public/images/";

            // Gera um nome único para a imagem
            $nomeImagem = uniqid() . '-' . basename($imagem['name']);
            $caminhoImagem = $diretorioImagens . $nomeImagem;

            // Move a imagem para o diretório de upload
            if (move_uploaded_file($imagem['tmp_name'], $caminhoImagem)) {
                echo "Imagem salva em: " . $caminhoImagem; // Debugging
                // Insere os dados do produto no banco de dados
                $stmt = $this->pdo->prepare("INSERT INTO produtos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)");
                return $stmt->execute([$nome, $descricao, $preco, $nomeImagem]);
            } else {
                throw new Exception("Erro ao mover a imagem para o diretório.");
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao adicionar produto: " . $e->getMessage());
        }
    }

    /**
     * Exclui um produto pelo ID.
     *
     * @param int $id ID do produto.
     * @return bool True se o produto foi excluído com sucesso.
     */
    public function excluirProduto($id)
    {
        try {
            // Buscar imagem para excluir do servidor
            $stmt = $this->pdo->prepare("SELECT imagem FROM produtos WHERE id = ?");
            $stmt->execute([$id]);
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produto) {
                $caminhoImagem = "../public/images/" . $produto['imagem'];
                if (file_exists($caminhoImagem)) {
                    unlink($caminhoImagem);
                }

                // Excluir do banco de dados
                $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = ?");
                return $stmt->execute([$id]);
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir produto: " . $e->getMessage());
        }
    }

    /**
     * Conta o total de produtos no banco de dados.
     *
     * @return int Total de produtos.
     */
    public function contarProdutos()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM produtos");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar produtos: " . $e->getMessage());
        }
    }
}
