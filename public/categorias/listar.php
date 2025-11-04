<?php
/**
 * listar.php
 * Lista todas as categorias e fornece links para as ações CRUD.
 */
// Define a raiz do projeto de forma robusta.
// __DIR__ aponta para /PROJETO-PHP/public/categorias
// dirname(dirname(__DIR__)) aponta para /PROJETO-PHP (a raiz do projeto)
$projectRoot = dirname(dirname(__DIR__));

// 1. Checagem de Autenticação e Sessão (AGORA APONTANDO PARA /includes/)
require_once $projectRoot . '/includes/auth_check.php';

// // 2. Verifica se o usuário tem permissão de administrador
// if ($_SESSION['nivel_acesso'] !== 'admin') {
//     $_SESSION['msg_erro'] = "Acesso negado. Apenas administradores podem gerenciar categorias.";
//     // Redirecionamento usando caminho relativo, já que não temos a $base_url aqui.
//     header("Location: ../../index.php"); 
//     exit;
// }

// 3. Dependências (CategoriaDAO e Header)
require_once $projectRoot . '/app/dao/CategoriaDAO.php';
// Inclui header (que abre <html> e <body> e exibe mensagens de feedback)
require_once $projectRoot . '/includes/header.php'; 

$categoriaDAO = new CategoriaDAO();

// 4. Busca de Dados
$categorias = $categoriaDAO->listarTodos();

// Define o título para o header.php
$pageTitle = 'Gerenciamento de Categorias';
?>

<h1>Gerenciamento de Categorias</h1>

<!-- Botão de Cadastro -->
<a href="cadastrar.php" class="button btn-primary">Nova Categoria</a>

<?php if (empty($categorias)): ?>
    <p>Nenhuma categoria cadastrada.</p>
<?php else: ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $categoria): ?>
            <tr>
                <td><?= htmlspecialchars($categoria['id']); ?></td>
                <td><?= htmlspecialchars($categoria['nome']); ?></td>
                <td class="actions-cell">
                    <a href="editar.php?id=<?= $categoria['id']; ?>" class="button btn-warning">Editar</a>
                    
                    <form action="/ProjetoPHP/actions/categoria_deletar.php" method="POST" style="display:inline;" class="button btn-danger">
                        <input type="hidden" name="id" value="<?= $categoria['id']; ?>">
                        <button type="submit" onclick="return confirm('Tem certeza que deseja excluir a categoria <?= htmlspecialchars($categoria['nome']); ?>?');">Deletar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php 
// 5. Rodapé (fecha </body> e </html>)
require_once $projectRoot . '/includes/footer.php'; 
?>
