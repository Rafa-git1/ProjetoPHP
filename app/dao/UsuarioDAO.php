<?php
/**
 * UsuarioDAO.php
 * Data Access Object para a tabela 'usuarios'.
 * Requer o arquivo de conexão com o banco de dados.
 */

// CORREÇÃO: O caminho foi ajustado para buscar o arquivo dentro de 'app/config/'
// O caminho agora é: app/dao/ -> (sobe) app/ -> (entra) config/Conexao.php
require_once __DIR__ . '/../config/Conexao.php'; 

class UsuarioDAO {
    private $conexao;

    public function __construct() {
        // Inicializa a conexão com o banco de dados
        $this->conexao = Conexao::getConexao(); 
    }

    /**
     * Lista todos os usuários.
     * @return array
     */
    public function listarTodos() {
        $sql = "SELECT id, nome, email, nivel_acesso FROM usuarios ORDER BY nome ASC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um usuário pelo ID.
     * @param int $id
     * @return array|null
     */
    public function lerPorId($id) {
        $sql = "SELECT id, nome, email, nivel_acesso FROM usuarios WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um usuário pelo email (usado no login).
     * Usa o nome da coluna 'senha_hash' conforme script do banco.
     * @param string $email
     * @return array|false Retorna o usuário como array associativo ou false se não encontrado.
     */
    public function buscarPorEmail($email) {
        // CORREÇÃO: Usando 'senha_hash' no SELECT
        $sql = "SELECT id, nome, email, senha_hash, nivel_acesso FROM usuarios WHERE email = :email";
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([':email' => $email]);
            // O fetch() retorna false se não encontrar, o que é útil para o processo de login
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (\PDOException $e) {
             // Logar o erro em produção, aqui apenas retornamos false
             return false;
        }
    }

    /**
     * Cadastra um novo usuário.
     * Usa o nome da coluna 'senha_hash' conforme script do banco.
     * @param string $nome
     * @param string $email
     * @param string $senhaHash (hash)
     * @param string $nivel_acesso
     * @return bool
     */
    public function cadastrar($nome, $email, $senhaHash, $nivel_acesso) {
        // CORREÇÃO: 'senhaA' alterado para 'senha_hash' na query
        $sql = "INSERT INTO usuarios (nome, email, senha_hash, nivel_acesso) VALUES (:nome, :email, :senhaHash, :nivel_acesso)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        // CORREÇÃO: Placeholder para a hash da senha deve ser :senhaHash (ou o nome que você usou na query)
        $stmt->bindParam(':senhaHash', $senhaHash); 
        $stmt->bindParam(':nivel_acesso', $nivel_acesso);
        return $stmt->execute();
    }

    /**
     * Atualiza os dados de um usuário (sem a senha).
     * @param int $id
     * @param string $nome
     * @param string $email
     * @param string $nivel_acesso
     * @return bool
     */
    public function atualizar($id, $nome, $email, $nivel_acesso) {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, nivel_acesso = :nivel_acesso WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nivel_acesso', $nivel_acesso);
        return $stmt->execute();
    }

    /**
     * Atualiza a senha de um usuário.
     * Usa o nome da coluna 'senha_hash' conforme script do banco.
     * @param int $id
     * @param string $senhaHash
     * @return bool
     */
    public function atualizarSenha($id, $senhaHash) {
        // CORREÇÃO: 'senhaA' alterado para 'senha_hash' na query
        $sql = "UPDATE usuarios SET senha_hash = :senhaHash WHERE id = :id"; 
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':senhaHash', $senhaHash);
        return $stmt->execute();
    }

    /**
     * Exclui um usuário.
     * @param int $id
     * @return bool
     */
    public function deletar($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Verifica se um e-mail já existe (útil para cadastro e edição).
     * @param string $email
     * @param int|null $ignoreId
     * @return bool
     */
    public function emailExiste($email, $ignoreId = null) {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        if ($ignoreId) {
            $sql .= " AND id != :ignoreId";
        }
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':email', $email);
        if ($ignoreId) {
            $stmt->bindParam(':ignoreId', $ignoreId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
