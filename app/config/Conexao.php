<?php
/**
 * Conexao.php
 * Classe para gerenciar a conexão com o banco de dados MySQL usando PDO (Padrão Singleton).
 * Protege contra múltiplas conexões.
 */
class Conexao {
    private static $instance;

    // Configurações do banco de dados
    private static $host = 'localhost';
    private static $db   = 'cadastros'; // MUDAR PARA O NOME DO SEU DB
    private static $user = 'root'; // MUDAR SE NECESSÁRIO
    private static $pass = ''; // MUDAR SE NECESSÁRIO
    private static $charset = 'utf8mb4';

    // Construtor privado para impedir instâncias externas
    private function __construct() {}

    /**
     * Retorna a instância única da conexão PDO.
     * @return PDO
     */
    public static function getConexao() {
        if (!isset(self::$instance)) {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";port=3307;charset=" . self::$charset;
            
            // Opções do PDO
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em erros
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Padrão de retorno: array associativo
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Garante o uso de prepared statements nativos
            ];

            try {
                self::$instance = new PDO($dsn, self::$user, self::$pass, $options);
            } catch (\PDOException $e) {
                // Em um ambiente de produção, logar o erro e mostrar uma mensagem genérica.
                // Aqui, lançamos a exceção para debug.
                die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}