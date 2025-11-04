<?php
/**
 * CategoriaDAO.php
 * Data Access Object para a tabela 'categorias'.
 * Requer o arquivo de conexão com o banco de dados.
 */

require_once __DIR__ . '/../config/Conexao.php'; 

class CategoriaDAO {
    private $conexao;

    public function __construct() {
        // Inicializa a conexão com o banco de dados
        $this->conexao = Conexao::getConexao(); 
    }

    /**
     * Cadastra uma nova categoria.
     * @param string $nome O nome da categoria a ser cadastrada.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function cadastrar($nome) {
        $sql = "INSERT INTO categorias (nome) VALUES (:nome)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    }

    /**
     * Lista todas as categorias.
     * @return array Retorna um array de categorias.
     */
    public function listarTodos() {
        $sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma categoria pelo ID.
     * @param int $id O ID da categoria.
     * @return array|null Retorna a categoria como array associativo ou null se não encontrada.
     */
    public function lerPorId($id) {
        $sql = "SELECT id, nome FROM categorias WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza o nome de uma categoria.
     * @param int $id O ID da categoria a ser atualizada.
     * @param string $nome O novo nome da categoria.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function atualizar($id, $nome) {
        $sql = "UPDATE categorias SET nome = :nome WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        return $stmt->execute();
    }

    /**
     * Exclui uma categoria.
     * @param int $id O ID da categoria a ser excluída.
     * @return bool Retorna true em caso de sucesso, false em caso de falha.
     */
    public function deletar($id) {
        $sql = "DELETE FROM categorias WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Verifica se um nome de categoria já existe (útil para cadastro e edição).
     * @param string $nome O nome a ser verificado.
     * @param int|null $ignoreId Opcional: ID a ser ignorado na verificação (para atualizações).
     * @return bool Retorna true se o nome já existe, false caso contrário.
     */
    public function nomeExiste($nome, $ignoreId = null) {
        $sql = "SELECT COUNT(*) FROM categorias WHERE nome = :nome";
        if ($ignoreId) {
            $sql .= " AND id != :ignoreId";
        }
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        if ($ignoreId) {
            $stmt->bindParam(':ignoreId', $ignoreId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
