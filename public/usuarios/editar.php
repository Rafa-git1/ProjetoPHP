<?php 
/**
 * Edição de Usuário
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

// Dependências e Lógica
require_once __DIR__ . '/../../app/dao/UsuarioDAO.php';
$usuarioDAO = new UsuarioDAO();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['msg_erro'] = "ID do usuário não fornecido.";
    header("Location: listar.php");
    exit();
}

// 1. Busca dos dados existentes no banco
$usuario = $usuarioDAO->lerPorId($id);

if (!$usuario) {
    $_SESSION['msg_erro'] = "Usuário não encontrado para edição.";
    header("Location: listar.php");
    exit();
}

// 2. Preencher com dados de sessão (se houver erro após POST) ou dados do banco
$formData = $_SESSION['form_data'] ?? [
    'id' => $usuario['id'],
    'nome' => $usuario['nome'],
    'email' => $usuario['email'],
    'nivel_acesso' => $usuario['nivel_acesso'],
];

$pageTitle = "Editar Usuário: " . $formData['nome']; 

require_once __DIR__ . '/../../includes/header.php'; 
?>

<div class="page-header">
    <h1>Editar Usuário: <?= htmlspecialchars($formData['nome']); ?></h1>
    <a href="listar.php" class="button button-secondary">
        <span class="icon">&#x2190;</span> Voltar para a Listagem
    </a>
</div>

<div class="form-card">
    <form action="../../actions/usuario_editar.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($formData['id']); ?>">

        <h2>Dados de Acesso</h2>
        <p class="form-tip">Preencha apenas os campos que deseja alterar. A senha é opcional.</p>

        <div class="form-group">
            <label for="nome">Nome Completo: (Obrigatório)</label>
            <input type="text" id="nome" name="nome" required 
                maxlength="255" value="<?= htmlspecialchars($formData['nome']); ?>">
        </div>
        
        <div class="form-group">
            <label for="email">E-mail: (Obrigatório)</label>
            <input type="email" id="email" name="email" required 
                maxlength="255" value="<?= htmlspecialchars($formData['email']); ?>">
        </div>
        
        <div class="form-group">
            <label for="nivel_acesso">Nível de Acesso: (Obrigatório)</label>
            <select id="nivel_acesso" name="nivel_acesso" required>
                <option value="admin" <?= ($formData['nivel_acesso'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                <option value="user" <?= ($formData['nivel_acesso'] == 'user') ? 'selected' : ''; ?>>Usuário Comum</option>
            </select>
        </div>

        <hr>

        <h2>Alterar Senha (Opcional)</h2>
        <div class="form-group">
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" minlength="6">
        </div>

        <div class="form-group">
            <label for="confirmar_senha">Confirmar Nova Senha:</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" minlength="6">
        </div>
        
        <div class="button-group">
            <button type="submit" class="button button-primary">
                <span class="icon">&#x2713;</span> Salvar Alterações
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
