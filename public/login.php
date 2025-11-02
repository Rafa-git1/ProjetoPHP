<?php 
// Define a raiz do projeto de forma robusta.
// O login.php está em /public/, então precisa subir um nível (../)
$projectRoot = dirname(__DIR__);

// 1. Checagem de Sessão (iniciada no header.php)
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// Se já estiver logado, redireciona para o dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Usamos o caminho absoluto para o redirecionamento
    header("Location: /PROJETOPHP/public/dashboard.php");
    exit();
}

// 2. Inclusão do Header (inclui <head>, CSS, e abre div.container)
$pageTitle = "Login"; // Define o título da página
require_once $projectRoot . '/includes/header.php'; 
?>

<div class="login-container">
    <div class="form-card login-form-card">
        <h1>Acesso ao Sistema</h1>

        <!-- Mensagens de erro/sucesso são exibidas automaticamente pelo header.php -->

        <form action="../actions/processa_login.php" method="POST">
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" class="primary-button">Entrar</button>
        </form>
    </div>
</div>

<?php 
// 3. Inclusão do Footer (fecha div.container e </body>)
require_once $projectRoot . '/includes/footer.php'; 
?>

<style>
/* Estilos Específicos para centralizar o formulário de Login */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    /* Ajusta a altura mínima para garantir que o formulário fique no meio */
    /* Subtrai o header (aprox 70px) e o footer (aprox 70px) */
    min-height: calc(100vh - 140px); 
    padding-top: 50px; /* Adiciona um espaço superior de segurança */
}

.login-form-card {
    max-width: 400px; /* Limita a largura para melhor visualização */
    width: 100%;
    text-align: center;
}

.login-form-card h1 {
    font-size: 2em;
    border-bottom: none;
    margin-bottom: 30px;
    color: var(--primary-color);
}

.login-form-card .form-group {
    text-align: left;
}
</style>
