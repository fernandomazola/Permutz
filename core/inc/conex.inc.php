<?php

require_once("config.inc.php");

/*
 * conexão com BD
 */
$conexao = mysqli_connect(BD_HOST, BD_USUARIO, BD_SENHA, BD_NOME);
if (mysqli_connect_errno()) :
    printf("Conexão falhou: %s\n", mysqli_connect_error());
    exit();
else :
	mysqli_set_charset($conexao, 'utf8');
endif;

