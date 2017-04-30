<?php

/*
 * inicializando a conexão com o BD
 * instanciando um novo objeto da classe mysqli
 * $dbc = new mysqli("host", "user", "pw", "db");
 */
require_once("../inc/conex.inc.php");
require_once("../inc/funcoes.inc.php");

/* declarando a query da consulta (que deve retornar a lista de todos os locais) */
$query = "SELECT id_raj, id_circunsc, id_local, nm_local FROM t_pmt_circunscricoes NATURAL JOIN t_pmt_locais WHERE t_pmt_locais.st_local=1 ORDER BY nm_local ASC ";

/* criando o array que vai armazenar os dados retornados pela consulta */
$arrLocais = array();

/* executando a query no BD */
if ($result = mysqli_query($conexao, $query)) :
	while ($linha = mysqli_fetch_array($result)) :
		$id_circ = substr($linha["id_circunsc"], 1);
		$strLocal = "{$linha["id_local"]} - {$linha["nm_local"]}"; //" - {$id_circ}ª CJ {$linha["id_raj"]}ª RAJ";
		array_push($arrLocais, $strLocal);
	endwhile;
endif;

/* finalizando a conexão com o BD */
mysqli_close($conexao);

$jsnLocais = json_encode($arrLocais);

echo $jsnLocais;
//arrayprint($jsnLocais);