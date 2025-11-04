<?php
class Produto
{
    private $id;
    private $nome;
    private $descricao;
    private $preco;
    private $categoria_id;
    private $data_cadastro;

    public function __construct($nome = null, $descricao = null, $preco = null, $categoria_id = null)
    {
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria_id = $categoria_id;
    }

    // --- Getters e Setters ---
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getDescricao() { return $this->descricao; }
    public function getPreco() { return $this->preco; }
    public function getCategoriaId() { return $this->categoria_id; }
    public function getDataCadastro() { return $this->data_cadastro; }

    public function setId($id) { $this->id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }
    public function setPreco($preco) { $this->preco = $preco; }
    public function setCategoriaId($categoria_id) { $this->categoria_id = $categoria_id; }
}
?>
