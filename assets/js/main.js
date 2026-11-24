/* ============================================================
   CRUD de Usuários — interatividade JavaScript
   Aula 0: confirmação antes de excluir.
   Aula 1: validação do formulário antes de enviar ao PHP.
   Aula 2: Toast de feedback após cadastrar ou excluir.
   Aula 3: senha opcional na edição (data-senha-opcional).
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {

    /* ---------- Confirmação ao excluir (Aula 0) ---------- */
    const botoesExcluir = document.querySelectorAll(".btn-excluir");

    botoesExcluir.forEach(function (botao) {
        botao.addEventListener("click", function (evento) {
            const nome = botao.dataset.nome;
            const confirmou = confirm("Deseja realmente excluir o usuário " + nome + "?");

            // Se cancelou, impede que o link siga adiante
            if (!confirmou) {
                evento.preventDefault();
            }
        });
    });

    /* ---------- Toast de feedback (Aula 2) ---------- */
    // O PHP só escreve o #toast-feedback quando existe uma mensagem flash na sessão.
    // (Como a mensagem é descartada ao ser lida, o F5 não repete o aviso.)
    const toastEl = document.getElementById("toast-feedback");

    if (toastEl) {
        new bootstrap.Toast(toastEl, { delay: 4000 }).show();
    }

    /* ---------- Validação do formulário (Aula 1) ---------- */
    const formulario = document.getElementById("form-usuario");

    if (formulario) {
        formulario.addEventListener("submit", function (evento) {
            const nome = document.getElementById("nome").value.trim();
            const email = document.getElementById("email").value.trim();
            const nascimento = document.getElementById("data_nascimento").value;
            const senha = document.getElementById("senha").value;

            // Na edição (Aula 3), a senha em branco significa "manter a senha atual"
            const senhaOpcional = formulario.dataset.senhaOpcional === "true";

            // Expressão regular simples para e-mail: texto@texto.texto
            const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            let mensagem = "";
            let campoComErro = null;

            if (nome.length < 3) {
                mensagem = "Informe um nome com pelo menos 3 caracteres.";
                campoComErro = "nome";
            } else if (!regexEmail.test(email)) {
                mensagem = "Informe um e-mail válido.";
                campoComErro = "email";
            } else if (nascimento === "" || new Date(nascimento) > new Date()) {
                mensagem = "Informe uma data de nascimento válida (não pode ser futura).";
                campoComErro = "data_nascimento";
            } else if ((senha.length > 0 || !senhaOpcional) && senha.length < 6) {
                mensagem = "A senha deve ter pelo menos 6 caracteres.";
                campoComErro = "senha";
            }

            // Se houve erro: bloqueia o envio, avisa e devolve o foco ao campo
            if (mensagem !== "") {
                evento.preventDefault();   // impede o navegador de enviar o formulário
                alert(mensagem);           // caixa de alerta (window.alert)
                document.getElementById(campoComErro).focus();
            }
        });
    }
});
