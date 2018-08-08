<?php
error_reporting(0);

session_start();

require 'conexao.php'; 

$email = $_SESSION['user_email'];
$senha = $_SESSION['user_senha'];

$PDO = db_connect();

$sql = "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha'";
$stmt = $PDO->prepare($sql);

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user = $users[0];

$idUsuario = $user['idUsuario'];

$_SESSION['user_id'] = $idUsuario;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$idPreferencia = (isset($_POST["idPreferencia"]) && $_POST["idPreferencia"] != null) ? $_POST["idPreferencia"] : "";
	$estiloMusical = (isset($_POST["estiloMusical"]) && $_POST["estiloMusical"] != null) ? $_POST["estiloMusical"] : "";
	$roleFavorito = (isset($_POST["roleFavorito"]) && $_POST["roleFavorito"] != null) ? $_POST["roleFavorito"] : "";
	$distancia = (isset($_POST["range"]) && $_POST["range"] != null) ? $_POST["range"] : "";
	
	$contar = count($estiloMusical);

	$contar2 = count($roleFavorito);

	if ($contar == "1") {
		$estiloMusical1 = $estiloMusical[0];
		$estiloMusical2 = "null";
		$estiloMusical3 = "null";
	} elseif ($contar == "2") {
		$estiloMusical1 = $estiloMusical[0];
		$estiloMusical2 = $estiloMusical[1];
		$estiloMusical3 = "null";
	}else{
		$estiloMusical1 = $estiloMusical[0];
		$estiloMusical2 = $estiloMusical[1];
		$estiloMusical3 = $estiloMusical[2];
	}

	if ($contar2 == "1") {
		$roleFavorito1 = $roleFavorito[0];
		$roleFavorito2 = "null";
		$roleFavorito3 = "null";
	} elseif ($contar2 == "2") {
		$roleFavorito1 = $roleFavorito[0];
		$roleFavorito2 = $roleFavorito[1];
		$roleFavorito3 = "null";
	}else{
		$roleFavorito1 = $roleFavorito[0];
		$roleFavorito2 = $roleFavorito[1];
		$roleFavorito3 = $roleFavorito[2];
	}

	
} else if (!isset($idPreferencia)) {
	$idPreferencia = (isset($_GET["idPreferencia"]) && $_GET["idPreferencia"] != null) ? $_GET["idPreferencia"] : "";
}

try {
	$conexao = db_connect();
	$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conexao->exec("set names utf8");
} catch (PDOException $erro) {
	echo "Erro na conexão:".$erro->getMessage();
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $estiloMusical1 != "") {
	try {
		if ($idPreferencia != "") {
			$stmt = $conexao->prepare("UPDATE preferencia  SET idUsuario=?, estiloMusical1=?, estiloMusical2=?, estiloMusical3=?, roleFavorito1=?, roleFavorito2=?, roleFavorito3=?, distancia=? WHERE idPreferencia = ?");
			$stmt->bindParam(9, $idPreferencia);
		} else {
			$stmt = $conexao->prepare("INSERT INTO preferencia (idUsuario, estiloMusical1, estiloMusical2, estiloMusical3, roleFavorito1, roleFavorito2, roleFavorito3, distancia) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		}
		$stmt->bindParam(1, $idUsuario);
		$stmt->bindParam(2, $estiloMusical1);
		$stmt->bindParam(3, $estiloMusical2);
		$stmt->bindParam(4, $estiloMusical3);
		$stmt->bindParam(5, $roleFavorito1);
		$stmt->bindParam(6, $roleFavorito2);
		$stmt->bindParam(7, $roleFavorito3);
		$stmt->bindParam(8, $distancia);


		if ($stmt->execute()) {
			if ($stmt->rowCount() > 0) {

				$_SESSION['logged_in'] = true;

				echo"<script language='javascript' type='text/javascript'> window.location.href='home.php';</script>";
				$idPreferencia = null;
				$idUsuario = null;
				$estiloMusical1 = null;
				$estiloMusical2 = null;
				$estiloMusical3 = null;
				$roleFavorito1 = null;
				$roleFavorito2 = null;
				$roleFavorito3 = null;
				$distancia = null;

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

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $idPreferencia != "") {
	try {
		$stmt = $conexao->prepare("SELECT * FROM preferencia WHERE idPreferencia = ?");
		$stmt->bindParam(1, $idPreferencia, PDO::PARAM_INT);
		if ($stmt->execute()) {
			$rs = $stmt->fetch(PDO::FETCH_OBJ);
			$idPreferencia = $rs->idPreferencia;
			$idUsuario = $rs->idUsuario;
			$estiloMusical1 = $rs->estiloMusical1;
			$estiloMusical2 = $rs->estiloMusical2;
			$estiloMusical3 = $rs->estiloMusical3;
			$roleFavorito1 = $rs->roleFavorito1;
			$roleFavorito2 = $rs->roleFavorito2;
			$roleFavorito3 = $rs->roleFavorito3;
			$distancia = $rs->distancia;
		} else {
			throw new PDOException("Erro: Não foi possível executar a declaração sql");
		}
	} catch (PDOException $erro) {
		echo "Erro: ".$erro->getMessage();
	}
}else{
	$idUsuario = null;
	$estiloMusical1 = null;
	$estiloMusical2 = null;
	$estiloMusical3 = null;
	$roleFavorito1 = null;
	$roleFavorito2 = null;
	$roleFavorito3 = null;
	$distancia = null;    
}

if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $idPreferencia != "") {
	try {
		$stmt = $conexao->prepare("DELETE FROM preferencia WHERE idPreferencia = ?");
		$stmt->bindParam(1, $idPreferencia, PDO::PARAM_INT);
		if ($stmt->execute()) {
			echo"<script language='javascript' type='text/javascript'>alert('Erro ao lançar Feedback!');window.location.href='cad_preferencias.html';</script>";
			$idPreferencia = null;
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/style-day.css">
	<link rel="stylesheet" type="text/css" href="css/style-night.css">
	<link rel="stylesheet" type="text/css" href="lib/fontawesome/css/fontawesome-all.min.css">
	<link rel="manifest" href="js/manifest.json">

	<style type="text/css" media="screen">
	body.day{
		background-color: #00E8EF;
	}
	body.night{
		background-color: #242F38;
	}
	.bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
		border: 1px solid #00E8EF;
		border-radius: 20px;
		width: 100%;
		margin-left: 50px;
		margin-top: 10px;
		background-color: transparent;
	}
	.btn-default.active.focus, .btn-default.active:focus, .btn-default.active:hover, .btn-default:active.focus, .btn-default:active:focus, .btn-default:active:hover, .open>.dropdown-toggle.btn-default.focus, .open>.dropdown-toggle.btn-default:focus, .open>.dropdown-toggle.btn-default:hover {
		border: 1px solid #00E8EF;
		border-radius: 20px;
		width: 100%;
		margin-left: 50px;
		margin-top: 10px;
		background-color: transparent;
	}
	.btn-group.open .dropdown-toggle {
		border: 1px solid #00E8EF;
		border-radius: 20px;
		width: 100%;
		margin-left: 50px;
		margin-top: 10px;
		background-color: transparent;
	}
	.bootstrap-select.btn-group .dropdown-toggle .filter-option {
		display: inline-block;
		overflow: hidden;
		width: 100%;
		text-align: left;
		color: #00E8EF;
	}
	.bootstrap-select.btn-group .dropdown-menu {
		min-width: 100%;
		margin-left: 50px;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		background-color: #242F38;
		border: 1px solid #00E8EF;
		border-radius: 20px;
	}
	.bootstrap-select.btn-group.show-tick .dropdown-menu li a span.text {
		margin-right: 34px;
		color: #00E8EF;
	}
	.bootstrap-select.btn-group .dropdown-menu li {
		position: relative;
		border-bottom: 1px solid #00E8EF;
	}
	.bootstrap-select.btn-group .dropdown-menu li:last-child{
		position: relative;
		border-bottom: none;
	}
	.glyphicon-ok:before {
		content: "\e013";
		color: #00E8EF;
	}
	.dropdown-menu>li>a:focus, .dropdown-menu>li>a:hover {
		color: #00E8EF;
		text-decoration: none;
		background-color: #242F38;
	}
	.btn-group>.btn:first-child {
		border: 1px solid #00E8EF;
		border-radius: 20px;
		width: 100%;
		margin-left: 50px;
		margin-top: 10px;
		background-color: transparent;
	}
	.bootstrap-select.btn-group .dropdown-toggle .caret {
		position: absolute;
		top: 50%;
		right: 12px;
		margin-top: -2px;
		vertical-align: middle;
		color: #00E8EF;
	}
	body div a img{
		display: none!important;
	}

</style>
</head>
<body class="visible-xs night">
	<div class="carregando" id="carregando"></div>
	<div class="corpo" id="corpo">
		<div id="cad-pref" class="container">
			<form action="?act=save" method="POST" name="form1">
				<div class="row" style="margin-top: 3%">
					<div class="col-xs-12">
						<div class="titulo"><p>Preferências</p></div>
					</div>
				</div>
				<div class="row" style="margin-top: 3%">
					<div class="col-xs-10 col-xs-offset-1">
						<p style="text-align: left;" class="obs">Ajuste suas preferências para ajudarmos você a encontrar as melhores festas de acordo com seu gosto.</p>
					</div>
				</div>
				<div class="row" style="margin-top: 6%; margin-left: -15px">
					<div class="col-xs-10 col-xs-offset-1">
						<h4>Estilio Musical</h4>
					</div>
				</div>
				<div class="row" style="margin-top: 1%">
					<select name="estiloMusical[]" class="selectpicker" multiple data-max-options="3" required>
						<option value="Axé">Axé</option>
						<option value="Country">Country</option>
						<option value="Elertrônica">Elertrônica</option>
						<option value="Forró">Forró</option>
						<option value="Funk">Funk</option>
						<option value="Gospel">Gospel</option>
						<option value="Hip Hop">Hip Hop</option>
						<option value="Jazz">Jazz</option>
						<option value="MPB">MPB</option>
						<option value="Pagode">Pagode</option>
						<option value="Pop">Pop</option>
						<option value="Rap">Rap</option>
						<option value="Reggae">Reggae</option>
						<option value="Rock">Rock</option>
						<option value="Rômantico">Rômantico</option>
						<option value="Samba">Samba</option>
						<option value="Sertanejo">Sertanejo</option>
						<option value="Tecnopop">Tecnopop</option>
					</select>
				</div>
				<div class="row" style="margin-top: 6%; margin-left: -15px">
					<div class="col-xs-10 col-xs-offset-1">
						<h4>Role Favorito</h4>
					</div>
				</div>
				<div class="row" style="margin-top: 1%">
					<select name="roleFavorito[]" class="selectpicker" multiple data-max-options="3" required>
						<option value="Balada">Balada</option>
						<option value="Bar">Bar</option>
						<option value="Festival">Festival</option>
						<option value="Parque Exposição">Parque Exposição</option>
						<option value="Rave">Rave</option>
						<option value="Show">Show</option>
					</select>
				</div>
				<div class="row" style="margin-top: 10%">
					<div class="col-xs-1"></div>
					<div class="col-xs-5">
						<h4>Localização</h4>
					</div>
					<div class="col-xs-5">
						<p class="local">Maringá <br> Paraná</p>
					</div>
				</div>
				<div class="row" style="margin-top: 15px">
					<div class="col-xs-10 col-xs-offset-1">
						<input id="range" style="width: 92%" name="range" type="range" min="0" max="100" value="0" class="range blue"/>
					</div>
				</div>
				<div class="row" style="margin-top: 30px">
					<div class="col-xs-12">
						<p id="resultado1" class="raio">Raio 0Km</p>
					</div>
				</div>
				<div class="row" style="margin-top: 15px; margin-bottom: 5px;">
					<div class="col-xs-8"> 
						<p class="obs ajust-pref">Você pode ajustar suas preferências quando quiser na aba Perfil.</p>
					</div>
					<div class="col-xs-4">
						<div class="btn-avancar">
							<button style="background-color: transparent!important;" type="submit" name="Enviar">
								<img class="icon-night" src="icons/right-arrow-night.png">
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>


	<script src="lib/jquery/jquery.min.js" type="text/javascript"></script>
	<script src="js/efeito.js"></script>
	<script src="js/efeitos.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

	<!-- (Optional) Latest compiled and minified JavaScript translation files -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/i18n/defaults-*.min.js"></script>

	<!-- Atente-se para a ordem: primeiro jquery, depois locastyle, depois o JS do Bootstrap. -->
	<script async="" src="//www.google-analytics.com/analytics.js"></script>
	<script type="text/javascript" src="lib/locaweb/javascripts/locastyle.js"></script>
	<script src="lib/bootstrap/js/bootstrap.min.js"></script>

	<script type="text/javascript">
		var p = document.getElementById("range"),
		res1 = document.getElementById("resultado1");

		p.addEventListener("input", function () {
			res1.innerHTML = "Raio " + p.value + "Km";
		}, false);

	</script>


</body>
</html>