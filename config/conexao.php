<?php
/* ============================================================
   Conexão com o banco de dados usando PDO
   Aula 1: este arquivo é incluído (require) pelas demais páginas.
   ============================================================ */

// Dados de acesso (padrão do XAMPP: usuário "root" e senha vazia)
$host    = 'localhost';
$banco   = 'db_crud_usuarios';
$usuario = 'root';
$senha   = '';

try {
    // DSN = "Data Source Name": tipo do banco, servidor, nome do banco e charset
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha,
        [
            // Faz o PDO lançar exceção em caso de erro (assim o catch funciona)
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // fetch() devolve array associativo: $linha['nome']
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Usa prepared statements reais do MySQL (mais seguro contra SQL Injection)
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // Código HTTP 500 = erro interno do servidor
    http_response_code(500);
    // Em produção NUNCA mostre $e->getMessage() ao usuário; aqui é só didático
    die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
}
