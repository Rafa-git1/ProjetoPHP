<?php 
/**
 * Cadastro de Usuário
 * Requer nível de acesso 'admin'. Aplica estilo de formulário.
 */
// Caminho corrigido para dois níveis acima
require_once __DIR__ . '/../../includes/auth_check.php'; 

// Checagem de Nível de Acesso (APENAS ADMIN PODE ACESSAR)
if ($_SESSION['user_nivel'] !== 'admin') {
    $_SESSION['msg_erro'] = "Acesso negado. Você precisa ser administrador.";
    header("Location: ../dashboard.php");
    exit();
}

$pageTitle = "Cadastrar Usuário"; 
$formData = $_SESSION['form_data'] ?? [];

require_once __DIR__ . '/../../includes/header.php'; 
?>

<div class="page-header">
    <h1>Cadastrar Novo Usuário</h1>
    <a href="listar.php" class="button button-secondary">
        <span class="icon">&#x2190;</span> Voltar para a Listagem
    </a>
</div>

<div class="form-card">
    <form action="../../actions/usuario_cadastrar.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome Completo: (Obrigatório)</label>
            <input type="text" id="nome" name="nome" required 
                maxlength="255" value="<?= htmlspecialchars($formData['nome'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="email">E-mail: (Obrigatório)</label>
            <input type="email" id="email" name="email" required 
                maxlength="255" value="<?= htmlspecialchars($formData['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="senha">Senha: (Obrigatório)</label>
            <input type="password" id="senha" name="senha" required minlength="6">
        </div>

        <div class="form-group">
            <label for="confirmar_senha">Confirmar Senha: (Obrigatório)</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
        </div>
        
        <div class="form-group">
            <label for="nivel_acesso">Nível de Acesso: (Obrigatório)</label>
            <select id="nivel_acesso" name="nivel_acesso" required>
                <option value="">Selecione o Nível</option>
                <option value="admin" <?= (isset($formData['nivel_acesso']) && $formData['nivel_acesso'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                <option value="user" <?= (isset($formData['nivel_acesso']) && $formData['nivel_acesso'] == 'user') ? 'selected' : ''; ?>>Usuário Comum</option>
            </select>
        </div>
        
        <div class="button-group">
            <button type="submit" class="button button-primary">
                <span class="icon">&#x2713;</span> Salvar Usuário
            </button>
            <a href="listar.php" class="button button-secondary">
                <span class="icon">&#x2190;</span> Cancelar e Voltar
            </a>
        </div>
    </form>
</div>

<?php 
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
require_once __DIR__ . '/../../includes/footer.php'; 
?>
