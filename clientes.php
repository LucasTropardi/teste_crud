<?php
include('conexao.php');

try {
    $filtro_cidade = isset($_GET['cidade']) ? $_GET['cidade'] : "";
    $filtro_sql = !empty($filtro_cidade) ? "WHERE cidade LIKE '%$filtro_cidade%'" : "";

    $sql_clientes = "SELECT clientes.*, categorias.nome AS nome_categoria FROM clientes LEFT JOIN categorias ON clientes.cod_categoria = categorias.cod_categoria $filtro_sql";
    $stmt = $pdo->query($sql_clientes);
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $num_clientes = count($clientes);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <!--<link rel="stylesheet" href="css/style-home.css">-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
</head>
<body>
    <main>
        <section class="principal">
            <h1>Lista de Clientes</h1>
            <p>Estes são os clientes cadastrados no sistema:</p>
            <a href="cadastro_clientes.php">cadastrar cliente</a>
            <br>
            <form method="GET">
                <label for="cidade">Filtrar por cidade:</label>
                <input type="text" name="cidade" value="<?php echo $filtro_cidade; ?>">
                <button type="submit">Aplicar Filtro</button>
            </form>
            <table border="1">
                <thead>
                    <th>ID</th>
                    <th>Categoria</th>
                    <th>Nome</th>
                    <th>RG</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                    <th>Endereço</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                    <th>Data de Nascimento</th>
                    <th>Telefone</th>
                    <th>Celular</th>
                    <th>Foto</th>
                    <th>Ações</th>
                </thead>
                <tbody>
                    <?php
                    if ($num_clientes == 0) { ?>
                        <tr>
                            <td colspan="13">Nenhum cliente cadastrado</td>
                        </tr>
                    <?php } else {
                        foreach ($clientes as $cliente) {
                        ?>    
                        <tr>
                            <td><?php echo $cliente['cod_cliente']; ?></td>
                            <td><?php echo $cliente['nome_categoria']; ?></td>
                            <td><?php echo $cliente['nome']; ?></td>
                            <td><?php echo $cliente['rg']; ?></td>
                            <td><?php echo $cliente['cpf']; ?></td>
                            <td><?php echo $cliente['email']; ?></td>
                            <td><?php echo $cliente['endereco']; ?></td>
                            <td><?php echo $cliente['cidade']; ?></td>
                            <td><?php echo $cliente['estado']; ?></td>
                            <td><?php echo $cliente['nascimento']; ?></td>
                            <td><?php echo $cliente['telefone']; ?></td>
                            <td><?php echo $cliente['celular']; ?></td>
                            <td><img src="<?php echo $cliente['foto']; ?>" width="100" height="100" alt="Foto do Cliente"></td>
                            <td>
                                <a href="edita_cliente.php?cod_cliente=<?php echo $cliente['cod_cliente']; ?>">Editar</a>
                                <a href="deleta_cliente.php?cod_cliente=<?php echo $cliente['cod_cliente']; ?>">Deletar</a>
                            </td>
                        </tr>
                    <?php } }?>
                </tbody>
            </table>   
        </section>
    </main>
</body>
</html>
