<?php
/* ============================================================
   acoes.php — processa as ações do CRUD
   Aula 1: gatilho "criar"     (Create) — via POST
   Aula 2: gatilho "excluir"   (Delete) — via GET
   Aula 3: gatilho "atualizar" (Update) — via POST
           + mensagens de feedback guardadas na sessão ($_SESSION)
   ============================================================ */

// A sessão precisa ser iniciada ANTES de qualquer saída (echo, HTML...)
session_start();

require 'config/conexao.php';

/**
 * Responde com um código de status HTTP e uma página de erro simples.
 * 400 = requisição inválida | 404 = não encontrado | 405 = método não permitido
 */
function responderErro(int $codigo, string $titulo, string $mensagem): void
{
    http_response_code($codigo);
    echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8">'
       . '<meta name="viewport" content="width=device-width, initial-scale=1">'
       . '<title>Erro ' . $codigo . '</title>'
       . '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>'
       . '<body class="bg-light d-flex align-items-center justify-content-center vh-100">'
       . '<div class="text-center"><h1 class="display-1 fw-bold text-primary">' . $codigo . '</h1>'
       . '<p class="fs-4 mb-1">' . htmlspecialchars($titulo) . '</p>'
       . '<p class="text-secondary">' . htmlspecialchars($mensagem) . '</p>'
       . '<a href="index.php" class="btn btn-primary">Voltar para a lista</a></div></body></html>';
    exit;
}

/**
 * Guarda uma mensagem na SESSÃO para ser exibida na PRÓXIMA página
 * (mensagem "flash": é lida uma única vez e depois descartada).
 * $tipo: success | danger | info | warning (cores do Bootstrap)
 */
function avisar(string $tipo, string $texto): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'texto' => $texto];
}

/**
 * Validação no SERVIDOR, usada pelo cadastro e pela edição.
 * Na edição a senha é opcional: em branco = manter a senha atual.
 */
function dadosValidos(string $nome, string $email, string $data, string $senha, bool $senhaObrigatoria): bool
{
    $dt = DateTime::createFromFormat('Y-m-d', $data);

    if (mb_strlen($nome) < 3 || mb_strlen($nome) > 100)        return false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) return false;
    if (!$dt || $dt->format('Y-m-d') !== $data)                return false;   // data inexistente
    if ($dt > new DateTime('today'))                           return false;   // data futura
    if (($senhaObrigatoria || $senha !== '') && strlen($senha) < 6) return false;

    return true;
}

// A ação pode chegar por POST (formulário) ou por GET (link na URL)
$metodo = $_SERVER['REQUEST_METHOD'];
$acao   = $_POST['acao'] ?? $_GET['acao'] ?? '';

/* ---------------------------- CREATE ---------------------------- */
if ($acao === 'criar') {

    if ($metodo !== 'POST') {
        responderErro(405, 'Método não permitido', 'O cadastro só aceita o envio do formulário (POST).');
    }

    $nome            = trim($_POST['nome'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $senha           = $_POST['senha'] ?? '';

    if (!dadosValidos($nome, $email, $data_nascimento, $senha, true)) {
        avisar('danger', 'Preencha todos os campos corretamente.');
        header('Location: usuario-criar.php');
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, data_nascimento, senha)
                               VALUES (:nome, :email, :data_nascimento, :senha)");
        $stmt->execute([
            'nome'            => $nome,
            'email'           => $email,
            'data_nascimento' => $data_nascimento,
            'senha'           => $senhaHash,
        ]);

        avisar('success', 'Usuário cadastrado com sucesso!');
        header('Location: index.php');   // Post/Redirect/Get
        exit;

    } catch (PDOException $e) {
        // 23000 = violação de chave única (e-mail UNIQUE já existe)
        avisar('danger', $e->getCode() === '23000'
            ? 'Este e-mail já está cadastrado.'
            : 'Não foi possível salvar. Tente novamente.');
        header('Location: usuario-criar.php');
        exit;
    }
}

/* ---------------------------- UPDATE ---------------------------- */
if ($acao === 'atualizar') {

    // Edição só é aceita por POST (altera dados, então nunca por GET)
    if ($metodo !== 'POST') {
        responderErro(405, 'Método não permitido', 'A edição só aceita o envio do formulário (POST).');
    }

    // 1) O id vem num campo oculto do formulário: <input type="hidden" name="id">
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
    if ($id === false || $id < 1) {
        responderErro(400, 'Requisição inválida', 'O identificador do usuário é inválido.');
    }

    $nome            = trim($_POST['nome'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $senha           = $_POST['senha'] ?? '';   // opcional na edição

    try {
        // 2) O usuário existe? Não dá para descobrir pelo rowCount() do UPDATE:
        //    no MySQL ele conta só as linhas que MUDARAM de fato. Se você salvar
        //    sem alterar nada, devolve 0 mesmo com o usuário existindo.
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        if (!$stmt->fetch()) {
            responderErro(404, 'Usuário não encontrado', 'Nenhum usuário com o id ' . $id . '.');
        }

        // 3) Validar no servidor
        if (!dadosValidos($nome, $email, $data_nascimento, $senha, false)) {
            avisar('danger', 'Preencha todos os campos corretamente.');
            header('Location: usuario-editar.php?id=' . $id);
            exit;
        }

        // 4) UPDATE com Prepared Statement. O WHERE é OBRIGATÓRIO (senão altera todos!).
        //    Sintaxe do UPDATE: UPDATE tabela SET coluna = valor, ... WHERE condição
        $params = [
            'nome'            => $nome,
            'email'           => $email,
            'data_nascimento' => $data_nascimento,
            'id'              => $id,
        ];

        if ($senha !== '') {
            // Digitou uma senha nova: grava o novo hash
            $sql = 'UPDATE usuarios
                       SET nome = :nome, email = :email, data_nascimento = :data_nascimento, senha = :senha
                     WHERE id = :id';
            $params['senha'] = password_hash($senha, PASSWORD_DEFAULT);
        } else {
            // Em branco: a coluna senha nem entra no UPDATE (mantém a atual)
            $sql = 'UPDATE usuarios
                       SET nome = :nome, email = :email, data_nascimento = :data_nascimento
                     WHERE id = :id';
        }

        $pdo->prepare($sql)->execute($params);

        avisar('success', 'Usuário atualizado com sucesso!');
        header('Location: index.php');
        exit;

    } catch (PDOException $e) {
        // 23000 aqui = tentou usar o e-mail de OUTRO usuário
        avisar('danger', $e->getCode() === '23000'
            ? 'Este e-mail já pertence a outro usuário.'
            : 'Não foi possível salvar. Tente novamente.');
        header('Location: usuario-editar.php?id=' . $id);
        exit;
    }
}

/* ---------------------------- DELETE ---------------------------- */
if ($acao === 'excluir') {

    // O id chega pela URL (GET / query string): acoes.php?acao=excluir&id=3
    // (Por simplicidade didática. Em sistemas reais, a exclusão deve usar POST.)
    $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
    if ($id === false || $id < 1) {
        responderErro(400, 'Requisição inválida', 'O identificador do usuário é inválido.');
    }

    try {
        // O WHERE é OBRIGATÓRIO: sem ele, o comando apagaria TODOS os registros.
        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);

        // Para DELETE, rowCount() = linhas apagadas. Zero => não existia => 404.
        if ($stmt->rowCount() === 0) {
            responderErro(404, 'Usuário não encontrado', 'Nenhum usuário com o id ' . $id . '.');
        }

        avisar('success', 'Usuário excluído com sucesso!');
        header('Location: index.php');
        exit;

    } catch (PDOException $e) {
        responderErro(500, 'Erro interno', 'Não foi possível excluir o usuário.');
    }
}

/* ------------------- Ação desconhecida ------------------- */
responderErro(400, 'Requisição inválida', 'Ação desconhecida.');
