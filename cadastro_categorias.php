<?php


$erro = false; 

if (count($_POST) > 0) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    if (empty($nome)) {
        $erro = "Preencha o nome.";
    } else {
        try {
            include('conexao.php');
            
            //Validar nome já cadastrado
            $sql_verificar = "SELECT COUNT(*) AS total FROM categorias WHERE nome = :nome";
            $stmt_verificar = $pdo->prepare($sql_verificar);
            $stmt_verificar->bindParam(':nome', $nome);
            $stmt_verificar->execute();
            
            $resultado = $stmt_verificar->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado['total'] > 0) {
                $erro = "Categoria já cadastrada.";
            }
            
            if (!$erro) {
                //Inserir o registro se não houver erro
                $sql_inserir = "INSERT INTO categorias (nome, descricao) VALUES (:nome, :descricao)";
                $stmt = $pdo->prepare($sql_inserir);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':descricao', $descricao);
                
                $deu_certo = $stmt->execute();
                
                if ($deu_certo) {
                    echo "<p><b>Categoria cadastrada com sucesso!</b></p>";
                    unset($_POST);
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



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de categorias</title>
</head>
<body>
    <main>
        <section class="principal">
            <a href="categorias.php">Voltar para a lista</a>
                <form method="POST" action="">
                    <p>
                        <label class="lblPrinc">*Nome</label><br>
                        <input value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>" name="nome" class="input-padrao" type="text">
                 
                    <br>
                    <p>
                        <label class="lblPrinc">Descrição</label><br>
                        <textarea value="<?php if(isset($_POST['descricao'])) echo $_POST['descricao']; ?>" name="descricao" class="input-padrao" type="text"></textarea>
                    
                    <br>
                    <br><br>
                    <p>
                        <button name="salvar_categoria" class="enviar" type="submit">Salvar categoria</button>
                    </p>
                </form>
        </section>
    </main>
    
</body>
</html>