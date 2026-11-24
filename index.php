<?php
/* ============================================================
   index.php — Lista de Usuários (Read)
   Aula 2: os dados agora vêm do banco (SELECT + foreach).
   Aula 3: links Visualizar/Editar e mensagens flash via $_SESSION.
   ============================================================ */

session_start();   // sempre ANTES de qualquer saída
require 'config/conexao.php';

// 1) Consulta. Não há valor vindo do usuário, então query() é suficiente.
//    Não buscamos a coluna "senha": só pegamos o que a tela precisa.
$stmt     = $pdo->query('SELECT id, nome, email, data_nascimento FROM usuarios ORDER BY id');
$usuarios = $stmt->fetchAll();   // array de arrays: uma posição por linha da tabela

// 2) Mensagem flash: guardada na sessão pelo acoes.php, lida UMA vez e descartada.
//    Diferente do ?msg=... da Aula 2, ela não aparece na URL e não pode ser forjada.
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD de Usuários | Programação Web</title>

    <!-- Bootstrap 5 + Bootstrap Icons + fonte Inter (via CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Estilos da aplicação -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- ===== Barra de navegação ===== -->
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

    <!-- ===== Conteúdo principal ===== -->
    <main class="container-fluid px-4 py-5">
        <div class="card card-crud">
            <div class="card-body p-4">

                <!-- Título + botão Adicionar -->
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h2 class="titulo-pagina fs-3 mb-1">
                            <i class="bi bi-person-lines-fill me-1"></i> Lista de Usuários
                        </h2>
                        <p class="text-secondary mb-0">Gerencie os registros cadastrados na base de dados</p>
                    </div>

                    <a href="usuario-criar.php" class="btn btn-primary btn-adicionar">
                        <i class="bi bi-person-plus-fill me-2"></i> Adicionar usuário
                    </a>
                </div>

                <!-- Tabela de usuários -->
                <div class="table-responsive">
                    <table class="table align-middle tabela-usuarios mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 90px;">ID</th>
                                <th>Usuário</th>
                                <th>E-mail</th>
                                <th class="text-center">Data nasc.</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (count($usuarios) === 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-5">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        Nenhum usuário cadastrado.
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <!-- Uma <tr> para cada linha devolvida pelo SELECT -->
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td class="text-center col-id">#<?= (int) $u['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="avatar-inicial"><?= htmlspecialchars(mb_strtoupper(mb_substr($u['nome'], 0, 1))) ?></span>
                                            <span class="nome-usuario"><?= htmlspecialchars($u['nome']) ?></span>
                                        </div>
                                    </td>
                                    <td class="email-usuario text-secondary">
                                        <i class="bi bi-envelope"></i><?= htmlspecialchars($u['email']) ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-data">
                                            <i class="bi bi-calendar3"></i><?= (new DateTime($u['data_nascimento']))->format('d/m/Y') ?>
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="usuario-view.php?id=<?= (int) $u['id'] ?>" class="btn btn-outline-secondary btn-acao" title="Visualizar">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="usuario-editar.php?id=<?= (int) $u['id'] ?>" class="btn btn-outline-primary btn-acao" title="Editar">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <!-- Excluir: o id viaja pela URL (GET / query string) -->
                                        <a href="acoes.php?acao=excluir&amp;id=<?= (int) $u['id'] ?>"
                                           class="btn btn-outline-danger btn-acao btn-excluir"
                                           data-nome="<?= htmlspecialchars($u['nome']) ?>" title="Excluir">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>

    <!-- ===== Toast de feedback (aparece só quando há uma mensagem flash na sessão) ===== -->
    <?php if ($flash): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="toast-feedback" class="toast align-items-center text-bg-<?= htmlspecialchars($flash['tipo']) ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi <?= $flash['tipo'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i><?= htmlspecialchars($flash['texto']) ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS + JS da aplicação -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
