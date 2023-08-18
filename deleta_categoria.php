<?php
include('conexao.php');
if(isset($_POST['confirmar'])) {
    $cod_categoria = intval($_GET['cod_categoria']);
    
    try {
        $sql_code = "DELETE FROM categorias WHERE cod_categoria = :cod_categoria";
        $stmt = $pdo->prepare($sql_code);
        $stmt->bindParam(':cod_categoria', $cod_categoria);
        $deu_certo = $stmt->execute();

        if ($deu_certo) { ?>
            <div class="container mt-5">
                <h1>Categoria deletada com sucesso.</h1>
                <p><a href="categorias.php" class="btn btn-primary">Clique aqui</a> para voltar à lista de categorias</p>
            </div>
        <?php    
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
    <title>Deletar categoria</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <main>
        <section class="container mt-5">
            <h1>Tem certeza que deseja deletar esta categoria?</h1>
            <form action="" method="POST">
                <a href="categorias.php" class="btn btn-secondary">Não</a>
                <button name="confirmar" value="1" class="btn btn-danger" type="submit">Sim</button>
            </form>
        </section>
    </main>
    
    <!-- Optional: jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
