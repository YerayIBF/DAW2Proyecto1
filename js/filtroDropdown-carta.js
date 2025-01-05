document.querySelectorAll('.filtro-titulo').forEach(title => {
    title.addEventListener('click', () => {
        title.classList.toggle('collapsed');
        const content = title.nextElementSibling;
        content.style.display = content.style.display === 'none' ? 'block' : 'none';
    });
});