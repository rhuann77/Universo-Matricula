<?php
// Detalhes da conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "universo";  // Nome do banco de dados

// Criar a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o método de envio é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recebe e sanitiza os dados do formulário
    $nome = $_POST['fullname'] ?? null;
    $data_nascimento = $_POST['birth'] ?? null;
    $genero = $_POST['gender'] ?? null;
    $info_medica = $_POST['medical-info'] ?? null;

    // Processamento do arquivo da certidão
    $arquivo_certidao = null;
    if (isset($_FILES['birth-file']) && $_FILES['birth-file']['error'] == 0) {
        $arquivo_certidao = file_get_contents($_FILES['birth-file']['tmp_name']);
    }

    // Endereço
    $cep = $_POST['cep'] ?? null;
    $rua = !empty($_POST['street']) ? $_POST['street'] : null;
    $numero = $_POST['number'] ?? null;
    $cidade = !empty($_POST['city']) ? $_POST['city'] : null;
    $estado = !empty($_POST['state']) ? $_POST['state'] : null;

    // Dados do responsável e contato
    $responsavel = $_POST['guardian'] ?? null;
    $telefone = $_POST['phone'] ?? null;
    $email = $_POST['mail'] ?? null;
    $turno = $_POST['study-shift'] ?? null;

    // Preparar a consulta SQL para inserção
    $stmt = $conn->prepare("
        INSERT INTO matriculas (
            nome, data_nascimento, genero, info_medica, 
            certidao_nascimento, cep, rua, numero, cidade, 
            estado, responsavel, telefone, email, turno
        ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Verificar se a consulta foi preparada corretamente
    if (!$stmt) {
        echo "<div style='
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        '>
            Erro ao preparar a consulta: " . htmlspecialchars($conn->error) . "
        </div>";
    } else {
        // Associar os parâmetros para a consulta SQL
        $stmt->bind_param(
            'ssssbssissssss', 
            $nome, 
            $data_nascimento, 
            $genero, 
            $info_medica, 
            $arquivo_certidao,  // Arquivo de certidão (blob)
            $cep, 
            $rua, 
            $numero, 
            $cidade, 
            $estado, 
            $responsavel, 
            $telefone, 
            $email, 
            $turno
        );

        // Executar a consulta
        if ($stmt->execute()) {
            echo "<div style='
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
                border-radius: 5px;
                font-family: Arial, sans-serif;
                font-size: 16px;
                text-align: center;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            '>
                <h2>Matrícula realizada com sucesso!</h2>
                <p>Obrigado por se registrar.</p>
                <a href='matricula.html' style='
                    display: inline-block;
                    margin-top: 10px;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    font-weight: bold;
                    transition: background-color 0.3s ease;
                ' onmouseover=\"this.style.backgroundColor='#0056b3';\" onmouseout=\"this.style.backgroundColor='#007bff';\">Voltar para tela de matrícula</a>
            </div>";
        } else {
            echo "<div style='
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                border-radius: 5px;
                font-family: Arial, sans-serif;
                font-size: 16px;
                text-align: center;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            '>
                Erro ao realizar a matrícula: " . htmlspecialchars($stmt->error) . "
                <br><br>
                <a href='matricula.html' style='
                    display: inline-block;
                    margin-top: 10px;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    font-weight: bold;
                    transition: background-color 0.3s ease;
                ' onmouseover=\"this.style.backgroundColor='#0056b3';\" onmouseout=\"this.style.backgroundColor='#007bff';\">Voltar para tela de matrícula</a>
            </div>";
        }
    }

    // Fechar a consulta e a conexão
    $stmt->close();
    $conn->close();
} else {
    echo "<div style='
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        font-family: Arial, sans-serif;
        font-size: 16px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    '>
        Método de envio inválido.
        <br><br>
        <a href='matricula.html' style='
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        ' onmouseover=\"this.style.backgroundColor='#0056b3';\" onmouseout=\"this.style.backgroundColor='#007bff';\">Voltar para tela de matrícula</a>
    </div>";
}
?>
