<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tripulantes</title>
    <script src="scripts.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php if (isset($requestError)) { ?>
        <div class="grid-container">
            <div class="grid-item col-12">
                <div class='alert alert-danger'> <?php echo $requestError ?> </div>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($requestSuccess)) { ?>
        <div class="grid-item col-12">
            <div class='alert alert-success'> <?php echo $requestSuccess ?> </div>
        </div>
    <?php } ?>

    <div class="grid-container">
        <div class="grid-item col-12">
            <h1 class="title">Tripulantes</h1>
            <table id="tripulantes-table" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Criação</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid-container">
        <div class="grid-item col-6">
            <h1 class="title">Bilhetes</h1>
            <div class="grid-item col-12">
                <button class="btn" onClick="openModal()">Gerar Bilhetes</button>
                <a href="/remover-bilhetes" class="btn">Remover Bilhetes</a>
            </div>
            <table id="bilhetes-table" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dezenas</th>
                        <th>Tripulante</th>
                        <th>Data de Criação</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="grid-item col-6">
            <h1 class="title">Bilhete Premiado</h1>
            <div class="grid-item col-12">
                <a class="btn" href="/gerar-bilhete-premiado">Gerar Bilhete Premiado</a>
            </div>
            <table id="bilhetes-premiados-table" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Dezenas</th>
                        <th>Data de Criação</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span onClick="closeModal()" class="close">&times;</span>
            <h1 class="title">Gerar Bilhetes</h1>
            <form action="/gerar-bilhete" method="POST">

                <label for="quantidade_bilhetes">Quantidade de Bilhetes</label>
                <input type="number" id="quantidadeBilhetes" name="quantidadeBilhetes" min="1" max="50" required>

                <label for="quantidade_numeros">Quantidade de Números</label>
                <input type="number" id="quantidadeDezenas" name="quantidadeDezenas" min="6" max="10" required>

                <label for="tripulante">Tripulante</label>
                <select id="idTripulante" name="idTripulante" required>
                    <option value="">Selecione um Tripulante</option>
                </select>

                <button type="submit" class="btn">Gerar Bilhetes</button>
            </form>
        </div>
    </div>

</body>

<script type="text/javascript">
    window.onload = carregarDadosPagina;

    window.onclick = function(event) {
        if (event.target === document.getElementById("myModal")) {
            document.getElementById("myModal").style.display = "none";
        }
    }
</script>

</html>