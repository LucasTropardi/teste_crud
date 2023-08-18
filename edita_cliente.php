<?php
include('conexao.php');
$cod_cliente = intval($_GET['cod_cliente']);
$erro = false;

function converterDataParaFormatoBanco($data) {
    $partes = explode('/', $data);
    return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
}
function converterDataParaFormatoExibicao($data) {
    $partes = explode('-', $data);
    return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
}    

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
    $nascimentoFormatado = converterDataParaFormatoBanco($nascimento);

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
        }
    }

    if ($erro) {
        echo "<p><b>Erro: $erro</b></p>";       
    } else {
        try {
            $sql_code = "UPDATE clientes
            SET cod_categoria = :cod_categoria,
            nome = :nome,
            rg = :rg,
            cpf = :cpf,
            email = :email,
            endereco = :endereco,
            cidade = :cidade,
            estado = :estado,
            nascimento = :nascimento,
            foto = :foto,
            telefone = :telefone,
            celular = :celular
            WHERE cod_cliente = :cod_cliente";   
            $stmt = $pdo->prepare($sql_code);
            $stmt->bindParam(':cod_cliente', $cod_cliente);
            $stmt->bindParam(':cod_categoria', $cod_categoria);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':rg', $rg);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':endereco', $endereco);
            $stmt->bindParam(':cidade', $cidade);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':nascimento', $nascimentoFormatado);
            $stmt->bindParam(':foto', $path);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':celular', $celular);
         
            $deu_certo = $stmt->execute();
            
            if ($deu_certo) {
                echo "<p><b>Cliente atualizado com sucesso.</b></p>";
                unset($_POST);
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}

try {
    $sql_cliente = "SELECT * FROM clientes WHERE cod_cliente = :cod_cliente";
    $stmt_cliente = $pdo->prepare($sql_cliente);
    $stmt_cliente->bindParam(':cod_cliente', $cod_cliente);
    $stmt_cliente->execute();
    $cliente = $stmt_cliente->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        echo "Cliente não encontrado.";
        exit; 
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
    exit; 
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edição de clientes</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <main class="container mt-5">
        <a href="index.php" class="btn btn-primary">Voltar para a lista</a>
        <form method="POST" enctype="multipart/form-data" action="" class="mt-4">
            <div class="form-group">
                <label for="cod_categoria">Categoria</label>
                <select id="cod_categoria" name="cod_categoria" class="form-control">
                    <option value="">Selecione uma categoria</option>
                    <?php
                    // Buscar categorias do banco de dados
                    $sql_categorias = "SELECT * FROM categorias";
                    $stmt_categorias = $pdo->prepare($sql_categorias);
                    $stmt_categorias->execute();

                    // Percorrer as categorias e preencher a caixa de seleção
                    while ($categoria = $stmt_categorias->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($categoria['cod_categoria'] == $cliente['cod_categoria']) ? 'selected' : '';
                        echo "<option value='" . $categoria['cod_categoria'] . "' $selected>" . $categoria['nome'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nome">*Nome</label>
                <input value="<?php echo $cliente['nome']; ?>" name="nome" id="nome" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="rg">RG</label>
                <input value="<?php echo $cliente['rg']; ?>" name="rg" id="rg" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="cpf">*CPF</label>
                <input value="<?php echo $cliente['cpf']; ?>" name="cpf" id="cpf" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="email">*E-mail</label>
                <input value="<?php echo $cliente['email']; ?>" name="email" id="email" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="endereco">*Endereço</label>
                <input value="<?php echo $cliente['endereco']; ?>" name="endereco" id="endereco" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="cidade">*Cidade</label>
                <input value="<?php echo $cliente['cidade']; ?>" name="cidade" id="cidade" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="estado">*Estado</label>
                <input value="<?php echo $cliente['estado']; ?>" name="estado" id="estado" class="form-control" type="text" maxlength="2">
            </div>
            <div class="form-group">
                <label for="nascimento">Data de nascimento</label>
                <input value="<?php echo ($cliente['nascimento'] !== '0000-00-00') ? converterDataParaFormatoExibicao($cliente['nascimento']) : ''; ?>" name="nascimento" id="nascimento" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input value="<?php echo $cliente['telefone']; ?>" name="telefone" id="telefone" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="celular">*Celular</label>
                <input value="<?php echo $cliente['celular']; ?>" name="celular" id="celular" class="form-control" type="text">
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input name="foto" id="foto" class="form-control-file" type="file">
            </div>
            <button name="atualizar_cliente" class="btn btn-primary" type="submit">Atualizar cliente</button>
        </form>
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

        // Aplicar máscara na data de nascimento após a página carregar
        var dataNascimentoInput = document.getElementById('dataNascimento');
        if (dataNascimentoInput) {
            var dataNascimento = dataNascimentoInput.value;
            if (dataNascimento && dataNascimento !== '00/00/0000') {
                dataNascimentoInput.value = converterDataParaFormatoExibicao(dataNascimento);
            }
        }
    });
    function converterDataParaFormatoExibicao(data) {
        var partes = data.split('-');
        return partes[2] + '/' + partes[1] + '/' + partes[0];
    }

    function converterDataParaFormatoBanco(data) {
        var partes = data.split('/');
        return partes[2] + '-' + partes[1] + '-' + partes[0];
    }

</script>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
