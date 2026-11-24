# 🚀 CRUD de Usuários em PHP, MySQL, Bootstrap & JavaScript

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)

Este repositório contém a aplicação prática de um **CRUD (Create, Read, Update, Delete)** para gerenciamento de usuários. O projeto serve como base didática para as aulas da disciplina de **Programação Web**, ministrada pelo **Prof. Jonatas Ferreira**.

> 📍 **Você está na Aula 0 (Pré-aula):** o projeto contém apenas o front-end estático (`index.html`) e o script do banco. O PHP entra a partir da Aula 1.

---

## 📸 Interface da Aplicação

![Lista de Usuários](assets/img/preview.png)

A interface é construída com **Bootstrap 5**, oferecendo uma tabela responsiva com botões de ação rápidos para **Visualizar**, **Editar** e **Excluir** registros, além da opção de **Adicionar usuário**.

---

## 🛠️ Tecnologias Utilizadas

- **Front-end:** HTML5, CSS3, Bootstrap 5 e JavaScript (ES6+).
- **Back-end:** PHP 8.x (com PDO para conexão e manipulação segura via Prepared Statements).
- **Banco de Dados:** MySQL / MariaDB.
- **Servidor Web:** Apache (XAMPP / WAMP).

---

## 📂 Estrutura de Pastas

Estrutura **atual (Aula 0)**:

```text
crud-usuarios/
├── assets/
│   ├── css/
│   │   └── style.css          # Estilos personalizados da aplicação
│   ├── js/
│   │   └── main.js            # Interatividade JS (confirmação ao excluir)
│   └── img/
│       └── preview.png        # Imagem de demonstração da interface
├── db/
│   └── schema.sql             # Script SQL de criação do banco e tabela
├── index.html                 # Tela principal estática (vira index.php na Aula 1)
└── README.md                  # Documentação do projeto
```

Estrutura **final do projeto** (ao fim da Aula 3):

```text
crud-usuarios/
├── assets/ ...
├── config/
│   └── conexao.php            # Conexão PDO com o banco de dados
├── db/
│   └── schema.sql
├── index.php                  # Lista de Usuários (Read)
├── usuario-criar.php          # Formulário de Cadastro (Create)
├── usuario-editar.php         # Formulário de Edição (Update)
├── usuario-view.php           # Visualização detalhada do usuário
├── acoes.php                  # Processamento das ações (POST/GET)
└── README.md
```

---

## 🗄️ Modelo de Banco de Dados (`db/schema.sql`)

Crie a base de dados no **phpMyAdmin** (`http://localhost/phpmyadmin`) executando o script `db/schema.sql`:

```sql
CREATE DATABASE IF NOT EXISTS db_crud_usuarios
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE db_crud_usuarios;

CREATE TABLE IF NOT EXISTS usuarios (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(100) NOT NULL,
    email           VARCHAR(100) NOT NULL UNIQUE,
    data_nascimento DATE         NOT NULL,
    senha           VARCHAR(255) NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
```

O arquivo também insere dois usuários de exemplo (Pedro e João), ambos com a senha `123456` já armazenada como **hash bcrypt**.

> 💡 A coluna `senha` é `VARCHAR(255)` porque o `password_hash()` pode gerar hashes maiores no futuro (hoje o bcrypt gera 60 caracteres).

---

## ⚙️ Como Executar o Projeto Localmente

### 1. Pré-requisitos

- **XAMPP** (ou ambiente similar com Apache, PHP 8+ e MySQL).
- Editor de código (ex: **VS Code**).

### 2. Passo a Passo (Aula 0)

1. **Copiar o Projeto:**
   Coloque a pasta `crud-usuarios` no diretório do servidor web (ex: `C:\xampp\htdocs\crud-usuarios`).

2. **Iniciar os Serviços:**
   Abra o **XAMPP Control Panel** e inicie os módulos **Apache** e **MySQL**.

3. **Configurar o Banco de Dados:**
   Acesse `http://localhost/phpmyadmin`, abra a aba **SQL** e execute o conteúdo de `db/schema.sql`. Confirme que o banco `db_crud_usuarios` apareceu com a tabela `usuarios` e 2 registros.

4. **Acessar no Navegador:**
   Abra `http://localhost/crud-usuarios/index.html`
   _(ou com a porta do seu ambiente, ex: `http://localhost:8081/crud-usuarios/index.html`)_.

---

## 🗓️ Evolução por Aula

| Aula  | Foco                    | O que muda no projeto                                                                       |
| ----- | ----------------------- | ------------------------------------------------------------------------------------------- |
| **0** | Setup e briefing        | `index.html` estático, `assets/`, `db/schema.sql`                                           |
| **1** | HTTP + Conexão + Create | `index.html` → `index.php`, `config/conexao.php`, `usuario-criar.php`, `acoes.php` (INSERT) |
| **2** | Read + Delete           | `index.php` com `SELECT` + `foreach`; exclusão por GET em `acoes.php`                       |
| **3** | Update + Sessões        | `usuario-view.php`, `usuario-editar.php`, `acoes.php` (UPDATE), mensagens com `$_SESSION`   |

---

## 📊 Mapa de Evolução do Projeto

O percentual indica **quanto do arquivo final** já está pronto ao término de cada aula.
🟢 concluído · 🟡 em andamento · 🔴 ainda não iniciado

| Arquivo / Pasta          |     Aula 0 (Setup & Briefing)     |       Aula 1 (HTTP, PDO & Create)        |     Aula 2 (Read & Delete)      |   Aula 3 (Update & Sessões)   |
| ------------------------ | :-------------------------------: | :--------------------------------------: | :-----------------------------: | :---------------------------: |
| `assets/css/style.css`   |              🟢 100%              |                 🟢 100%                  |             🟢 100%             |            🟢 100%            |
| `assets/js/main.js`      | 🟡 33% _(confirmação ao excluir)_ | 🟡 67% _(+ validação, `preventDefault`)_ |       🟢 100% _(+ Toast)_       |            🟢 100%            |
| `assets/img/preview.png` |              🟢 100%              |                 🟢 100%                  |             🟢 100%             |            🟢 100%            |
| `config/conexao.php`     |               🔴 0%               |                 🟢 100%                  |             🟢 100%             |            🟢 100%            |
| `db/schema.sql`          |              🟢 100%              |                 🟢 100%                  |             🟢 100%             |            🟢 100%            |
| `index.php`              | 🟡 30% _(`index.html` estático)_  |  🟡 40% _(renomeado, botão Adicionar)_   | 🟡 90% _(`SELECT` + `foreach`)_ | 🟢 100% _(Visualizar/Editar)_ |
| `usuario-criar.php`      |               🔴 0%               |                 🟢 100%                  |             🟢 100%             |            🟢 100%            |
| `usuario-view.php`       |               🔴 0%               |                  🔴 0%                   |              🔴 0%              |            🟢 100%            |
| `usuario-editar.php`     |               🔴 0%               |                  🔴 0%                   |              🔴 0%              |            🟢 100%            |
| `acoes.php`              |               🔴 0%               |        🟡 40% _(gatilho Create)_         |    🟡 70% _(gatilho Delete)_    |  🟢 100% _(gatilho Update)_   |
| `README.md`              |              🟡 60%               |                  🟡 80%                  |             🟡 90%              |            🟢 100%            |
| **Progresso geral**      |             **≈ 38%**             |                **≈ 66%**                 |            **≈ 77%**            |           **100%**            |

> A Aula 4 é dedicada às apresentações dos grupos e não altera o projeto.
> O `$_SESSION` aparece na Aula 3 para as mensagens de feedback (sucesso/erro). Login e carrinho ficam para o projeto final (Mini Loja).

---

## 📌 Funcionalidades do CRUD (objetivo final)

- [ ] **Create (`C`):** Formulário de cadastro com validação via JavaScript e criptografia de senha (`password_hash`).
- [ ] **Read (`R`):** Exibição dinâmica dos dados em tabela estilizada, com data no padrão `dd/mm/aaaa`.
- [ ] **Update (`U`):** Busca do registro via `GET`, preenchimento do formulário e atualização via `POST`.
- [ ] **Delete (`D`):** Remoção via PDO com confirmação prévia em JavaScript.

---

👨‍🏫 **Docente:** Prof. Jonatas Ferreira
📚 **Disciplina:** Programação Web
