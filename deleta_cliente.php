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
            echo "<h1>Cliente deletado com sucesso.</h1>";
            echo "<p><a href='clientes.php'>Clique aqui</a> para voltar à lista de clientes</p>";
            die();
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Exclusão de Cliente</title>

    <!-- Seus estilos e scripts aqui -->

</head>
<body>
<main>
    <section class="principal">
        <h1>Confirmação de Exclusão</h1>
        <p>Tem certeza de que deseja excluir este cliente?</p>
        <form method="POST" action="">
            <input type="submit" name="confirmar" value="Confirmar">
            <a href="clientes.php">Cancelar</a>
        </form>
    </section>
</main>
<!-- Seus scripts aqui -->

</body>
</html>
