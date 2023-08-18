<?php

include('conexao.php');


$erro = false; 

if (count($_POST) > 0) {
    $cod_categoria = $_POST['cod_categoria'];
    $nome = $_POST['nome'];
    $rg = $_POST['rg'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $nascimento = $_POST['nascimento'];
    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;
    $telefone = $_POST['telefone'];
    $celular = isset($_POST['celular']) ? $_POST['celular'] : null;

    if (empty($nome)) {
        $erro = "Preencha o nome.";
    } 
    if (!empty($rg)) {
        $rg = Limpar_texto($rg);
    }
    if (empty($cpf)) {
        $erro = "Informe o CPF.";
    } elseif (!validar_cpf($cpf)) {
        $erro = "CPF inválido, verifique.";
    }
    if (empty($email)) {
        $erro = "Informe o e-mail.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Informe um e-mail válido.";
    }
    if (empty($endereco)) {
        $erro = "Informe o endereço.";
    }
    if (empty($cidade)) {
        $erro = "Informe a cidade.";
    }
    if (empty($estado)) {
        $erro = "Informe o estado.";
    }
    if (isset($_FILES['foto']) && $_FILES['foto']['name'] !== "") {
        if ($_FILES['foto']['error']) {
            die("Falha ao enviar o arquivo.");
        }
        if ($_FILES['foto']['size'] > 5242880) {
            die("Arquivo muito grande, máximo permitido 5 MB.");
        }
        $pasta = "imagens/";
        $nome_foto = $_FILES['foto']['name'];
        $nome_unico = uniqid();
        $extensao = strtolower(pathinfo($nome_foto, PATHINFO_EXTENSION));
    
        if ($extensao != "jpg" && $extensao != "jpeg" && $extensao != "png") {
            die("Tipo de arquivo não suportado, somente jpg, jpeg e png.");
        }
        $path = $pasta . $nome_unico . "." . $extensao;
        $passou = move_uploaded_file($_FILES['foto']['tmp_name'], $path);
        if (!$passou) {
            $erro = "Falha ao enviar o arquivo.";
        } else {
            $foto = $path; // Atribuir o caminho da foto à variável $foto
        }
    }
        //echo "<script>console.log($cpf)</script>";
        //usei o console para verificar o motivo do bug de não salvar os dois digitos verificadores do cpf
        try {            
            
            if (!$erro) {
                //inserir o registro se não houver erros
                $sql_inserir = "INSERT INTO clientes (cod_categoria, nome, rg, cpf, email, endereco, cidade, estado, nascimento, foto, telefone, celular) VALUES (:cod_categoria, :nome, :rg, :cpf, :email, :endereco, :cidade, :estado, :nascimento, :foto, :telefone, :celular)";
                $stmt = $pdo->prepare($sql_inserir);
                $stmt->bindParam(':cod_categoria', $cod_categoria, PDO::PARAM_INT);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':rg', $rg);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':endereco', $endereco);
                $stmt->bindParam(':cidade', $cidade);
                $stmt->bindParam(':estado', $estado);
                $stmt->bindParam(':nascimento', $nascimento);
                $stmt->bindParam(':foto', $foto);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':celular', $celular);

                
                $deu_certo = $stmt->execute();
                
                if ($deu_certo) {
                    echo "<p><b>Cliente cadastrado com sucesso!</b></p>";
                    unset($_POST);
                }
            } else {
                echo "<p><b>Erro: $erro</b></p>";       
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de clientes</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style-home.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
</head>
<body>
    <main>
        <section class="principal">
            <a href="clientes.php">Voltar para a lista</a>
                <form method="POST" enctype="multipart/form-data" action="">
                    <p>
                        <label class="lblPrinc">Categoria</label><br>
                        <select name="cod_categoria" class="input-padrao">
                        <option value="">Selecione uma categoria</option>
                        <?php
                            //buscar as categorias
                            $sql_categorias = "SELECT * FROM categorias";
                            $stmt_categorias = $pdo->prepare($sql_categorias);
                            $stmt_categorias->execute();

                            //preenche o select box com os nomes das categorias
                            while ($categoria = $stmt_categorias->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $categoria['cod_categoria'] . "'>" . $categoria['nome'] . "</option>";
                            }
                        ?>
                        </select>
                    </p>
                    <br>
                    <p>
                        <label class="lblPrinc">*Nome</label><br>
                        <input value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>" name="nome" class="input-padrao" type="text">
                    <br>
                    <p>
                        <label class="lblPrinc">RG</label><br>
                        <input value="<?php if(isset($_POST['rg'])) echo $_POST['rg']; ?>" name="rg" class="input-padrao" type="text">
                    <br>
                    <p>
                        <label class="lblPrinc">*CPF</label><br>
                        <input value="<?php if(isset($_POST['cpf'])) echo $_POST['cpf']; ?>" name="cpf" class="input-padrao" type="text">
                    <br>
                    <p>
                        <label class="lblPrinc">*E-mail</label><br>
                        <input value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" name="email" class="input-padrao" type="text">
                    <br>
                    <p>
                        <label class="lblPrinc">*Endereço</label><br>
                        <input value="<?php if(isset($_POST['endereco'])) echo $_POST['endereco']; ?>" name="endereco" class="input-padrao" type="text">
                    <br>
                    <p>
                        <label class="lblPrinc">*Cidade</label><br>
                        <input value="<?php if(isset($_POST['cidade'])) echo $_POST['cidade']; ?>" name="cidade" class="input-padrao" type="text">
                    <br>
                    <p>
                        <label class="lblPrinc">*Estado</label><br>
                        <input value="<?php if(isset($_POST['estado'])) echo $_POST['estado']; ?>" name="estado" id="estado" class="input-padrao" type="text" maxlength="2" oninput="formatarEstado(this)">
                    <br>
                    <p>
                        <label class="lblPrinc">Data de nascimento</label><br>
                        <input value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']; ?>" name="nascimento" class="input-padrao" type="text">
                    <br>
                    <p>
                        <label class="lblPrinc">Telefone</label><br>
                        <input value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone']; ?>" name="telefone" class="input-padrao" type="text">
                    <br>
                    <p>
                        <label class="lblPrinc">*Celular</label><br>
                        <input value="<?php if(isset($_POST['celular'])) echo $_POST['celular']; ?>" name="celular" class="input-padrao" type="text">
                    <br>
                    <br>
                    <p>
                        <label class="lblPrinc">Foto</label><br>
                        <input value="<?php if(isset($_FILES['foto'])) echo $_FILES['foto']; ?>" name="foto" class="input-padrao" type="file">
                    <br>
                    <br>
                    <br><br>
                    <p>
                        <button name="salvar_cliente" class="enviar" type="submit">Salvar cliente</button>
                    </p>
                </form>
        </section>
    </main>
    <script>
        $(document).ready(function() {
        // Máscara para RG
        $("input[name='rg']").inputmask("99.999.999-9");
        
        // Máscara para CPF
        $("input[name='cpf']").inputmask("999.999.999-99");

        // Máscara para Data de Nascimento
        $("input[name='nascimento']").inputmask("99/99/9999");

        // Máscara para Telefone e Celular
        $("input[name='telefone'], input[name='celular']").inputmask("(99) 9999-9999[9]");
        });
        function formatarEstado(input) {
            input.value = input.value.replace(/[^A-Za-z]/g, '').toUpperCase();
        }
    </script>
</body>
</html>