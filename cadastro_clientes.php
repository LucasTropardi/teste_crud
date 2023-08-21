<?php

require('conexao.php');


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
    $data_formatada = 0000-00-00;
    $foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;
    $telefone = $_POST['telefone'];
    $celular = isset($_POST['celular']) ? $_POST['celular'] : null;

    if (!empty($nascimento)) {
        $dateTime = DateTime::createFromFormat('d/m/Y', $nascimento);
    
        if ($dateTime !== false) {
            $data_formatada = $dateTime->format('Y-m-d');
        }
    }

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
    } else {
        $foto = "Sem foto";
    }
        //echo "<script>console.log($nascimento)</script>";
        //verificação de bugs ao salvar campos.
        try {
            //validar e-mail já cadastrado
            $sql_verificar_email = "SELECT COUNT(*) AS total FROM clientes WHERE email = :email";
            $stmt_verificar_email = $pdo->prepare($sql_verificar_email);
            $stmt_verificar_email->bindParam(':email', $email);
            $stmt_verificar_email->execute();

            $resemail = $stmt_verificar_email->fetch(PDO::FETCH_ASSOC);

            if ($resemail['total'] > 0) {
                $erro = "E-mail já cadastrado, verifique.";
            }

            //validar cpf ja cadastrado
            $sql_verificar_cpf = "SELECT COUNT(*) AS tem_cpf FROM clientes WHERE cpf = :cpf";
            $stmt_verificar_cpf = $pdo->prepare($sql_verificar_cpf);
            $stmt_verificar_cpf->bindParam(':cpf', $cpf);
            $stmt_verificar_cpf->execute();

            $rescpf = $stmt_verificar_cpf->fetch(PDO::FETCH_ASSOC);

            if ($rescpf['tem_cpf'] > 0) {
                $erro = "CPF já cadastrado, verifique.";
            }
            
            if (!$erro) {
                //inserir o registro se não houver erros
                $sql_inserir = "INSERT INTO clientes (cod_categoria, nome, rg, cpf, email, endereco, cidade, estado, nascimento, foto, telefone, celular) VALUES (:cod_categoria, :nome, :rg, :cpf, :email, :endereco, :cidade, :estado, :data_formatada, :foto, :telefone, :celular)";
                $stmt = $pdo->prepare($sql_inserir);
                $stmt->bindParam(':cod_categoria', $cod_categoria, PDO::PARAM_INT);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':rg', $rg);
                $stmt->bindParam(':cpf', $cpf);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':endereco', $endereco);
                $stmt->bindParam(':cidade', $cidade);
                $stmt->bindParam(':estado', $estado);
                $stmt->bindParam(':data_formatada', $data_formatada);
                $stmt->bindParam(':foto', $foto, PDO::PARAM_STR);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':celular', $celular);
                
                $deu_certo = $stmt->execute();
                
                if ($deu_certo) {
                    unset($_POST);
                    header("Location: msg_cliente_cadastrado.php");
                    die();
                }
            } else {
                echo "<p><b>Erro: $erro</b></p>";       
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de clientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
</head>
<body>
    <main class="container mt-5">
        <section class="principal">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <h1 class="mb-3">Novo cliente</h1><br>
                        <form method="POST" enctype="multipart/form-data" action="">
                            <div class="form-group">
                                <label for="cod_categoria">Categoria *</label>
                                <select name="cod_categoria" class="form-control">
                                <?php
                                    try {
                                        $sql_categorias = "SELECT cod_categoria, nome FROM categorias";
                                        $stmt_categorias = $pdo->prepare($sql_categorias);
                                        $stmt_categorias->execute();
                                        $categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($categorias as $categoria) {
                                            $selected = (isset($_POST['cod_categoria']) && $_POST['cod_categoria'] == $categoria['cod_categoria']) ? 'selected' : '';
                                            echo "<option value=\"{$categoria['cod_categoria']}\" $selected>{$categoria['nome']}</option>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "Erro: " . $e->getMessage();
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nome">Nome *</label>
                                <input value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>" name="nome" class="form-control" type="text" required>
                            </div>
                            <div class="form-group">
                                <label for="rg">RG</label>
                                <input value="<?php if(isset($_POST['rg'])) echo $_POST['rg']; ?>" name="rg" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label for="cpf">CPF *</label>
                                <input value="<?php if(isset($_POST['cpf'])) echo $_POST['cpf']; ?>" name="cpf" class="form-control" type="text" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail *</label>
                                <input value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" name="email" class="form-control" type="text" required>
                            </div>
                            <div class="form-group">
                                <label for="endereco">Endereço *</label>
                                <input value="<?php if(isset($_POST['endereco'])) echo $_POST['endereco']; ?>" name="endereco" class="form-control" type="text" required>
                            </div>
                            <div class="form-group">
                                <label for="cidade">Cidade *</label>
                                <input value="<?php if(isset($_POST['cidade'])) echo $_POST['cidade']; ?>" name="cidade" class="form-control" type="text" required>
                            </div>
                            <div class="form-group">
                                <label for="estado">Estado *</label>
                                <input value="<?php if(isset($_POST['estado'])) echo $_POST['estado']; ?>" name="estado" id="estado" class="form-control" type="text" maxlength="2" oninput="formatarEstado(this)" required>
                            </div>
                            <div class="form-group">
                                <label for="nascimento">Data de nascimento</label>
                                <input value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']; ?>" name="nascimento" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label for="telefone">Telefone</label>
                                <input value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone']; ?>" name="telefone" class="form-control" type="text">
                            </div>
                            <div class="form-group">
                                <label for="celular">Celular *</label>
                                <input value="<?php if(isset($_POST['celular'])) echo $_POST['celular']; ?>" name="celular" class="form-control" type="text" required>
                            </div>
                            <div class="form-group">
                                <label for="foto">Foto</label>
                                <input name="foto" class="form-control-file" type="file">
                            </div><br>
                            <div class="form-group">
                                <button name="salvar_cliente" class="btn btn-primary" type="submit">Salvar cliente</button>
                                <a href="index.php" class="btn btn-secondary">Voltar para a lista</a><br><br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
            $("input[name='telefone'], input[name='celular']").inputmask("(99) 99999-9999[9]");
        });

        function formatarEstado(input) {
            input.value = input.value.replace(/[^A-Za-z]/g, '').toUpperCase();
        }
    </script>
</body>
</html>
