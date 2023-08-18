<?php
include('conexao.php');
$cod_categoria = intval($_GET['cod_categoria']);


if (count($_POST) > 0) {
    $erro = false;
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    if (empty($nome)) {
        $erro = "Preencha o nome.";
    }

    if ($erro) {
        echo "<p><b>Erro: $erro</b></p>";       
    } else {
        try {
            $sql_code = "UPDATE categorias
            SET nome = :nome,
            descricao = :descricao
            WHERE cod_categoria = :cod_categoria";   
            $stmt = $pdo->prepare($sql_code);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':cod_categoria', $cod_categoria);
            
            $deu_certo = $stmt->execute();
            
            if ($deu_certo) {
                echo "<p><b>Categoria atualizada com sucesso.</b></p>";
                unset($_POST);
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}

try {
    $sql_categoria = "SELECT * FROM categorias WHERE cod_categoria = :cod_categoria";
    $stmt_categoria = $pdo->prepare($sql_categoria);
    $stmt_categoria->bindParam(':cod_categoria', $cod_categoria);
    $stmt_categoria->execute();
    $categoria = $stmt_categoria->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Edição de categorias</title>

    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style-home.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
<main>
    <section class="principal">
        <a href="categorias.php">Voltar para a lista</a>
        <form method="POST" action="">
            <p>
                <label class="lblPrinc">Nome</label>
                <input value="<?php echo $categoria['nome']; ?>" name="nome" class="input-padrao" type="text">
            </p>
            <p>
                <label class="lblPrinc">Descrição</label>
                <textarea name="descricao" class="input-padrao" type="text"><?php echo $categoria['descricao']; ?></textarea>
            </p>
            <p>
                <button name="atualizar_categoria" class="enviar" type="submit">Atualizar categoria</button>
            </p>
        </form>
    </section>
</main>
</body>
</html>
