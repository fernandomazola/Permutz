<?php

session_start();

if ($_SESSION['admin']) :

	require_once('admin-cabecalho.php');

?>

	<h1>PAINEL DE ADMINISTRAÇÃO</h1>
	<ul>
		<li><a href="gestao-locais-lista.php">Gestão de Locais</a></li>
		<li><a href="gestao-usuarios-lista.php">Gestão de Usuários</a></li>
	</ul>

<?php 

	require_once('admin-rodape.php');

else :

	header('Location:index.php');

endif;