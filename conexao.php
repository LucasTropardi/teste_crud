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

function formatar_telefone($telefone) {
    $ddd = substr($telefone, 0, 2);
    $parte1 = substr($telefone, 2, 5);
    $parte2 = substr($telefone, 7);
    $telefone = "($ddd) $parte1-$parte2";
    return $telefone;
}