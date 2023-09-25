<?php

namespace Periclesphp\SerenattoAlura\Repositorio;

use PDO;
use Periclesphp\SerenattoAlura\Modelo\Produto;

class ProdutoRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function formatarObjeto(array $produto): Produto
    {
        return new Produto(
            $produto['id'],
            $produto['tipo'],
            $produto['nome'],
            $produto['descricao'],
            $produto['preco'],
            $produto['imagem']
        );
    }

    public function opcoesCafe(): array
    {
        $sql = "SELECT * FROM produtos WHERE tipo = 'Café' ORDER BY preco";
        $stmt = $this->pdo->query($sql);
        $produtosCafe = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dadosCafe = array_map(
            function ($cafe) {
                return $this->formatarObjeto($cafe);
            }, $produtosCafe);

        return $dadosCafe;
    }

    public function opcoesAlmoco(): array
    {
        $sql = "SELECT * FROM produtos WHERE tipo = 'Almoço' ORDER BY preco";
        $stmt = $this->pdo->query($sql);
        $produtosAlmoco = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dadosAlmoco = array_map(
            function ($almoco) {
                return $this->formatarObjeto($almoco);
            }, $produtosAlmoco);

        return $dadosAlmoco;
    }

    public function buscarTodos(): array
    {
        $sql = "SELECT * FROM produtos ORDER BY preco";
        $stmt = $this->pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $todosOsDados = array_map(function ($produto) {
            return $this->formatarObjeto($produto);
        }, $dados);

        return $todosOsDados;
    }

    public function deletar (int $id): void 
    {
        $sql = 'DELETE FROM produtos WHERE id = ?;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }

    public function salvar(Produto $produto, bool $criar): void 
    {
        if ($criar) {
            $this->insert($produto);
            return;
        }

        $this->update($produto);
    }

    private function insert(Produto $produto): void 
    {
        $sql = 'INSERT INTO produtos (tipo, nome, descricao, preco, imagem) VALUES (?, ?, ?, ?, ?);';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $produto->getTipo());
        $stmt->bindValue(2, $produto->getNome());
        $stmt->bindValue(3, $produto->getDescricao());
        $stmt->bindValue(4, $produto->getPreco());
        $stmt->bindValue(5, $produto->getImagem());
        $stmt->execute();
    }

    private function update(Produto $produto): void
    {
        if (!empty($_FILES['imagem']['name'])) {
            $imagem = uniqid() . $_FILES['imagem']['name'];
        } else {
            $imagem = null;
        }

        $produto->atualizarProduto(
            $_POST['tipo'],
            $_POST['nome'],
            $_POST['descricao'],
            $_POST['preco'],
            $imagem
        );

        if (!is_null($imagem)) {
            move_uploaded_file($_FILES['imagem']['tmp_name'], $produto->getImagemDiretorio());
        }

        $sql = 'UPDATE produtos SET tipo = ?, nome = ?, descricao = ?, preco = ?, imagem = ? WHERE id = ?;';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $produto->getTipo());
        $stmt->bindValue(2, $produto->getNome());
        $stmt->bindValue(3, $produto->getDescricao());
        $stmt->bindValue(4, $produto->getPreco());
        $stmt->bindValue(5, $produto->getImagem());
        $stmt->bindValue(6, $produto->getId());
        $stmt->execute();
    }

    public function buscar(int $id): Produto 
    {
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $this->formatarObjeto($dados);
    }
}