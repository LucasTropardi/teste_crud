<?php
include('conexao.php');

if (isset($_POST['confirmar'])) {
    $cod_cliente = intval($_GET['cod_cliente']);

    try {
        $sql_code = "DELETE FROM clientes WHERE cod_cliente = :cod_cliente";
        $stmt = $pdo->prepare($sql_code);
        $stmt->bindParam(':cod_cliente', $cod_cliente);
        $deu_certo = $stmt->execute();

        if ($deu_certo) {
            echo "<h1 class='mt-5'>Cliente deletado com sucesso.</h1>";
            echo "<p><a href='clientes.php' class='btn btn-primary'>Clique aqui</a> para voltar à lista de clientes</p>";
            die();
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Exclusão de Cliente</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <main class="container mt-5">
        <section class="principal">
            <h1 class="mb-4">Confirmação de Exclusão</h1>
            <p>Tem certeza de que deseja excluir este cliente?</p>
            <form method="POST" action="">
                <button type="submit" name="confirmar" class="btn btn-danger">Confirmar</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </section>
    </main>
</body>
</html>
