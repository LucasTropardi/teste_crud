<?php
require('conexao.php');

try {
    $filtro_cidade = isset($_GET['cidade']) ? $_GET['cidade'] : "";
    $filtro_sql = !empty($filtro_cidade) ? "WHERE cidade LIKE :filtro_cidade" : "";

    $sql_clientes = "SELECT clientes.*, categorias.nome AS nome_categoria FROM clientes LEFT JOIN categorias ON clientes.cod_categoria = categorias.cod_categoria $filtro_sql";
    $stmt = $pdo->prepare($sql_clientes);

    if (!empty($filtro_cidade)) {
        $filtro_cidade_param = '%' . $filtro_cidade . '%';
        $stmt->bindParam(':filtro_cidade', $filtro_cidade_param, PDO::PARAM_STR);
    }

    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $num_clientes = count($clientes);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <title>Clientes</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <main class="container mt-4">
        <section class="principal">
            <h1 class="mb-3">Lista de Clientes</h1>
            <a href="cadastro_clientes.php" class="btn btn-primary mb-3">Cadastrar Cliente</a>
            <a href="categorias.php" class="btn btn-primary mb-3">Lista de Categorias</a>

            <form class="mb-3" method="GET">
                <div class="form-group">
                    <label for="cidade">Filtrar por cidade:</label>
                    <input type="text" class="form-control" name="cidade" value="<?php echo $filtro_cidade; ?>">
                </div>
                <button type="submit" class="btn btn-secondary">Aplicar Filtro</button>
            </form>

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
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
                    </tr>
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
                                <a href="edita_cliente.php?cod_cliente=<?php echo $cliente['cod_cliente']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="deleta_cliente.php?cod_cliente=<?php echo $cliente['cod_cliente']; ?>" class="btn btn-sm btn-danger">Deletar</a>
                            </td>
                        </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
