<?php
include('conexao.php');

try {
    $sql_categorias = "SELECT * FROM categorias";
    $stmt = $pdo->query($sql_categorias);
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $num_categorias = count($categorias);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias</title>
</head>
<body>
    <main>
        <section class="principal">
            <h1>Lista de categorias</h1>
            <p>Estas são as categorias cadastradas no sistema:</p>
            <table border="1">
                <thead>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>    
                    <th>Ações</th>
                </thead>
                <tbody>
                    <?php
                    if ($num_categorias == 0) { ?>
                        <tr>
                            <td colspan="4">Nenhuma categoria cadastrada</td>
                        </tr>
                    <?php } else { 
                        foreach ($categorias as $categoria) {
                        ?>    
                        <tr>
                            <td><?php echo $categoria['cod_categoria']; ?></td>
                            <td><?php echo $categoria['nome']; ?></td>
                            <td><?php echo $categoria['descricao']; ?></td>
                            <td>
                                <a href="edita_categoria.php?cod_categoria=<?php echo $categoria['cod_categoria']; ?>">Editar</a>
                                <a href="deleta_categoria.php?cod_categoria=<?php echo $categoria['cod_categoria']; ?>">Deletar</a>
                            </td>
                        </tr>
                    <?php } }?>
                </tbody>
            </table>   
        </section>
    </main>
</body>
</html>
