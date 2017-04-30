<?php

session_start();
require_once("core/inc/conex.inc.php");
require_once("core/inc/funcoes.inc.php");

if(isset($_GET['uid']) && isset($_GET['mail']) && isset($_GET['h'])) :
	
	if($_GET['op'] == 'ativacao') :
		if(ativaPerfilDoUsuarioParaSql($conexao, $_GET['uid'], $_GET['mail'], $_GET['h']) == true) :
			gravaOpcoesValidasDePermutaDeSqlParaJson($conexao);
			if ($arrIdsOpcoesUsuario = recuperaIdsOpcoesDePermutaDoUsuarioDeJson($_GET['uid'])) :
				$_SESSION['perfilPermutz'] = recuperaPerfilDoUsuarioDeJson($_GET['uid']);
				$_SESSION['combinacoesPermutz'] = buscaPermutz($conexao, $arrIdsOpcoesUsuario, 6);
				header("Location: perfil-consolidado.php");
			else:
				$_SESSION['flash'] = array(
					'titulo' => 'Ooooops',
					'classe-contexto' => 'warning',
					'msg' => 'Erro ao recuperar as opções de permuta do usuário',
					'info-adicional' => '',
					);
				header('Location: flash.php');
				die();
			endif;
		else:
			$_SESSION['flash'] = array(
				'titulo' => 'Ooooops',
				'classe-contexto' => 'warning',
				'msg' => 'Nenhum perfil encontrado',
				'info-adicional' => '',
				);
			header('Location: flash.php');
			die();
		endif;
	
	elseif ($_GET['op'] == 'edicao') :
		$_SESSION['usuario'] = recuperaPerfilDoUsuarioDeSql($conexao, $_GET['uid'], $_GET['mail'], $_GET['h']);
		$_SESSION['pagina'] = array('titulo' => 'Alterar perfil', 'classe-contexto' => 'bg-default');
		header('Location: perfil-form.php');
		die();
	else :
		header('Location: index.php');
		die();
	endif;
else :
	header('Location: index.php');
	die();
endif;