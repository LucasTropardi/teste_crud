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
            <h1>Categoria deletada com sucesso.</h1>
            <p><a href="categorias.php">Clique aqui</a> para voltar à lista de categorias</p>
        <?php    
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
    <title>Deletar categoria</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-home.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
<main>
    <section class="principal">
        <h1>Tem certeza que deseja deletar esta categoria?</h1>
        <form action="" method="POST">
            <a style="margin-right: 10px;" href="categorias.php"><b>Não</b></a>
            <button name="confirmar" value="1" type="submit"><b>Sim</b></button>
        </form>
        
    </section>
</main>
</body>
</html>
