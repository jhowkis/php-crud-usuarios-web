<?php
// Caso a conexão com o banco ainda não exista, usa dados fictícios para exibição
if (file_exists(__DIR__ . '/config/conexao.php')) {
    require_once __DIR__ . '/config/conexao.php';
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, data_nascimento FROM usuarios ORDER BY id DESC");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $erro = "Erro ao carregar usuários: " . $e->getMessage();
    }
} else {
    // Dados temporários para demonstração visual
    $usuarios = [
        ['id' => 1, 'nome' => 'Pedro', 'email' => 'contato@canalti.com.br', 'data_nascimento' => '2000-05-01'],
        ['id' => 2, 'nome' => 'João', 'email' => 'contato2@canalti.com.br', 'data_nascimento' => '2001-05-01']
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuários - Painel</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS Personalizado Externo -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar Superior -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-5">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="index.php">
                <i class="bi bi-people-fill fs-4"></i>
                <span>CRUD <span class="fw-light opacity-75">| PROF. JONATAS FERREIRA</span></span>
            </a>
            <span class="navbar-text text-white-50 small">
                <i class="bi bi-code-slash me-1"></i> Programação Web
            </span>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container mb-5">
        
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($erro) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card card-custom p-4">
            
            <!-- Cabeçalho do Card -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1 text-primary">
                        <i class="bi bi-person-lines-fill me-2"></i>Lista de Usuários
                    </h4>
                    <p class="text-muted small mb-0">Gerencie os registros cadastrados na base de dados</p>
                </div>
                <div>
                    <a href="usuario-criar.php" class="btn btn-primary btn-primary-custom d-inline-flex align-items-center gap-2">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Adicionar usuário</span>
                    </a>
                </div>
            </div>

            <!-- Tabela de Usuários -->
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" style="width: 80px;">ID</th>
                            <th scope="col">Usuário</th>
                            <th scope="col">E-mail</th>
                            <th scope="col" class="text-center">Data Nasc.</th>
                            <th scope="col" class="text-end" style="width: 160px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td class="text-center fw-semibold text-secondary">
                                        #<?= htmlspecialchars($usuario['id']) ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-circle">
                                                <?= strtoupper(substr($usuario['nome'], 0, 1)) ?>
                                            </div>
                                            <span class="fw-semibold text-dark">
                                                <?= htmlspecialchars($usuario['nome']) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-secondary">
                                        <i class="bi bi-envelope me-1 text-primary opacity-50"></i>
                                        <?= htmlspecialchars($usuario['email']) ?>
                                    </td>
                                    <td class="text-center text-secondary">
                                        <span class="badge bg-light text-dark border px-2 py-1 fw-normal">
                                            <i class="bi bi-calendar3 me-1 text-primary opacity-75"></i>
                                            <?= date('d/m/Y', strtotime($usuario['data_nascimento'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="usuario-view.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline-secondary action-btn" title="Visualizar" data-bs-toggle="tooltip">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="usuario-editar.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline-primary action-btn" title="Editar" data-bs-toggle="tooltip">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <a href="acoes.php?acao=excluir&id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline-danger action-btn" title="Excluir" data-bs-toggle="tooltip" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    Nenhum usuário encontrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS Personalizado Externo -->
    <script src="assets/js/main.js"></script>
</body>
</html>