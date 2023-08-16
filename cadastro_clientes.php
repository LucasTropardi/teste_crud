<?php

function limpar_texto($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

if (count($_POST) > 0) {

    include('conexao.php');
    $erro = false;
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['nascimento'];

    if (empty($nome)) {
        $erro = "Preencha o nome.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Preencha o e-mail corretamente.";
    }
    if (!empty($nascimento)) {
        $tmp = explode('/', $nascimento);
        if (count($tmp) == 3) {
            $nascimento = implode('-', array_reverse($tmp)); 
        } else {
            $erro = "A data de nascimento deve seguir o padrão dia/mês/ano.";
        }        
    }
    if(!empty($telefone)) {
        $telefone = limpar_texto($telefone);
        if(strlen($telefone) != 11) {
           $erro = "O telefone deve ser preenchido no padrão (21) 98888-7777.";     
        }
    }
    if ($erro) {
        echo "<p><b>Erro: $erro</b></p>";       
    } else {
        $sql_code = "INSERT INTO clientes (nome, email, telefone, nascimento, data)
        VALUES ('$nome', '$email', '$telefone', '$nascimento', NOW())";   
        $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);
        if ($deu_certo) {
            echo "<p><b>Cliente cadastrado com sucesso!</b></p>";
            unset($_POST);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Cadastro de clientes</title>

    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style-home.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
<main>
    <section class="principal">
        <a href="/clientes.php">Voltar para a lista</a>
        <form method="POST" action="">
            <p>
                <label class="lblPrinc">Nome</label>
                <input value="<?php if(isset($_POST['nome'])) echo $_POST['nome']; ?>" name="nome" class="input-padrao" type="text">
            </p>
            <br>
            <p>
                <label class="lblPrinc">E-mail</label>
                <input value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" name="email" class="input-padrao" type="text">
            </p>
            <br>
            <p>
                <label class="lblPrinc">Telefone</label>
                <input value="<?php if(isset($_POST['telefone'])) echo $_POST['telefone']; ?>" name="telefone" class="input-padrao" type="text">
            </p>
            <br>
            <p>
                <label class="lblPrinc">Data de nascimento</label>
                <input value="<?php if(isset($_POST['nascimento'])) echo $_POST['nascimento']; ?>" name="nascimento" class="input-padrao" type="text">
            </p>
            <br><br>
            <p>
                <button name="salvar_cliente" class="enviar" type="submit">Salvar cliente</button>
            </p>
        </form>
    </section>
</main>
</body>
</html>
