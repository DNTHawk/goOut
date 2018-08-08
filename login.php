<?php

// inclui o arquivo de inicialização
require 'conexao.php';

// resgata variáveis do formulário
$email = isset($_POST['email']) ? $_POST['email'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

if (empty($email) || empty($senha))
{
	echo"<script language='javascript' type='text/javascript'>alert('O campo login deve ser preenchido');window.location.href='login.html';</script>";
	exit;
}

$PDO = db_connect();

$sql = "SELECT * FROM usuario WHERE usuario.email = :email AND usuario.senha = :senha" ;
$stmt = $PDO->prepare($sql);

$stmt->bindParam(':email', $email);
$stmt->bindParam(':senha', $senha);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($users) <= 0)
{
	echo"<script language='javascript' type='text/javascript'>alert('Email ou senha incorreta!');window.location.href='login.html';</script>";
	exit;
}

// pega o primeiro usuário
$user = $users[0];

session_start();
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = $user['idUsuario'];

header('Location: home.php');

?>