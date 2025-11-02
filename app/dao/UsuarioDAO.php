<?php
/**
 * UsuarioDAO.php
 * Objeto de Acesso a Dados para a tabela 'usuarios'.
 */
require_once __DIR__ . '/../config/Conexao.php'; // Garante que a Conexao está carregada

class UsuarioDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    /**
     * Busca um usuário pelo email (usado no login).
     * @param string $email
     * @return array|false Retorna o usuário como array associativo ou false se não encontrado.
     */
    public function buscarPorEmail($email) {
        $sql = "SELECT id, nome, email, senha_hash, nivel_acesso FROM usuarios WHERE email = :email";
        try {
            $stmt = $this->conexao->prepare($sql);
            // Uso de prepared statements para prevenir SQL Injection
            $stmt->execute([':email' => $email]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            // Em um projeto real, você logaria o erro.
            // Para o projeto acadêmico, pode mostrar:
            // die("Erro ao buscar usuário: " . $e->getMessage());
            return false;
        }
    }

    // MÉTODOS CRUD COMPLETOS PARA USUÁRIOS (ex: criar, atualizar, listar, deletar) DEVEM SER ADICIONADOS AQUI
    // Exemplo:
    public function listarTodos() {
        $sql = "SELECT id, nome, email, nivel_acesso FROM usuarios ORDER BY nome";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll();
    }
}