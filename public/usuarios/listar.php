<?php 
/**
 * Listagem de Usuários
 * Requer nível de acesso 'admin'. Aplica estilo de tabela.
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
$pageTitle = "Gerenciar Usuários"; 
$usuarioDAO = new UsuarioDAO();
$usuarios = $usuarioDAO->listarTodos();

require_once __DIR__ . '/../../includes/header.php'; 
?>

<div class="page-header">
    <h1><span class="icon">👥</span> Gerenciar Usuários</h1>
    <a href="cadastrar.php" class="button button-primary">
        <span class="icon">&#x271A;</span> Novo Usuário
    </a>
</div>

<?php 
// Mensagens de sucesso/erro são exibidas pelo header.php
?>

<!-- Layout de Tabela Estilizada -->
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Nível</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="5" class="text-center">Nenhum usuário cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id']); ?></td>
                        <td><?= htmlspecialchars($usuario['nome']); ?></td>
                        <td><?= htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <span class="tag tag-<?= $usuario['nivel_acesso'] === 'admin' ? 'admin' : 'user'; ?>">
                                <?= htmlspecialchars(ucfirst($usuario['nivel_acesso'])); ?>
                            </span>
                        </td>
                        <td class="action-buttons">
                            <a href="editar.php?id=<?= $usuario['id']; ?>" title="Editar" class="button button-small button-secondary">
                                <span class="icon">&#x270E;</span> Editar
                            </a>
                            <!-- Botão de exclusão com confirmação JS -->
                            <form action="../../actions/usuario_deletar.php" method="POST" style="display:inline-block;"
                                onsubmit="return confirm('Tem certeza que deseja deletar o usuário <?= htmlspecialchars($usuario['nome']); ?>? Esta ação é irreversível.');">
                                <input type="hidden" name="id" value="<?= $usuario['id']; ?>">
                                <button type="submit" title="Excluir" class="button button-small button-danger">
                                    <span class="icon">&#x1F5D1;</span> Deletar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
