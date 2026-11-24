<?php
/* ============================================================
   usuario-editar.php — formulário de edição (Update)
   Aula 3: busca UM usuário pelo id (GET) e preenche o formulário.
   ============================================================ */

session_start();
require 'config/conexao.php';

// 1) Validar o id que veio na URL: usuario-editar.php?id=3
$id      = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
$usuario = null;
$status  = 200;

if ($id === false || $id < 1) {
    $status = 400;   // id inválido
} else {
    // 2) SELECT de UM registro: prepare + execute + fetch()
    $stmt = $pdo->prepare('SELECT id, nome, email, data_nascimento FROM usuarios WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $usuario = $stmt->fetch();   // array com a linha, ou false se não existir

    if (!$usuario) {
        $status = 404;
    }
}
http_response_code($status);

// 3) Mensagem flash (lida uma vez e descartada)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar usuário | CRUD Programação Web</title>

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
                    <!-- id inválido (400) ou usuário inexistente (404) -->
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
                    <h2 class="titulo-pagina fs-3 mb-1">
                        <i class="bi bi-pencil-fill me-1"></i> Editar usuário
                    </h2>
                    <p class="text-secondary mb-4">Altere os dados de <strong><?= htmlspecialchars($usuario['nome']) ?></strong></p>

                    <?php if ($flash): ?>
                        <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <?= htmlspecialchars($flash['texto']) ?>
                        </div>
                    <?php endif; ?>

                    <!--
                        data-senha-opcional="true": avisa o main.js que, na edição,
                        a senha em branco significa "manter a senha atual".
                    -->
                    <form id="form-usuario" action="acoes.php" method="POST" novalidate data-senha-opcional="true">

                        <!-- Campos ocultos: qual ação executar e QUAL usuário alterar -->
                        <input type="hidden" name="acao" value="atualizar">
                        <input type="hidden" name="id" value="<?= (int) $usuario['id'] ?>">

                        <!-- value="..." preenche o campo com o dado atual.
                             htmlspecialchars() também é obrigatório dentro de atributos! -->
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-semibold">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                   maxlength="100" value="<?= htmlspecialchars($usuario['nome']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   maxlength="100" value="<?= htmlspecialchars($usuario['email']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label fw-semibold">Data de nascimento</label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
                                   value="<?= htmlspecialchars($usuario['data_nascimento']) ?>">
                        </div>

                        <!-- A senha NUNCA é preenchida: o banco guarda só o hash -->
                        <div class="mb-4">
                            <label for="senha" class="form-label fw-semibold">Nova senha <span class="text-secondary fw-normal">(opcional)</span></label>
                            <input type="password" class="form-control" id="senha" name="senha"
                                   placeholder="Deixe em branco para manter a senha atual">
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Salvar alterações
                            </button>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
