<?php
include 'db_connect.php'; // Inclua o arquivo de conexão

// Consultar todos os produtos
$sql = "SELECT id, nome FROM produtos";
$result = $conn->query($sql);

$produtos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}

// Fechar conexão
$conn->close();
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provador Virtual</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-container {
            margin: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .alert {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <h2>Provador Virtual</h2>
        <form id="provador-form">
            <div class="form-group">
                <label for="produto-input">Digite o ID ou nome do produto:</label>
                <input type="text" class="form-control" id="produto-input" placeholder="ID ou nome do produto" required>
            </div>
            <div class="form-group">
                <label for="tamanho-input">Tamanho:</label>
                <select class="form-control" id="tamanho-input" required>
                    <option value="">Selecione um tamanho</option>
                    <option value="P">P</option>
                    <option value="M">M</option>
                    <option value="G">G</option>
                    <option value="GG">GG</option>
                </select>
            </div>
            <!-- Adicione mais campos para medidas aqui -->
            <button type="submit" class="btn btn-primary">Verificar Medidas</button>
        </form>
        <div id="resultado" class="alert alert-info mt-3" role="alert"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('provador-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            var produtoInput = document.getElementById('produto-input').value;
            var tamanhoInput = document.getElementById('tamanho-input').value;
            var resultado = document.getElementById('resultado');

            if (produtoInput && tamanhoInput) {
                fetch('get_medidas.php?produto=' + encodeURIComponent(produtoInput) + '&tamanho=' + encodeURIComponent(tamanhoInput))
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            var medidas = data[0];
                            resultado.innerHTML = `
                                <strong>Produto:</strong> ${medidas.nome_produto}<br>
                                <strong>Tamanho:</strong> ${medidas.tamanho}<br>
                                <strong>Comprimento:</strong> ${medidas.comprimento_min} - ${medidas.comprimento_max}<br>
                                <strong>Ombro:</strong> ${medidas.ombro_min} - ${medidas.ombro_max}<br>
                                <strong>Busto:</strong> ${medidas.busto_min} - ${medidas.busto_max}<br>
                                <strong>Cintura:</strong> ${medidas.cintura_min} - ${medidas.cintura_max}<br>
                                <strong>Quadril:</strong> ${medidas.quadril_min} - ${medidas.quadril_max}<br>
                                <strong>Manga:</strong> ${medidas.manga_min} - ${medidas.manga_max}
                            `;
                            resultado.classList.remove('alert-info');
                            resultado.classList.add('alert-success');
                        } else {
                            resultado.innerHTML = 'Nenhum dado encontrado para o produto fornecido.';
                            resultado.classList.remove('alert-success');
                            resultado.classList.add('alert-info');
                        }
                        resultado.style.display = 'block';
                    })
                    .catch(error => {
                        resultado.innerHTML = 'Erro ao buscar as medidas.';
                        resultado.classList.remove('alert-success');
                        resultado.classList.add('alert-info');
                        resultado.style.display = 'block';
                        console.error('Erro:', error);
                    });
            } else {
                resultado.innerHTML = 'Por favor, preencha todos os campos.';
                resultado.classList.remove('alert-success');
                resultado.classList.add('alert-info');
                resultado.style.display = 'block';
            }
        });
    </script>
</body>
</html>


