document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('errorMessage');

    // Limpar qualquer mensagem de erro anterior
    errorMessage.textContent = '';

    if (username === 'admin123' && password === 'admin123') {
        alert('Login bem-sucedido!');
        // Redirecionar para a página matricula.html
        window.location.href = "matricula.html";
    } else {
        // Mostrar a mensagem de erro
        errorMessage.textContent = 'Usuário ou senha incorretos. Tente novamente.';
        errorMessage.style.color = 'red'; // Exemplo de estilização para destacar o erro
    }
});
