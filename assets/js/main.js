/* ============================================================
   CRUD de Usuários — interatividade JavaScript
   Aula 0: apenas a confirmação antes de excluir.
   Nas próximas aulas entram validação de formulário e Toast.
   ============================================================ */

document.addEventListener("DOMContentLoaded", function () {
    // Pega todos os botões "Excluir" da tabela
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
});
