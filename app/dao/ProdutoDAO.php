<?php
/**
 * ProdutoDAO.php
 * Objeto de Acesso a Dados (DAO) para a tabela 'produtos'.
 * Implementa CRUD completo com prepared statements.
 */
require_once __DIR__ . '/../config/Conexao.php';

class ProdutoDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    /**
     * CADASTRA um novo produto.
     */
    public function criar($nome, $descricao, $preco, $categoria_id) {
        $sql = "INSERT INTO produtos (nome, descricao, preco, categoria_id) VALUES (:nome, :descricao, :preco, :categoria_id)";
        
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => $preco,
                ':categoria_id' => $categoria_id
            ]);
            return true;
        } catch (\PDOException $e) {
            // Em caso de erro (ex: chave estrangeira inexistente), você pode logar ou tratar.
            error_log("Erro ao criar produto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * LÊ um produto pelo ID.
     */
    public function lerPorId($id) {
        $sql = "SELECT p.*, c.nome as categoria_nome FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.id = :id";
        
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * LÊ TODOS os produtos, com opção de FILTROS (Desafio Obrigatório).
     */
    public function lerTodosComFiltros($nome = null, $categoria_id = null) {
        $sql = "SELECT p.id, p.nome, p.preco, c.nome as categoria_nome, p.data_cadastro 
                FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE 1=1"; // Cláusula base para facilitar a adição de WHERE

        $params = [];

        // Filtro por nome
        if ($nome) {
            $sql .= " AND p.nome LIKE :nome";
            $params[':nome'] = "%" . $nome . "%"; // Permite busca parcial
        }

        // Filtro por categoria
        if ($categoria_id && $categoria_id !== 'todos') {
            $sql .= " AND p.categoria_id = :categoria_id";
            $params[':categoria_id'] = $categoria_id;
        }

        $sql .= " ORDER BY p.nome ASC";
        
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ATUALIZA um produto.
     */
    public function atualizar($id, $nome, $descricao, $preco, $categoria_id) {
        $sql = "UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, categoria_id = :categoria_id WHERE id = :id";
        
        try {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':preco' => $preco,
                ':categoria_id' => $categoria_id,
                ':id' => $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            error_log("Erro ao atualizar produto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETA um produto pelo ID.
     */
    public function deletar($id) {
        $sql = "DELETE FROM produtos WHERE id = :id";
        
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Lista todas as categorias (necessário para os formulários).
     */
    public function listarCategorias() {
        $sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}