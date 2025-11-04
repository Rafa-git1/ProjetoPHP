<?php
class Usuario
{
    private $id;
    private $nome;
    private $email;
    private $senha_hash;
    private $nivel_acesso;
    private $data_cadastro;

    // --- Construtor ---
    public function __construct($nome = null, $email = null, $senha_hash = null, $nivel_acesso = 'editor')
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->senha_hash = $senha_hash;
        $this->nivel_acesso = $nivel_acesso;
    }

    // --- Getters e Setters ---
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getSenhaHash() { return $this->senha_hash; }
    public function getNivelAcesso() { return $this->nivel_acesso; }
    public function getDataCadastro() { return $this->data_cadastro; }

    public function setId($id) { $this->id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setEmail($email) { $this->email = $email; }
    public function setSenhaHash($senha_hash) { $this->senha_hash = $senha_hash; }
    public function setNivelAcesso($nivel_acesso) { $this->nivel_acesso = $nivel_acesso; }

    // --- Métodos auxiliares ---
    public function verificarSenha($senha)
    {
        return password_verify($senha, $this->senha_hash);
    }
}
?>
