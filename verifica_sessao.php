<?php

require_once 'conexao.php';

if (!isLoggedIn())
{
	header('Location: index.html');
}

?>