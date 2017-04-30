<?php

require_once("core/inc/conex.inc.php");
require_once("core/inc/funcoes.inc.php");


/*
 html do cabeçalho
 */
require_once('core/tpl/cabecalho.tpl.php');

$combo2 = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(id_comb_2) FROM t_pmt_comb_2"));
$intCombos2 = $combo2[0];

$combo3 = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(id_comb_3) FROM t_pmt_comb_3"));
$intCombos3 = $combo3[0] / 3;

$combo4 = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(id_comb_4) FROM t_pmt_comb_4"));
$intCombos4 = $combo4[0] / 4;

$combo5 = mysqli_fetch_row(mysqli_query($conexao, "SELECT COUNT(id_comb_5) FROM t_pmt_comb_5"));
$intCombos5 = $combo5[0] / 5;

$intTotal = $intCombos2 + $intCombos3 + $intCombos4 + $intCombos5;
$intTotal = intval($intTotal);

?>
	<h1 class="text-center bg bg-default">Lista de Combinações</h1>

	<h2 class="text-center">O Permutz já encontrou <?= $intTotal ?> combinações de permutas!</h2>

	<h3 class="text-center">Dê um CTRL + F, pesquise seu e-mail TJSP e veja se a sua está aí embaixo:</h3>

	<?php 

	function extraiDadosUsuario($conexao, $value) {
		$query = "SELECT u.nm_usuario, u.ds_email_tjsp, la.nm_local AS nm_local_atual, ld.nm_local AS nm_local_desejado FROM t_pmt_usuarios AS u INNER JOIN t_pmt_opcoes_permuta AS o ON (u.id_usuario=o.id_usuario) INNER JOIN t_pmt_locais AS la ON (u.id_local_atual=la.id_local) INNER JOIN t_pmt_locais AS ld ON (o.id_local_desejado = ld.id_local) WHERE o.id_opcao = {$value}";
		$result = mysqli_query($conexao, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<li>";
			echo "{$row['nm_usuario']} (<a href='mailto:{$row['ds_email_tjsp']}'>{$row['ds_email_tjsp']}</a>)<br>";
			echo "De: {$row['nm_local_atual']}<br>";
			echo "Para: {$row['nm_local_desejado']}<br><br>";
			echo "</li>"; 
		}
	}

	$contador = 1;

	
	$queryComb2 = "SELECT id_opcao_1, id_opcao_2 FROM t_pmt_comb_2";
	$result2 = mysqli_query($conexao, $queryComb2);
	while ($row2 = mysqli_fetch_assoc($result2)) {
		echo "<div class='panel panel-success'>";
			echo "<div class='panel-heading'>Combinação #{$contador}</div>";
			echo "<div class='panel-body'>";
				echo "<ul>";
					foreach ($row2 as $key => $value) {
						extraiDadosUsuario($conexao, $value);
					}
				echo "</ul>";
			echo "</div>";
		echo "</div>";
		echo '<hr>';
		$contador++;
	}

	
	$queryComb3 = "SELECT id_opcao_1, id_opcao_2, id_opcao_3 FROM t_pmt_comb_3 ORDER BY vl_check ASC";
	$result3 = mysqli_query($conexao, $queryComb3);
	$i=1;
	while ($row3 = mysqli_fetch_assoc($result3)) {
		if ($i % 3 == 0) {
			echo "<div class='panel panel-success'>";
				echo "<div class='panel-heading'>Combinação #{$contador}</div>";
				echo "<div class='panel-body'>";
					echo "<ul>";
						foreach ($row3 as $key => $value) {
							extraiDadosUsuario($conexao, $value);
						}
					echo "</ul>";
				echo "</div>";
			echo "</div>";
			echo '<hr>';
			$contador++;
		}
		$i++;
	}

	
	$queryComb4 = "SELECT id_opcao_1, id_opcao_2, id_opcao_3, id_opcao_4 FROM t_pmt_comb_4 ORDER BY vl_check ASC";
	$result4 = mysqli_query($conexao, $queryComb4);
	$i=1;
	while ($row4 = mysqli_fetch_assoc($result4)) {
		if ($i%4 == 0) {
			echo "<div class='panel panel-success'>";
				echo "<div class='panel-heading'>Combinação #{$contador}</div>";
				echo "<div class='panel-body'>";
					echo "<ul>";
						foreach ($row4 as $key => $value) {
							extraiDadosUsuario($conexao, $value);
						}
					echo "</ul>";
				echo "</div>";
			echo "</div>";
			echo '<hr>';
			$contador++;
		}
		$i++;
	}


	$queryComb5 = "SELECT id_opcao_1, id_opcao_2, id_opcao_3, id_opcao_4, id_opcao_5 FROM t_pmt_comb_5 ORDER BY vl_check ASC";
	$result5 = mysqli_query($conexao, $queryComb5);
	$i=1;
	while ($row5 = mysqli_fetch_assoc($result5)) {
		if ($i%5 == 0) {
			echo "<div class='panel panel-success'>";
				echo "<div class='panel-heading'>Combinação #{$contador}</div>";
				echo "<div class='panel-body'>";
					echo "<ul>";
						foreach ($row5 as $key => $value) {
							extraiDadosUsuario($conexao, $value);
						}
					echo "</ul>";
				echo "</div>";
			echo "</div>";
			echo '<hr>';
			$contador++;
		}
		$i++;
	}


	require_once('core/tpl/rodape.tpl.php');

	?>
