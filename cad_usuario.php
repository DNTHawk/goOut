<?php
error_reporting(0);

session_start();

require 'conexao.php';

$dataCadastro = date("Y-m-d");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$idUsuario = (isset($_POST["idUsuario"]) && $_POST["idUsuario"] != null) ? $_POST["idUsuario"] : "";
	$nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
	$sobrenome = (isset($_POST["sobrenome"]) && $_POST["sobrenome"] != null) ? $_POST["sobrenome"] : "";
	$aniversario = (isset($_POST["aniversario"]) && $_POST["aniversario"] != null) ? $_POST["aniversario"] : "";
	$sexo = (isset($_POST["sexo"]) && $_POST["sexo"] != null) ? $_POST["sexo"] : "";
	$email = (isset($_POST["email"]) && $_POST["email"] != null) ? $_POST["email"] : "";
	$senha = (isset($_POST["senha"]) && $_POST["senha"] != null) ? $_POST["senha"] : "";
	$receberInformacao = (isset($_POST["receberInformacao"]) && $_POST["receberInformacao"] != null) ? $_POST["receberInformacao"] : "";

	$_SESSION['user_email'] = $email;
	$_SESSION['user_senha'] = $senha;
} else if (!isset($idUsuario)) {
	$idUsuario = (isset($_GET["idUsuario"]) && $_GET["idUsuario"] != null) ? $_GET["idUsuario"] : "";
}

try {
	$conexao = db_connect();
	$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conexao->exec("set names utf8");
} catch (PDOException $erro) {
	echo "Erro na conexão:".$erro->getMessage();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
	try {
		if ($idUsuario != "") {
			$stmt = $conexao->prepare("UPDATE usuario  SET nome=?, sobrenome=?, aniversario=?, sexo=?, email=?, senha=?, receberInformacao=?, dataCadastro=? WHERE idUsuario = ?");
			$stmt->bindParam(9, $idUsuario);
		} else {
			$stmt = $conexao->prepare("INSERT INTO usuario (nome, sobrenome, aniversario, sexo, email, senha, receberInformacao, dataCadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		}
		$stmt->bindParam(1, $nome);
		$stmt->bindParam(2, $sobrenome);
		$stmt->bindParam(3, $aniversario);
		$stmt->bindParam(4, $sexo);
		$stmt->bindParam(5, $email);
		$stmt->bindParam(6, $senha);
		$stmt->bindParam(7, $receberInformacao);
		$stmt->bindParam(8, $dataCadastro);


		if ($stmt->execute()) {
			if ($stmt->rowCount() > 0) {
				echo"<script language='javascript' type='text/javascript'> window.location.href='cad_preferencias.php';</script>";
				$idUsuario = null;
				$nome = null;
				$sobrenome = null;
				$aniversario = null;
				$sexo = null;
				$email = null;
				$senha = null;
				$receberInformacao = null;
				$dataCadastro = null;

			} else {
				echo "Erro ao tentar efetivar cadastro";
			}
		} else {
			throw new PDOException("Erro: Não foi possível executar a declaração sql");
		}
	} catch (PDOException $erro) {
		echo "Erro: ".$erro->getMessage();
	}
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $idUsuario != "") {
	try {
		$stmt = $conexao->prepare("SELECT * FROM usuario WHERE idUsuario = ?");
		$stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
		if ($stmt->execute()) {
			$rs = $stmt->fetch(PDO::FETCH_OBJ);
			$idUsuario = $rs->idUsuario;
			$nome = $rs->nome;
			$sobrenome = $rs->sobrenome;
			$aniversario = $rs->aniversario;
			$sexo = $rs->sexo;
			$email = $rs->email;
			$senha = $rs->senha;
			$receberInformacao = $rs->receberInformacao;
			$dataCadastro = $rs->dataCadastro;
		} else {
			throw new PDOException("Erro: Não foi possível executar a declaração sql");
		}
	} catch (PDOException $erro) {
		echo "Erro: ".$erro->getMessage();
	}
}else{
	$nome = null;
	$sobrenome = null;
	$aniversario = null;
	$sexo = null;
	$email = null;
	$senha = null;
	$receberInformacao = null;
	$dataCadastro = null;    
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $idUsuario != "") {
	try {
		$stmt = $conexao->prepare("DELETE FROM usuario WHERE idUsuario = ?");
		$stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
		if ($stmt->execute()) {
			echo"<script language='javascript' type='text/javascript'>alert('Erro ao lançar Feedback!');window.location.href='cad_preferencias.html';</script>";
			$idUsuario = null;
		} else {
			throw new PDOException("Erro: Não foi possível executar a declaração sql");
		}
	} catch (PDOException $erro) {
		echo "Erro: ".$erro->getMessage();
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>GoOut</title>
	<link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-day.css">
	<link rel="stylesheet" type="text/css" href="css/style-night.css">
	<link rel="stylesheet" type="text/css" href="lib/fontawesome/css/fontawesome-all.min.css">
	<link rel="manifest" href="js/manifest.json">
	<script src="js/efeito.js"></script>
	<style type="text/css">
	body.day{
		background-color: #FFF;
	}
	body.night{
		background-color: #242F38;
	}
	body div a img{
		display: none!important;
	}
	.night button {
		background-color: #00E8EF;
		border: none;
		height: 100%;
		width: 100%;
		border-radius: 50%;
	}
	#cad-user .btn-avancar img {
		width: 100%;
		margin-top: 10%;
		margin-left: 0%;
	}
</style>
</head>
<body class="night">
	<div class="carregando" id="carregando"></div>
	<div class="corpo" id="corpo">
		<form action="?act=save" method="POST" name="form1">
			<div id="cad-user" class="container">
				<div class="row" style="margin-top: 3%">
					<div class="col-xs-12">
						<div class="titulo"><p style="margin-top: 2%;">Preencha suas informações <br> antes de continuar.</p></div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-10 col-xs-offset-1">
						<label for="nome">Nome</label><br>
						<input type="text" name="nome" required>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1">
						<label for="sobrenome">Sobrenome</label><br>
						<input type="text" name="sobrenome" required>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1">
						<label for="aniversario">Aniversário</label><br>
						<input type="date" name="aniversario" required>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1">
						<label for="sexo">Gênero</label>
					</div>
				</div>
				<div class="row" style="margin-top: 2%">
					<div class="col-xs-12">
						<div id="masc" class="col-xs-6">
							<input type="radio" id="control_01" name="sexo" value="Masculino" required>
							<label style="width: 95%; margin-left: 15px" class="radio" for="control_01">
								<p>Masculino</p>
							</label>
						</div>
						<div id="femi" class="col-xs-6">
							<input type="radio" id="control_02" name="sexo" value="Feminino">
							<label style="width: 95%; margin-left: -7px" class="radio" for="control_02">
								<p>Feminino</p>
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1">
						<label for="email">Endereço de e-mail</label><br>
						<input type="email" class="hidden" name="email">
						<input type="email" name="email" required>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1">
						<label for="senha">Senha</label><br>
						<input type="password" class="hidden" name="senha">
						<input type="password" name="senha" required>
					</div>
				</div>
				<div class="row" style="margin-top: 5%">
					<div class="col-xs-1"></div>
					<div class="col-xs-7">
						<p style="text-align: left;" class="obs">Gostaria de receber informações promocionais, incluindo descontos e pesquisar do GoOut por e-email.</p>
					</div>
					<div class="col-xs-3">
						<div style="margin-top: -10px" class="switch__container">
							<input id="switch-shadow" name="receberInformacao" class="switch switch--shadow" type="checkbox">
							<label for="switch-shadow"></label>
						</div>
					</div>
				</div>
				<div class="row" >
					<div class="col-xs-8">

					</div>
					<div class="col-xs-4">
						<div class="btn-avancar">
							<button type="submit" name="Enviar">
								<img class="icon-night" src="right-arrow-night.png">
							</button>
						</div>	
					</div>
				</div>
				<br>
			</div>
			
		</form>
	</div>

	<script src="lib/jquery/jquery.min.js" type="text/javascript"></script>
	<script src="lib/bootstrap/js/bootstrap.min.js"></script>
	<script src="js/efeito.js"></script>
	<script src="js/efeitos.js"></script>

	<script type="text/javascript">
		$("#btn-avancar").on("click", function(){

			$("#cad-pref").removeClass("hidden");
		});
		$("#btn-avancar").on("click", function(){

			$("#cad-pref").toggleClass("visible");

		});
		$("#btn-avancar").on("click", function(){

			$("#cad-user").toggleClass("hidden");

		});

		$("#teste").on("click", function(){

			var valor = $('#estiloMusical').val();

			document.getElementById('teste2').value = valor;

			$("#add_estiloMusical").removeClass("hidden");

		});
	</script>
</body>
</html>