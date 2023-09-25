<?php

namespace Periclesphp\SerenattoAlura\Modelo;

class Produto
{
    private ?int $id;
    private string $tipo;
    private string $nome;
    private string $descricao;
    private float $preco;
    private string $imagem;

    public function __construct(?int $id, string $tipo, string $nome, string $descricao, float $preco, ?string $imagem)
    {
        $this->id = $id;
        $this->tipo = $tipo;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
       
        $this->setImagem($imagem);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getImagem(): string
    {
        return $this->imagem;
    }

    public function getImagemDiretorio(): string
    {
        return "img/$this->imagem";
    }

    public function setImagem(?string $imagem): void
    {
        if (!is_null($imagem)) {
            $this->imagem = $imagem;

            return;
        }

        $this->imagem = "logo-serenatto.png";
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function getPrecoFormatado(): string
    {
        return "R$ " . number_format($this->preco, 2);
    }

    public function atualizarProduto(string $tipo, string $nome, string $descricao, float $preco, ?string $imagem): void
    {
        $this->tipo = $tipo;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;

        if (is_null($imagem)) {
            return;
        }

        $this->imagem = $imagem;
    }
}
