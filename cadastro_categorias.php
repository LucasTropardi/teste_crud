<?php
$erro = false; 

if (count($_POST) > 0) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    if (empty($nome)) {
        $erro = "Preencha o nome.";
    } else {
        try {
            require('conexao.php');
            
            // Validar nome já cadastrado
            $sql_verificar = "SELECT COUNT(*) AS total FROM categorias WHERE nome = :nome";
            $stmt_verificar = $pdo->prepare($sql_verificar);
            $stmt_verificar->bindParam(':nome', $nome);
            $stmt_verificar->execute();
            
            $resultado = $stmt_verificar->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado['total'] > 0) {
                $erro = "Categoria já cadastrada.";
            }
            
            if (!$erro) {
                // Inserir o registro se não houver erro
                $sql_inserir = "INSERT INTO categorias (nome, descricao) VALUES (:nome, :descricao)";
                $stmt = $pdo->prepare($sql_inserir);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':descricao', $descricao);
                
                $deu_certo = $stmt->execute();
                
                if ($deu_certo) {
                    unset($_POST);
                    header("Location: msg_categoria_cadastro.php");
                    die();
                }
            } else {
                echo "<p><b>Erro: $erro</b></p>";       
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastrar categorias</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <main>
        <section class="container mt-5">
            <form method="POST" action="">
                <h1 class="display-4">Nova categoria</h1><br><br>
                <div class="form-group">
                    <label for="nome">Nome *</label>
                    <input value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>" name="nome" class="form-control" type="text" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" class="form-control"><?php if(isset($_POST['descricao'])) echo $_POST['descricao']; ?></textarea>
                </div>
                <button name="salvar_categoria" class="btn btn-primary" type="submit">Salvar categoria</button>
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
