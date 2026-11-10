<?php
// Mensagens de erro enviadas por acoes.php via URL (GET): usuario-criar.php?erro=email
$erros = [
    'campos' => 'Preencha todos os campos corretamente.',
    'email'  => 'Este e-mail já está cadastrado.',
    'banco'  => 'Não foi possível salvar. Tente novamente.',
];
$erro = $_GET['erro'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adicionar usuário | CRUD Programação Web</title>

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

                <h2 class="titulo-pagina fs-3 mb-1">
                    <i class="bi bi-person-plus-fill me-1"></i> Adicionar usuário
                </h2>
                <p class="text-secondary mb-4">Preencha os dados para cadastrar um novo usuário</p>

                <?php if (isset($erros[$erro])): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <?= htmlspecialchars($erros[$erro]) ?>
                    </div>
                <?php endif; ?>

                <!--
                    method="POST": os dados vão no CORPO (body) da requisição, não na URL.
                    action: arquivo PHP que vai receber e processar os dados.
                    novalidate: desliga a validação nativa para usarmos a nossa em JavaScript.

                    ATENÇÃO: o PHP lê os campos pelo atributo NAME (não pelo id):
                    <input name="email">  ->  $_POST['email']
                -->
                <form id="form-usuario" action="acoes.php" method="POST" novalidate>

                    <!-- Campo oculto: diz ao acoes.php qual ação executar -->
                    <input type="hidden" name="acao" value="criar">

                    <div class="mb-3">
                        <label for="nome" class="form-label fw-semibold">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome"
                               maxlength="100" placeholder="Ex.: Maria da Silva">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email"
                               maxlength="100" placeholder="Ex.: maria@email.com">
                    </div>

                    <div class="mb-3">
                        <label for="data_nascimento" class="form-label fw-semibold">Data de nascimento</label>
                        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
                    </div>

                    <div class="mb-4">
                        <label for="senha" class="form-label fw-semibold">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha"
                               placeholder="Mínimo de 6 caracteres">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Cadastrar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
