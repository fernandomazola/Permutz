<?php

session_start();
require_once('../core/inc/conex.inc.php');

if (isset($_POST['local-gravar']) && $_POST['local-label-atual']!='') :

		$id_local = $_POST['local-label-atual'];
		$nm_local = trim($_POST['local-label-novo']);
		$st_local = $_POST['local-status'];

		if ($id_local == 999) :
		
			$query = "INSERT INTO t_pmt_locais (id_local, nm_local, id_circunsc, st_local) VALUES (0, UPPER('{$nm_local}'), 100, {$st_local})";

		else :

			if ($nm_local != '') :
				$query = "UPDATE t_pmt_locais SET nm_local=UPPER('{$nm_local}'), st_local={$st_local} WHERE id_local={$id_local}";
			else :
				$query = "UPDATE t_pmt_locais SET st_local={$st_local} WHERE id_local={$id_local}";
			endif;

		endif;
		
		mysqli_query($conexao, $query);
		
endif;

header('Location:gestao-locais-lista.php');