<?php 
// Define a raiz do projeto de forma robusta.
// __DIR__ aponta para /PROJETO-PHP/public/produtos
// dirname(dirname(__DIR__)) aponta para /PROJETO-PHP (a raiz do projeto)
$projectRoot = dirname(dirname(__DIR__));

// 1. Checagem de Autenticação e Sessão
// O caminho agora é: {raiz do projeto}/includes/auth_check.php
require_once $projectRoot . '/includes/auth_check.php';

// 2. Dependências (ProdutoDAO e includes)
require_once $projectRoot . '/app/dao/ProdutoDAO.php';
require_once $projectRoot . '/includes/header.php'; // Inclui header e mensagem de feedback

$produtoDAO = new ProdutoDAO();

// 3. Processamento dos Filtros
$nome_filtro = filter_input(INPUT_GET, 'nome_filtro', FILTER_SANITIZE_STRING);
$categoria_id_filtro = filter_input(INPUT_GET, 'categoria_id_filtro', FILTER_VALIDATE_INT) ?? 'todos';

// 4. Busca de Dados
$produtos = $produtoDAO->lerTodosComFiltros($nome_filtro, $categoria_id_filtro);
$categorias = $produtoDAO->listarCategorias(); // Para popular o filtro de categoria
?>

<h1>Gerenciamento de Produtos</h1>

<a href="cadastrar.php" class="button">Novo Produto</a>

<form method="GET" action="listar.php" class="filter-form">
    <h3>Buscar/Filtrar</h3>
    <div>
        <label for="nome_filtro">Nome do Produto:</label>
        <input type="text" id="nome_filtro" name="nome_filtro" value="<?= htmlspecialchars($nome_filtro ?? ''); ?>">
    </div>
    <div>
        <label for="categoria_id_filtro">Categoria:</label>
        <select id="categoria_id_filtro" name="categoria_id_filtro">
            <option value="todos">Todas as Categorias</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id']; ?>" 
                    <?= ($categoria_id_filtro == $categoria['id']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($categoria['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit">Aplicar Filtros</button>
    <a href="listar.php">Limpar Filtros</a>
</form>

<?php if (empty($produtos)): ?>
    <p>Nenhum produto encontrado com os filtros aplicados.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Data Cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['id']); ?></td>
                <td><?= htmlspecialchars($produto['nome']); ?></td>
                <td>R$ <?= htmlspecialchars(number_format($produto['preco'], 2, ',', '.')); ?></td>
                <td><?= htmlspecialchars($produto['categoria_nome'] ?? 'Sem Categoria'); ?></td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($produto['data_cadastro']))); ?></td>
                <td>
                    <a href="editar.php?id=<?= $produto['id']; ?>">Editar</a> |
                    <!-- CORRIGIDO: Usando caminho absoluto para o action -->
                    <form method="POST" action="/PROJETOPHP/actions/produto_deletar.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $produto['id']; ?>">
                        <!-- Lembre-se: O ideal é usar uma modal customizada ao invés de alert/confirm -->
                        <button type="submit" onclick="return confirm('Tem certeza que deseja deletar o produto?')" style="background: none; border: none; color: blue; cursor: pointer; padding: 0;">Deletar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php 
// 4. Rodapé
require_once $projectRoot . '/includes/footer.php'; 
?>