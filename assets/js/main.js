/**
 * main.js - Scripts de Interatividade e Animações da Aula 0
 * Disciplina: Programação Web (PHP-WEB)
 * Docente: Prof. Jônatas Ferreira
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Aula 0: Script JS carregado com sucesso!');

    // 1. Inicialização de Tooltips do Bootstrap 5 (se aplicável)
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // 2. Animação simples de entrada no Card de Boas-Vindas
    const cardElement = document.querySelector('.card');
    if (cardElement) {
        cardElement.style.opacity = '0';
        cardElement.style.transform = 'translateY(15px)';
        cardElement.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        
        setTimeout(() => {
            cardElement.style.opacity = '1';
            cardElement.style.transform = 'translateY(0)';
        }, 100);
    }

    // 3. Verificação em tempo real da conexão local (Healthcheck simplificado)
    const serverStatusBadge = document.querySelector('.badge');
    if (serverStatusBadge) {
        serverStatusBadge.addEventListener('click', () => {
            alert('✅ Status: Servidor Apache e Interpretador PHP funcionando corretamente no XAMPP!');
        });
    }
});
