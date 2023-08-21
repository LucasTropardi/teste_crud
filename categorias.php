<?php
require('conexao.php');

try {
    $sql_categorias = "SELECT * FROM categorias";
    $stmt = $pdo->query($sql_categorias);
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $num_categorias = count($categorias);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Categorias</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <main>
        <section class="container mt-5">
            <h1 class="display-4">Lista de categorias</h1>
            <p>Estas são as categorias cadastradas no sistema:</p>

            <a href="cadastro_categorias.php" class="btn btn-primary mb-3">Cadastrar Categorias</a>
            <a href="index.php" class="btn btn-primary mb-3">Lista de Clientes</a><br>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($num_categorias == 0) { ?>
                            <tr>
                                <td colspan="4">Nenhuma categoria cadastrada</td>
                            </tr>
                        <?php } else {
                            foreach ($categorias as $categoria) { ?>
                                <tr>
                                    <td><?php echo $categoria['cod_categoria']; ?></td>
                                    <td><?php echo $categoria['nome']; ?></td>
                                    <td><?php echo $categoria['descricao']; ?></td>
                                    <td>
                                        <a href="edita_categoria.php?cod_categoria=<?php echo $categoria['cod_categoria']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                        <a href="deleta_categoria.php?cod_categoria=<?php echo $categoria['cod_categoria']; ?>" class="btn btn-danger btn-sm">Deletar</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-aIyF0OsRd3Jz3pUCNE5w1sF5js/xl+poTzoG9+A0bsW1Ofck2" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
