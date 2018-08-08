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
  <style type="text/css" media="screen">
  body.day{
    background-color: #FFF;
  }
  body div a img{
    display: none!important;
  }
</style>
</head>
<body>
  <div class="carregando" id="carregando"></div>
  <div class="corpo" id="corpo">
    <?php 
    session_start();

    require 'conexao.php';

    $idUsuario = $_SESSION['user_id'];

    $localFoto = '';

    try {
      $conexao = db_connect();
      $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conexao->exec("set names utf8");
    } catch (PDOException $erro) {
      echo "Erro na conexão:".$erro->getMessage();
    }
  // Recupera os dados dos campos
    $foto = $_FILES["foto"];

  // Se a foto estiver sido selecionada
    if (!empty($foto["name"])) {

    // Largura máxima em pixels
      $largura = 3150;
    // Altura máxima em pixels
      $altura = 3180;
    // Tamanho máximo do arquivo em bytes
      $tamanho = 1000000000000;

      $error = array();

      // Verifica se o arquivo é uma imagem
      if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])){
        $error[1] = "Isso não é uma imagem.";
      } 

    // Pega as dimensões da imagem
      $dimensoes = getimagesize($foto["tmp_name"]);

    // Verifica se a largura da imagem é maior que a largura permitida
      if($dimensoes[0] > $largura) {
        $error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
      }

    // Verifica se a altura da imagem é maior que a altura permitida
      if($dimensoes[1] > $altura) {
        $error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
      }

    // Verifica se o tamanho da imagem é maior que o tamanho permitido
      if($foto["size"] > $tamanho) {
        $error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
      }

    // Se não houver nenhum erro
      try {
        if (count($error) == 0) {

      // Pega extensão da imagem
          preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);

          // Gera um nome único para a imagem
          $nome_imagem = md5(uniqid(time())) . "." . $ext[1];

          // Caminho de onde ficará a imagem
          $caminho_imagem = "fotos/".$nome_imagem;

      // Faz o upload da imagem para seu respectivo caminho
          move_uploaded_file($foto["tmp_name"], $caminho_imagem);

      // Insere os dados no banco

          try {
            $stmt = $conexao->prepare("SELECT * FROM foto WHERE idUsuario = '$idUsuario'");
            $stmt->bindParam(1, $idUsuario, PDO::PARAM_INT);
            if ($stmt->execute()) {
              $rs = $stmt->fetch(PDO::FETCH_OBJ);
              if($rs){
                $idFoto = $rs->idFoto;
                $stmt = $conexao->prepare("UPDATE foto SET idUsuario = '$idUsuario', caminhoFoto = '$nome_imagem' WHERE idFoto = '$idFoto'");
              }else{
                $stmt = $conexao->prepare("INSERT INTO foto VALUES ('', '".$idUsuario."', '".$nome_imagem."')");
              }
            } else {
              throw new PDOException("Erro: Não foi possível executar a declaração sql");
            }
          } catch (PDOException $erro) {
            echo "Erro: ".$erro->getMessage();
          }


          if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
              echo"<script language='javascript' type='text/javascript'> window.location.href='home.php';</script>";

            } else {
              echo "Erro ao tentar efetivar cadastro";
            }
          } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
          }

      // Se os dados forem inseridos com sucesso

        }
      } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
      }

    // Se houver mensagens de erro, exibe-as
      if (count($error) != 0) {
        foreach ($error as $erro) {
          echo $erro . "<br />";
        }
      }
    }
    ?>
  </div>

</body>
</html>