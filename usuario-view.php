<?php
/* ============================================================
   usuario-view.php — visualização detalhada de um usuário (Read de UM registro)
   Aula 3: mesmo esquema do editar: valida o id, SELECT ... WHERE id, fetch().
   ============================================================ */

require 'config/conexao.php';

$id      = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
$usuario = null;
$status  = 200;

if ($id === false || $id < 1) {
    $status = 400;
} else {
    // Não buscamos a coluna "senha": nem o hash deve sair do banco sem necessidade
    $stmt = $pdo->prepare('SELECT id, nome, email, data_nascimento, created_at FROM usuarios WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        $status = 404;
    }
}
http_response_code($status);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar usuário | CRUD Programação Web</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand navbar-dark navbar-crud py-3">
        <div class="container-fluid px-4">
            <a class="navbar-brand fs-4 d-flex align-items-center" href="index.php">
                <i class="bi bi-people-fill me-2"></i>
                CRUD
                <span class="brand-divisor"></span>
                <span class="brand-professor">Prof. Jonatas Ferreira</span>
            </a>
            <span class="navbar-text navbar-disciplina">
                <i class="bi bi-code-slash me-1"></i> Programação Web
            </span>
        </div>
    </nav>

    <main class="container-fluid px-4 py-5">
        <div class="card card-crud mx-auto" style="max-width: 720px;">
            <div class="card-body p-4">

                <?php if (!$usuario): ?>
                    <div class="text-center py-4">
                        <h1 class="display-4 fw-bold text-primary"><?= $status ?></h1>
                        <p class="fs-5 mb-4">
                            <?= $status === 400 ? 'Identificador de usuário inválido.' : 'Usuário não encontrado.' ?>
                        </p>
                        <a href="index.php" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-1"></i> Voltar para a lista
                        </a>
                    </div>

                <?php else: ?>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="avatar-inicial" style="width: 64px; height: 64px; font-size: 1.6rem;">
                            <?= htmlspecialchars(mb_strtoupper(mb_substr($usuario['nome'], 0, 1))) ?>
                        </span>
                        <div>
                            <h2 class="titulo-pagina fs-3 mb-0"><?= htmlspecialchars($usuario['nome']) ?></h2>
                            <span class="text-secondary">Usuário #<?= (int) $usuario['id'] ?></span>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-secondary"><i class="bi bi-envelope me-2"></i>E-mail</span>
                            <span class="fw-semibold"><?= htmlspecialchars($usuario['email']) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-secondary"><i class="bi bi-calendar3 me-2"></i>Data de nascimento</span>
                            <span class="fw-semibold"><?= (new DateTime($usuario['data_nascimento']))->format('d/m/Y') ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-secondary"><i class="bi bi-clock-history me-2"></i>Cadastrado em</span>
                            <span class="fw-semibold"><?= (new DateTime($usuario['created_at']))->format('d/m/Y \à\s H:i') ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-secondary"><i class="bi bi-shield-lock me-2"></i>Senha</span>
                            <span class="fw-semibold">•••••••• <small class="text-secondary fw-normal">(guardada como hash)</small></span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Voltar
                        </a>
                        <a href="usuario-editar.php?id=<?= (int) $usuario['id'] ?>" class="btn btn-primary">
                            <i class="bi bi-pencil-fill me-1"></i> Editar
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
