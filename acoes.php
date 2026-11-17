<?php
/* ============================================================
   acoes.php — processa as ações do CRUD
   Aula 1: gatilho "criar" (Create)
   Aula 2: entra o gatilho "excluir"
   Aula 3: entra o gatilho "atualizar"
   ============================================================ */

require 'config/conexao.php';
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

// A ação pode chegar por POST (formulário) ou por GET (link na URL)
$metodo = $_SERVER['REQUEST_METHOD'];
$acao   = $_POST['acao'] ?? $_GET['acao'] ?? '';

/* ---------------------------- CREATE ---------------------------- */
if ($acao === 'criar') {
    // Cadastro só é aceito por POST. Se alguém abrir pela URL (GET), devolvemos 405.
    if ($metodo !== 'POST') {
        responderErro(405, 'Método não permitido', 'O cadastro só aceita o envio do formulário (POST).');
    }
    // 1) Receber os dados (as chaves são os atributos "name" do formulário)
    $nome            = trim($_POST['nome'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $senha           = $_POST['senha'] ?? '';

    // 2) Validar NO SERVIDOR. A validação em JavaScript pode ser
    //    contornada, então o PHP nunca confia só nela.
    $dataValida = DateTime::createFromFormat('Y-m-d', $data_nascimento);
    if (
        mb_strlen($nome) < 3 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !$dataValida || $dataValida->format('Y-m-d') !== $data_nascimento ||
        $dataValida > new DateTime('today') ||
        strlen($senha) < 6
    ) {
        header('Location: usuario-criar.php?erro=campos');
        exit;
    }

    // 3) Criptografar a senha.
    //    NUNCA use md5($senha): é rápido demais, sem salt, e quebrado por
    //    Rainbow Tables. password_hash gera um salt aleatório a cada chamada,
    //    então a mesma senha produz hashes diferentes.
    //
    //    echo md5('123456');                      // sempre o mesmo valor
    //    echo password_hash('123456', PASSWORD_DEFAULT); // muda a cada execução
    //
    //    Para conferir no login (Aula 3 / Mini Loja): password_verify($digitada, $hash)
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // 4) INSERT com Prepared Statement (placeholders nomeados).
    //    Os valores NUNCA são concatenados no SQL: isso impede SQL Injection.
    try {
        $sql  = "INSERT INTO usuarios (nome, email, data_nascimento, senha)
                 VALUES (:nome, :email, :data_nascimento, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome'            => $nome,
            'email'           => $email,
            'data_nascimento' => $data_nascimento,
            'senha'           => $senhaHash,
        ]);

        // 5) Redirecionar para a lista (padrão Post/Redirect/Get:
        //    evita reenviar o formulário se o usuário atualizar a página)
        header('Location: index.php?msg=criado');
        exit;
    } catch (PDOException $e) {
        // 23000 = violação de chave única (e-mail UNIQUE já existe)
        $erro = ($e->getCode() === '23000') ? 'email' : 'banco';
        header('Location: usuario-criar.php?erro=' . $erro);
        exit;
    }
}

/* ---------------------------- DELETE ---------------------------- */
if ($acao === 'excluir') {

    // Aula 2: o id chega pela URL (GET / query string): acoes.php?acao=excluir&id=3
    // (Por simplicidade didática. Em sistemas reais, a exclusão deve usar POST.)

    // 1) Validar o id: precisa ser um número inteiro positivo.
    //    filter_var devolve false se o valor não for um inteiro válido.
    $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
    if ($id === false || $id < 1) {
        responderErro(400, 'Requisição inválida', 'O identificador do usuário é inválido.');
    }

    try {
        // 2) DELETE com Prepared Statement. O WHERE é OBRIGATÓRIO:
        //    sem ele, o comando apagaria TODOS os registros da tabela.
        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);

        // 3) rowCount() informa quantas linhas foram apagadas.
        //    Zero significa que não existia usuário com esse id => 404 Not Found.
        if ($stmt->rowCount() === 0) {
            responderErro(404, 'Usuário não encontrado', 'Nenhum usuário com o id ' . $id . '.');
        }

        // 4) Sucesso: redireciona (302) e a lista é exibida com 200 OK
        header('Location: index.php?msg=excluido');
        exit;
    } catch (PDOException $e) {
        responderErro(500, 'Erro interno', 'Não foi possível excluir o usuário.');
    }
}

/* ------------------- Ação desconhecida ------------------- */
responderErro(400, 'Requisição inválida', 'Ação desconhecida.');
