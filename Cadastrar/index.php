<?php
include 'db_connect.php'; 

$sql = "SELECT id, nome FROM tipos_produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medida Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .border-inputs { border: none; font-size: 12px; border-radius: 2px; width: 70px; }
        body { padding: 2rem; background-color: #f1f1f1; display: flex; align-items: center; justify-content: center; margin: 0; min-height: 100vh; }
        .container { padding: 20px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; width: 100%; max-width: 1000px; }
        .form-section { margin-bottom: 20px; }
        .form-section h4 { margin-bottom: 15px; color: #333; }
        .table-container { 
            margin-top: 15px;
            overflow-x: auto;
        }
        .table {
        width: 100%; /* Garante que a tabela ocupe toda a largura disponível */
        min-width: 800px; /* Define uma largura mínima para a tabela */
        }
        .form-check-group { display: flex; flex-wrap: wrap; gap: 10px; }
        input[type="text"]:invalid { border-color: red; }
        input[type="text"]:valid { border-color: green; }

        /* Estilos para o spinner e mensagem de sucesso */
        .spinner-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
        }
        .spinner-border-custom {
            width: 3rem;
            height: 3rem;
            border-width: 0.4em;
        }
        .success-message {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .success-message.show {
            display: block;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-3">CADASTRAR NOVO PRODUTO</h1>
        <div class="form-section">
            <h4>Informações do Produto</h4>
            <form action="salvar.php" method="POST">
                <div class="mb-2">
                    <label for="productName" class="form-label">Nome do Produto</label>
                    <input type="text" class="form-control" id="productName" name="productName" placeholder="Digite o nome do produto" required>
                </div>
                <div class="mb-2">
                    <label for="productType" class="form-label">Tipo de Produto</label>
                    <select class="form-select" id="productType" name="productType" required>
                        <option value="" disabled selected>Escolha o tipo de produto</option>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="table table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tamanho</th>
                                <th>Comprimento</th>
                                <th>Ombro</th>
                                <th>Busto</th>
                                <th>Cintura</th>
                                <th>Quadril</th>
                                <th>Manga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- As linhas são geradas pelo JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
  <path d="M11 2H9v3h2z"/>
  <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
</svg>
                        Salvar
                    </button>
                </div>
            </form>
            <!-- <div class="text-center mt-3">
                <a href="listar_produtos.php" class="btn btn-secondary">Ver Produtos Cadastrados</a>
            </div> -->
        </div>
    </div>


<div class="spinner-container" id="spinner">
    <div class="spinner-border spinner-border-custom" role="status"></div>
</div>
<div class="success-message" id="successMessage">
    Produto salvo com sucesso!
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('get_sizes.php')
            .then(response => response.json())
            .then(sizes => {
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = ''; // Limpa as linhas existentes

                sizes.forEach(size => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${size}</td>
                        <td>
                            <div class="d-flex flex-column flex-md-row">
                                <input name="comprimento_${size.toLowerCase()}_min" type="number" class="form-control border-inputs mb-1 mb-md-0" placeholder="Mín" aria-label="Comprimento Mín ${size}" required>
                                <input name="comprimento_${size.toLowerCase()}_max" type="number" class="form-control border-inputs" placeholder="Máx" aria-label="Comprimento Máx ${size}" required>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column flex-md-row">
                                <input name="ombro_${size.toLowerCase()}_min" type="number" class="form-control border-inputs mb-1 mb-md-0" placeholder="Mín" aria-label="Ombro Mín ${size}" required>
                                <input name="ombro_${size.toLowerCase()}_max" type="number" class="form-control border-inputs" placeholder="Máx" aria-label="Ombro Máx ${size}" required>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column flex-md-row">
                                <input name="busto_${size.toLowerCase()}_min" type="number" class="form-control border-inputs mb-1 mb-md-0" placeholder="Mín" aria-label="Busto Mín ${size}" required>
                                <input name="busto_${size.toLowerCase()}_max" type="number" class="form-control border-inputs" placeholder="Máx" aria-label="Busto Máx ${size}" required>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column flex-md-row">
                                <input name="cintura_${size.toLowerCase()}_min" type="number" class="form-control border-inputs mb-1 mb-md-0" placeholder="Mín" aria-label="Cintura Mín ${size}" required>
                                <input name="cintura_${size.toLowerCase()}_max" type="number" class="form-control border-inputs" placeholder="Máx" aria-label="Cintura Máx ${size}" required>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column flex-md-row">
                                <input name="quadril_${size.toLowerCase()}_min" type="number" class="form-control border-inputs mb-1 mb-md-0" placeholder="Mín" aria-label="Quadril Mín ${size}" required>
                                <input name="quadril_${size.toLowerCase()}_max" type="number" class="form-control border-inputs" placeholder="Máx" aria-label="Quadril Máx ${size}" required>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column flex-md-row">
                                <input name="manga_${size.toLowerCase()}_min" type="number" class="form-control border-inputs mb-1 mb-md-0" placeholder="Mín" aria-label="Manga Mín ${size}" required>
                                <input name="manga_${size.toLowerCase()}_max" type="number" class="form-control border-inputs" placeholder="Máx" aria-label="Manga Máx ${size}" required>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // Adiciona os eventos de input para verificar os valores mínimos e máximos
                const inputsMin = document.querySelectorAll('input[name$="_min"]');
                const inputsMax = document.querySelectorAll('input[name$="_max"]');

                inputsMin.forEach(inputMin => {
                    inputMin.addEventListener('input', function() {
                        const size = this.name.split('_')[1];
                        const correspondingMax = document.querySelector(`input[name="${this.name.replace('_min', '_max')}"]`);

                        if (parseFloat(this.value) > parseFloat(correspondingMax.value)) {
                            correspondingMax.value = this.value;
                        }
                    });
                });

                inputsMax.forEach(inputMax => {
                    inputMax.addEventListener('input', function() {
                        const size = this.name.split('_')[1];
                        const correspondingMin = document.querySelector(`input[name="${this.name.replace('_max', '_min')}"]`);

                        if (parseFloat(this.value) < parseFloat(correspondingMin.value)) {
                            this.value = correspondingMin.value;
                        }
                    });
                });
            })
            .catch(error => console.error('Erro ao carregar tamanhos:', error));
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('form');
        const spinner = document.getElementById('spinner');
        const successMessage = document.getElementById('successMessage');

        form.addEventListener('submit', function(event) {
            event.preventDefault(); 
            spinner.style.display = 'block';

            const formData = new FormData(form);

            fetch('salvar.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta da API');
                }
                return response.text();
            })
            .then(result => {

                setTimeout(() => {

                    spinner.style.display = 'none';
                    successMessage.classList.add('show');
                    
                    setTimeout(function() {
                        successMessage.classList.remove('show');
                        form.reset();
                    }, 1000);
                }, 2000);
            })
            .catch(error => {
                console.error('Erro ao enviar o formulário:', error);
                spinner.style.display = 'none';
            });
        });
    });
</script>


</body>
</html>

<?php
$conn->close();
?>
