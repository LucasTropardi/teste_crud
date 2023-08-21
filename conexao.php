<?php

$host = "localhost";
$db = "cadastro";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexão com a base de dados não estabelecida, verifique.");
}
//tive problemas com esta função, remover do código ou trabalhar nela.
function limpar_texto($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

function formatar_data($data) {
    return implode('/', array_reverse(explode('-', $data)));
}

function converter_formato_data($data, $formatoOrigem, $formatoDestino) {
    $dataObj = DateTime::createFromFormat($formatoOrigem, $data);
    return $dataObj ? $dataObj->format($formatoDestino) : '';
}


function formatar_telefone($telefone) {
    $ddd = substr($telefone, 0, 2);
    $parte1 = substr($telefone, 2, 5);
    $parte2 = substr($telefone, 7);
    $telefone = "($ddd) $parte1-$parte2";
    return $telefone;
}

function validar_cpf($cpf) {
    $cpf = Limpar_texto($cpf);
    if (strlen($cpf) !== 11) {
        return false;
    }
    $cpfs_invalidos = [
        '000.000.000-00',
        '111.111.111-11',
        '222.222.222-22',
        '333.333.333-33',
        '444.444.444-44',
        '555.555.555-55',
        '666.666.666-66',
        '777.777.777-77',
        '888.888.888-88',
        '999.999.999-99'
    ];
    if (in_array($cpf, $cpfs_invalidos)) {
        return false;
    }
    //cálculo de verificação dos dígitos do cpf
    for ($i = 0; $i < 2; $i++) {
        $soma = 0;
        //soma ponderada dos dígitos do CPF
        for ($j = 0; $j < 9 + $i; $j++) {
            $soma += $cpf[$j] * (10 + $i - $j);
        }
        //resto da divisão da soma por 11
        $resto = $soma % 11;
        //calcular dígito verificador
        $digitoVerificador = ($resto < 2) ? 0 : (11 - $resto);
        //validar dígito calculado com o digitado pelo usuário    
        if ($cpf[9 + $i] != $digitoVerificador) {
            return false;
        }
    }

    return true;
}