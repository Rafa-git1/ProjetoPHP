<?php 
// Define a raiz do projeto de forma robusta.
// Como o dashboard.php está em /public/, ele só precisa subir um nível (../)
// dirname(__DIR__) aponta para /PROJETO-PHP (a raiz do projeto)
$projectRoot = dirname(__DIR__);

// Variável para URL base de links internos
$base_url_public = '/PROJETOPHP/public/';

// 1. Checagem de Autenticação
require_once $projectRoot . '/includes/auth_check.php';

// O header.php inclui o início do HTML, CSS, e abre a div principal
require_once $projectRoot . '/includes/header.php'; 
?>

<h1>Bem-vindo(a) ao Dashboard, <?= htmlspecialchars($_SESSION['user_nome'] ?? 'Usuário'); ?>!</h1>

<!-- A mensagem de sucesso do login é exibida no header.php, então podemos removê-la daqui. -->
<p>Seu nível de acesso é: <strong><?= htmlspecialchars($_SESSION['user_nivel']); ?></strong></p>

<div class="card-list">
    
    <div class="card">
        <h2>Produtos</h2>
        <p>Gerencie o estoque e o catálogo de produtos disponíveis.</p>
        <a href="<?= $base_url_public; ?>produtos/listar.php" class="button">Gerenciar Produtos</a>
    </div>

    <?php if (isset($_SESSION['user_nivel']) && $_SESSION['user_nivel'] === 'admin'): ?>
        <div class="card">
            <h2>Usuários</h2>
            <p>Cadastro, edição e controle de acesso dos usuários do sistema.</p>
            <a href="<?= $base_url_public; ?>usuarios/listar.php" class="button">Gerenciar Usuários (Admin)</a>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <h2>Categorias</h2>
        <p>Cadastro de categorias para relacionar com os produtos.</p>
        <a href="<?= $base_url_public; ?>categorias/listar.php" class="button">Gerenciar Usuários (Admin)</a>
    </div>

</div>

<style>
/* Estilos específicos para o dashboard */
.card-list {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 30px;
}

.card {
    flex: 1 1 300px; /* Cresce e ocupa no mínimo 300px */
    background-color: var(--card-background);
    padding: 30px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    transition: transform 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.card h2 {
    color: var(--primary-dark);
    margin-bottom: 15px;
}

.card p {
    margin-bottom: 20px;
    color: var(--text-color);
}

/* Garante que o botão no card use a largura completa no mobile */
@media (max-width: 768px) {
    .card a.button {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php 
// O footer.php fecha o div principal e o body
require_once $projectRoot . '/includes/footer.php'; 
?>
