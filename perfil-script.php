<?php

/*
 script que manipula os dados do perfil (validação e banco de dados)
 */
session_start();
require_once("core/inc/conex.inc.php");
require_once("core/inc/funcoes.inc.php");
require_once("PHPMailer/functions_mail.inc.php");




/* 
 CASO O USUÁRIO TENHA POSTADO UM FORMULÁRIO DE CRIAÇÃO OU EDIÇÃO DE PERFIL,
 EXECUTA OS BLOCOS ABAIXO:
 */
if (isset($_POST["btn-perfil-form-op"])) :

	/* seta a flag de validação (chave form-incompleto) para false no início da execução do script */
	$formValidacao['form-incompleto'] = false;

	/* caso o formulário de origem seja de edição, obtém o ID do usuário */
	if(isset($_POST['usuario-id'])):
		$usuarioId = intval($_POST["usuario-id"], 10);
	endif;

	




	/*
	 validando os campos postados
	 */

	/* validação do nome */
	$formValidacao['usuario-nome-validacao'] = '';
	if (!isset($_POST['usuario-nome']) || $_POST['usuario-nome']=='' || $_POST['usuario-nome']==null || strlen($_POST['usuario-nome'])<4) :
		$formValidacao['usuario-nome-validacao'] = 'has-error';
		$formValidacao['form-incompleto'] = true;
	else :
		$usuarioNome = trim($_POST['usuario-nome']);
		$usuarioNome = mysqli_real_escape_string($conexao, $usuarioNome);
	endif;
	
	/* validação do cargo */
	// $formValidacao['usuario-cargo-validacao'] = '';
	// if ($_POST['usuario-cargo-id']=='' || $_POST['usuario-cargo-id']==null) :
	// 	$formValidacao['usuario-cargo-validacao'] = 'has-error';
	// 	$formValidacao['form-incompleto'] = true;
	// else :
		$cargoId = 2;
	// endif;
	
	/* validação do telefone */
	$formValidacao['usuario-telefone-validacao'] = '';
	if ($_POST['usuario-telefone']=='' || $_POST['usuario-telefone']==null || strlen($_POST['usuario-telefone'])<8) :
		$formValidacao['usuario-telefone-validacao'] = 'has-error';
		$formValidacao['form-incompleto'] = true;
	else :
		$usuarioTel = trim($_POST['usuario-telefone']);
		$usuarioTel = mysqli_real_escape_string($conexao, $usuarioTel);
	endif;

	/* validação do email pessoal*/
	$formValidacao['usuario-email-pessoal-validacao'] = '';
	if (($_POST['usuario-email-pessoal']=='' || !filter_var($_POST['usuario-email-pessoal'], FILTER_VALIDATE_EMAIL)) && $_POST['btn-perfil-form-op']!='excluir') :
		$formValidacao['usuario-email-pessoal-validacao'] = 'has-error';
		$formValidacao['form-incompleto'] = true;
	else :
		$usuarioEmailPessoal = trim($_POST['usuario-email-pessoal']);
		$usuarioEmailPessoal = mysqli_real_escape_string($conexao, $usuarioEmailPessoal);
	endif;

	/* validação do email TJSP*/
	$formValidacao['usuario-email-tjsp-validacao'] = '';
	if ($_POST['usuario-email-tjsp']=='' || $_POST['usuario-email-tjsp']==null || strlen($_POST['usuario-email-tjsp'])<3 || stripos($_POST['usuario-email-tjsp'], '@')>0) :
		$formValidacao['usuario-email-tjsp-validacao'] = 'has-error';
		$formValidacao['form-incompleto'] = true;
	else :
		$strEmailTjsp = trim($_POST['usuario-email-tjsp']);
		$usuarioEmailTjsp = mysqli_real_escape_string($conexao, $strEmailTjsp."@tjsp.jus.br");
	endif;

	/* validação senha*/
	$formValidacao['usuario-senha-validacao'] = '';
	$senha = ($_POST['usuario-senha1'] == $_POST['usuario-senha2']) ? trim($_POST['usuario-senha1']) : '';
	if (($senha=='' || strlen($senha)<4) && $_POST['btn-perfil-form-op']!='excluir') :
		$formValidacao['usuario-senha-validacao'] = 'has-error';
		$formValidacao['form-incompleto'] = true;
	else :
		$usuarioSenha = mysqli_real_escape_string($conexao, $senha);
	endif;

	/* validação do local de origem */
	$formValidacao['local-origem-id-validacao'] = '';
	if ($_POST['local-origem-id']=='' || $_POST['local-origem-id']==null) :
		$formValidacao['local-origem-id-validacao'] = 'has-error';
		$formValidacao['form-incompleto'] = true;
	else :
		$localOrigemId = intval($_POST["local-origem-id"], 10);
	endif;

	/* validação do posto */
	$formValidacao['usuario-posto-validacao'] = '';
	if ($_POST['usuario-posto']=='' || $_POST['usuario-posto']==null) :
		$formValidacao['usuario-posto-validacao'] = 'has-error';
		$formValidacao['form-incompleto'] = true;
	else :
		$usuarioPosto = trim($_POST["usuario-posto"]);
		$usuarioPosto = mysqli_real_escape_string($conexao, $usuarioPosto);
	endif;

	/* validação dos arrays de locais de destino */
	$formValidacao['locais-destino-id-validacao'] = '';
	if ($_POST['btn-perfil-form-op']=='criar' && !isset($_POST['locais-desejados-adicionar']) ) :
		$formValidacao['locais-destino-id-validacao'] = 'has-error';
		$formValidacao['form-incompleto'] = true;
	elseif (isset($_POST['locais-desejados-cadastrados']) && count($_POST['locais-desejados-cadastrados'])==0 && 
		isset($_POST['locais-desejados-adicionar']) && count($_POST['locais-desejados-adicionar'])==0 && 
		isset($_POST['locais-desejados-excluir']) && count($_POST['locais-desejados-excluir']==0)) :
		$formValidacao['locais-destino-id-validacao'] = 'has-error';
		$formValidacao['form-incompleto'] = true;
	endif;

	/* atribuição do valor observações */
	// $usuarioObservacoes = $_POST['usuario-observacoes'];







	/*
	 caso existam dados inválidos no formulário:
		 1. seta uma variável de sessão com os dados preenchidos pelo usuário
		 2. redireciona o usuário para o formulário para correção dos dados
	 caso contrário, seta os demais dados e passa para o próximo bloco
	 */
	if($formValidacao['form-incompleto'] == true) :
		$_SESSION['usuario'] = array(
			'id' => isset($_POST['usuario-id']) ? $_POST['usuario-id'] : '',
			'nome' => isset($_POST['usuario-nome']) ? $_POST['usuario-nome'] : '',
			// 'cargo_id' => isset($_POST['usuario-cargo-id']) ? $_POST['usuario-cargo-id'] : null,
			'cargo_id' => 2,
			// 'cargo_nome' => isset($_POST['usuario-cargo-nome']) ? $_POST['usuario-cargo-nome'] : '',
			'cargo_nome' => isset($_POST['usuario-cargo-id']) ? 'Escrevente' : '',
			'telefone' => isset($_POST['usuario-telefone']) ? $_POST['usuario-telefone'] : '',
			'email_pessoal' => isset($_POST['usuario-email-pessoal']) ? $_POST['usuario-email-pessoal'] : '',
			'email_tjsp' => isset($_POST['usuario-email-tjsp']) ? $_POST['usuario-email-tjsp'] : '',
			'senha' => isset($_POST['usuario-senha1']) ? $_POST['usuario-senha1'] : '',
			'local_origem_id' => isset($_POST['local-origem-id']) ? $_POST['local-origem-id'] : null,
			'local_origem_nome' => isset($_POST['local-origem-nome']) ? $_POST['local-origem-nome'] : '',
			'posto' => isset($_POST['usuario-posto']) ? $_POST['usuario-posto'] : '',
			'locais_desejados_cadastrados' => isset($_POST['locais-desejados-cadastrados']) ? $_POST['locais-desejados-cadastrados'] : null,
			'locais_desejados_adicionar' => isset($_POST['locais-desejados-adicionar']) ? $_POST['locais-desejados-adicionar'] : null,
			'locais_desejados_excluir' => isset($_POST['locais-desejados-excluir']) ? $_POST['locais-desejados-excluir'] : null,
			'observacoes' => isset($_POST['usuario-observacoes']) ? $_POST['usuario-observacoes'] : '',
			);
		$_SESSION['form-validacao'] = $formValidacao;
		$_SESSION['pagina'] = array(
			'titulo' => 'Corrija os campos abaixo',
			'classe-contexto' => 'bg-danger',
			);
		header('Location: perfil-form.php');
		die();
	else :
		$idsLocaisDestinoAtuais = null;
		if (isset($_POST['locais-desejados-cadastrados']) && is_array($_POST['locais-desejados-cadastrados'])) :
			$idsLocaisDestinoAtuais = $_POST['locais-desejados-cadastrados'];
		endif;
		
		$idsLocaisDestinoAdicionar = null;
		if (isset($_POST['locais-desejados-adicionar']) && is_array($_POST['locais-desejados-adicionar'])) :
			$idsLocaisDestinoAdicionar = $_POST['locais-desejados-adicionar'];
		endif;

		$idsLocaisDestinoExcluir = null;
		if (isset($_POST['locais-desejados-excluir']) && is_array($_POST['locais-desejados-excluir'])) :
			$idsLocaisDestinoExcluir = $_POST['locais-desejados-excluir'];
		endif;

		$usuarioObservacoes = '';
		if(isset($_POST['usuario-observacoes'])):
			$usuarioObservacoes = trim($_POST['usuario-observacoes']);
			$usuarioObservacoes = strtoupper($usuarioObservacoes);
			$usuarioObservacoes = mysqli_real_escape_string($conexao, $usuarioObservacoes);
		endif;

		

		




		/*
		 caso os dados do formulário sejam válidos, o script verifica qual a operação chamada pelo usuário
		 e executa as operações no bando de dados
		 */

		/* verifica qual o tipo de operação */			
		$formScriptPerfil = $_POST['btn-perfil-form-op'];
		
		
		/*
		 1. CRIAÇÃO DE PERFIL
		 */
		if ($formScriptPerfil == 'criar') :
			if (consultaEmailDeSql($conexao, $usuarioEmailTjsp) == 0) :
				$usuarioId = gravaUsuarioParaSql($conexao, $usuarioNome, $cargoId, $usuarioTel, $usuarioEmailPessoal, $usuarioEmailTjsp, $usuarioSenha, $localOrigemId, $usuarioPosto, $usuarioObservacoes);
				gravaOpcoesDePermutaDoUsuarioParaSql($conexao, $usuarioId, $idsLocaisDestinoAdicionar);
				gravaOpcoesValidasDePermutaDeSqlParaJson($conexao);


				if ($arrIdsOpcoesUsuario = recuperaIdsOpcoesDePermutaDoUsuarioDeJson($usuarioId)) :
					$_SESSION['perfilPermutz'] = recuperaPerfilDoUsuarioDeJson($usuarioId);
					$_SESSION['combinacoesPermutz'] = buscaPermutz($conexao, $arrIdsOpcoesUsuario, 6);
					header("Location: perfil-consolidado.php");
					die();
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
			
			else :
				echo
					"<script>
						alert('E-mail pessoal já associado a um perfil existente. Tente outro.');
						history(-1);
					</script>";
			endif;

		/*
		 2. ALTERAÇÃO DE PERFIL
		 */
		elseif ($formScriptPerfil == 'alterar') :
			atualizaUsuarioParaSql($conexao, $usuarioId, $usuarioNome, $cargoId, $usuarioTel, $usuarioEmailPessoal, $usuarioEmailTjsp, $usuarioSenha, $localOrigemId, $usuarioPosto, $usuarioObservacoes);
			if (validaOpcoesDePermuta($idsLocaisDestinoAtuais, $idsLocaisDestinoAdicionar, $idsLocaisDestinoExcluir)>0) :
				atualizaOpcoesDePermutaDoUsuarioParaSql($conexao, $usuarioId, $idsLocaisDestinoAtuais, $idsLocaisDestinoAdicionar, $idsLocaisDestinoExcluir);
				gravaOpcoesValidasDePermutaDeSqlParaJson($conexao);
				$arrIdsOpcoesUsuario = recuperaIdsOpcoesDePermutaDoUsuarioDeJson($usuarioId);
				$_SESSION['perfilPermutz'] = recuperaPerfilDoUsuarioDeJson($usuarioId);
				$_SESSION['combinacoesPermutz'] = buscaPermutz($conexao, $arrIdsOpcoesUsuario, 6);
				header("Location: perfil-consolidado.php");
				die();
			else :
				$usuario = recuperaUltimoPerfilDeSql($conexao, $usuarioId);
				$link = "perfil-operacao.php?op=edicao&uid={$usuario['id']}&mail={$usuario['email_pessoal']}&h={$usuario['md5']}";
				echo "
					<script>
						alert('Você não pode excluir todas as opções de permuta');
						location.href='{$link}';
					</script>
					";
			endif;
	
		/*
		 3. EXCLUSÃO DE PERFIL
		 */
		elseif ($formScriptPerfil == 'excluir') :
			if (excluiPerfil($conexao, $usuarioId)) :
				gravaOpcoesValidasDePermutaDeSqlParaJson($conexao);
				$_SESSION['flash'] = array(
					'titulo' => 'Perfil excluído com sucesso!',
					'classe-contexto' => 'danger',
					'msg' => 'Quando precisar, cadastre novamente seu perfil',
					'info-adicional' => '',
					);
				header('Location: flash.php');
				die();
			else :
				$_SESSION['flash'] = array(
					'titulo' => 'Problemas na exclusão do perfil!',
					'classe-contexto' => 'danger',
					'msg' => 'Não localizamos os dados do seu perfil...',
					'info-adicional' => '',
					);
				header('Location: flash.php');
				die();
			endif;
		

		/*
		 4. N.D.A: MENSAGEM FLASH DE ERRO
		 */
		else :
			$_SESSION['flash'] = array(
				'titulo' => 'Operação com o perfil não informada corretamente',
				'classe-contexto' => 'danger',
				'msg' => 'Que tal tentar novamente?',
				'info-adicional' => '',
				);
			header('Location: flash.php');
			die();
		endif;

	endif;







/* 
 CASO O USUÁRIO TENHA POSTADO UM FORMULÁRIO COM LOGIN E SENHA PARA ALTERAÇÃO DO PERFIL
 EXECUTA OS BLOCOS ABAIXO:
 */
elseif (isset($_POST["btn-perfil-alteracao"])) :
	$inputEmail = $_POST["input-usuario-email-tjsp"] . "@tjsp.jus.br";
	$usuarioEmail = filter_var($inputEmail, FILTER_VALIDATE_EMAIL);
	$usuarioSenha = mysqli_real_escape_string($conexao, $_POST["usuario-senha"]);
	// if ($dadosUsuario = recuperaUsuarioByEmailStatusDeSql($conexao, $usuarioEmail)) :
	if ($dadosUsuario = recuperaUsuarioByEmailSenha($conexao, $usuarioEmail, $usuarioSenha)) :

		
		$_SESSION['usuario'] = recuperaPerfilDoUsuarioDeSql($conexao, $dadosUsuario['id'], $dadosUsuario['email_tjsp'], $dadosUsuario['md5']);
		$_SESSION['pagina'] = array('titulo' => 'Alterar perfil', 'classe-contexto' => 'bg-default');
		header('Location: perfil-form.php');
		die();





		// if (enviaLinkEdicaoPerfilPorEmail($dadosUsuario)) :
		// if (true) : ////////////////////////////
		// 	$_SESSION['flash'] = array(
		// 		'titulo' => 'Link enviado com sucesso!',
		// 		'classe-contexto' => 'success',
		// 		'msg' => 'Em breve, enviaremos ao email '. $usuarioEmail . ' um link para alteração de seu perfil, ok?', 
		// 		// 'info-adicional' => geraLinkOperacaoPerfil($dadosUsuario, 'edicao'), ///////////// excluir antes do deploy
		// 		// 'info-adicional' => '',
		// 		);
		// 	if (AMBIENTE == 0) :
		// 		$_SESSION['flash']['info-adicional'] = '<p>' . geraLinkOperacaoPerfil($dadosUsuario, 'edicao') . '</p>';
		// 	else :
		// 		$_SESSION['flash']['info-adicional'] = '';
		// 		enviaLinkEdicaoPerfilPorEmail($dadosUsuario);
		// 	endif;
		// 	header('Location: flash.php');
		// 	die();
		// else :
		// 	$_SESSION['flash'] = array(
		// 		'titulo' => 'Oooops',
		// 		'classe-contexto' => 'danger',
		// 		'msg' => 'Estamos com problemas no envio de e-mails. Por favor, tente mais tarde.', 
		// 		'info-adicional' => '',
		// 		);
		// 	header('Location: flash.php');
		// 	die();
		// endif;
	else :
		$_SESSION['flash'] = array(
			'titulo' => 'Acesso negado',
			'classe-contexto' => 'warning',
			// 'msg' => 'Não localizamos o e-mail ' . $usuarioEmail . ' em nossos registros...', 
			'msg' => 'Login/senha não conferem', 
			'info-adicional' => '',
			);
		header('Location: flash.php');
		die();
	endif;








/* 
 CASO O USUÁRIO TENHA POSTADO UM FORMULÁRIO DE REQUISIÇÃO DE COMBINAÇÕES
 EXECUTA OS BLOCOS ABAIXO:
 */
elseif (isset($_POST["combinator-form-submit"])) :
	$inputEmail = $_POST["input-usuario-email-tjsp"] . "@tjsp.jus.br";
	$usuarioEmail = filter_var($inputEmail, FILTER_VALIDATE_EMAIL);
	$usuarioSenha = mysqli_real_escape_string($conexao, $_POST["usuario-senha"]);
	if ($dadosUsuario = zRecuperaUsuarioByEmailStatusDeSql($conexao, $usuarioEmail, $usuarioSenha)) :
		$usuarioId = $dadosUsuario['id'];
		if ($arrIdsOpcoesUsuario = recuperaIdsOpcoesDePermutaDoUsuarioDeJson($usuarioId)) :
			$_SESSION['perfilPermutz'] = recuperaPerfilDoUsuarioDeJson($usuarioId);
			$_SESSION['combinacoesPermutz'] = buscaPermutz($conexao, $arrIdsOpcoesUsuario, 5);
			header("Location: perfil-consolidado.php");
		else:
			// echo "Erro ao recuperar as opções de permuta do usuário";
			$_SESSION['flash'] = array(
				'titulo' => 'Erro ao recuperar opções de permuta',
				'classe-contexto' => 'warning',
				'msg' => 'Estamos com problemas em localizar seus registros... Por favor, tente novamente mais tarde', 
				'info-adicional' => '',
				);
			header('Location: flash.php');
			die();
		endif;
	else :
		$_SESSION['flash'] = array(
			'titulo' => 'E-mail não localizado',
			'classe-contexto' => 'warning',
			'msg' => 'Não localizamos o e-mail ' . $usuarioEmail . ' em nossos registros...', 
			'info-adicional' => '<a type="button" class="btn btn-default btn-lg btn-block" href="combinator-form.php">Tentar novamente</a>',
			);
		header('Location: flash.php');
		// header("Location: combinator-erro.php?email={$usuarioEmail}"); //val=0: e-mail não localizado
		die();
	endif;








/* 
 CASO O USUÁRIO TENHA POSTADO UM FORMULÁRIO DE RECUPERAÇÃO DE SENHA
 EXECUTA OS BLOCOS ABAIXO:
 */
elseif (isset($_POST["btn-perfil-recuperar"])) :
	$inputEmail = $_POST["input-usuario-email-tjsp"] . "@tjsp.jus.br";
	$usuarioEmailTjsp = filter_var($inputEmail, FILTER_VALIDATE_EMAIL);
	$dadosUsuario = recuperaUsuarioByEmailDeSql($conexao, $usuarioEmailTjsp);
	if ($usuarioEmailTjsp && $dadosUsuario) :
		$intId = $dadosUsuario['id'];
		$strNome = $dadosUsuario['nome'];
		$intIdCargo = $dadosUsuario['id_cargo'];
		$strTelefone = $dadosUsuario['telefone'];
		$strEmailPessoal = $dadosUsuario['email_pessoal'];
		$strEmailTjsp = $dadosUsuario['email_tjsp'];
		$strNovaSenha = substr(md5($dadosUsuario['nome'] . $dadosUsuario['posto'] . time()), 0, 8);
		$intIdLocalAtual = $dadosUsuario['local_origem_id'];
		$strPostoDeTrabalho = $dadosUsuario['posto'];
		$txtObservacao = $dadosUsuario['observacoes'];
		atualizaUsuarioParaSql($conexao, $intId, $strNome, $intIdCargo, $strTelefone, $strEmailPessoal, $strEmailTjsp, $strNovaSenha, $intIdLocalAtual, $strPostoDeTrabalho, $txtObservacao);
		if (enviaNovaSenhaUsuario($dadosUsuario, $strNovaSenha)) :
			$_SESSION['flash'] = array(
				'titulo' => 'Requisição processada',
				'classe-contexto' => 'success',
				'msg' => 'Estamos preparando uma nova senha para você!', 
				'info-adicional' => '<p>Em breve ela será enviada ao e-mail <strong>' . $strEmailTjsp . '</strong></p>',
				);
		else:
			$_SESSION['flash'] = array(
				'titulo' => 'Erro ao recuperar opções de permuta',
				'classe-contexto' => 'warning',
				'msg' => 'Estamos com problemas em localizar seus registros... Por favor, tente novamente mais tarde', 
				'info-adicional' => '',
				);
		endif;
	else :
		$_SESSION['flash'] = array(
			'titulo' => 'E-mail não localizado',
			'classe-contexto' => 'warning',
			'msg' => 'Não localizamos o e-mail ' . $usuarioEmail . ' em nossos registros...', 
			'info-adicional' => '<a type="button" class="btn btn-default btn-lg btn-block" href="combinator-form.php">Tentar novamente</a>',
			);
	endif;
	header('Location: flash.php');
	die();



		
else :
	header("Location: index.php");
	die();
endif;