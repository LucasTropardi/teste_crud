<?php
require('conexao.php');
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
                unset($_POST);
                header("Location: msg_categoria_atualizada.php");
                die();
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

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Editar categoria</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <main>
        <section class="container mt-5">
            <h1 class="display-4">Atualizar registro</h1><br><br>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input value="<?php echo $categoria['nome']; ?>" name="nome" class="form-control" type="text" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" class="form-control"><?php echo $categoria['descricao']; ?></textarea>
                </div>
                <button name="atualizar_categoria" class="btn btn-primary" type="submit">Atualizar categoria</button>
                <a href="categorias.php" class="btn btn-secondary">Voltar para a lista</a>
            </form>
        </section>
    </main>
    
    <!-- Optional: jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
