<?php

// função para imprimir vetor formatado
function arrayprint($array) {
	echo "<pre>", print_r($array), "</pre>";
	echo "<hr/>";
}

// function retiraCJ($str) {
// 	return mb_convert_case(trim(substr($str, 0, strpos($str, " -"))), MB_CASE_UPPER, "UTF-8");
// }

/**************************************************

			FUNÇÕES DE USUÁRIOS

***************************************************/

function consultaEmailDeSql($conexao, $strEmailUsuario) {
	$querySelectUsuarioByEmail = "SELECT * FROM t_pmt_usuarios WHERE ds_email_tjsp='{$strEmailUsuario}'";
	$result = mysqli_query($conexao, $querySelectUsuarioByEmail);
	return mysqli_num_rows($result);
}




function gravaUsuarioParaSql($conexao, $strNome, $intIdCargo, $strTelefone, $strEmailPessoal, $strEmailTjsp, $strSenha, $intIdLocalAtual, $strPostoDeTrabalho, $strObservacoes) {
	$queryInsertUsuario = "INSERT INTO t_pmt_usuarios (nm_usuario, id_cargo, nr_telefone, ds_email_pessoal, ds_email_tjsp, ds_senha, id_local_atual, ds_posto, st_usuario, ds_md5, ds_observacao) VALUES (UPPER('{$strNome}'), {$intIdCargo}, UPPER('{$strTelefone}'), LOWER('{$strEmailPessoal}'), LOWER('{$strEmailTjsp}'), md5('{$strSenha}'), {$intIdLocalAtual}, UPPER('{$strPostoDeTrabalho}'), 1, md5('{$strEmailPessoal}'), UPPER('{$strObservacoes}'))";
	// if (mysqli_query($conexao, $queryInsertUsuario)) :
		mysqli_query($conexao, $queryInsertUsuario);
		$usuarioId = mysqli_insert_id($conexao);
		return $usuarioId;
	// else :
	// 	return FALSE;
	// endif;
}



function gravaOpcoesDePermutaDoUsuarioParaSql($conexao, $intIdUsuario, $arrIdsLocaisDesejados) {
	$arrIdsLocaisDesejadosFiltrados = array();
	foreach ($arrIdsLocaisDesejados as $strIdLocalDesejado) :
		array_push($arrIdsLocaisDesejadosFiltrados, intval($strIdLocalDesejado));
	endforeach;
	$queryInsertOpcoes = "INSERT INTO t_pmt_opcoes_permuta (id_usuario, id_local_desejado, st_opcao) VALUES ";
	$totalLocais = count($arrIdsLocaisDesejadosFiltrados);
	if ($totalLocais == 1) :
		$queryInsertOpcoes .= "({$intIdUsuario}, {$arrIdsLocaisDesejadosFiltrados[0]}, 1)";
	else :
		for ($i=0; $i<($totalLocais-1); $i++):
			$queryInsertOpcoes .= "({$intIdUsuario}, {$arrIdsLocaisDesejadosFiltrados[$i]}, 1), ";
		endfor;
		$queryInsertOpcoes .= "({$intIdUsuario}, {$arrIdsLocaisDesejadosFiltrados[$totalLocais-1]}, 1)";
	endif;
	if ($result = mysqli_query($conexao, $queryInsertOpcoes)) :
		return true;
	else :
		return false;
	endif;
}



function recuperaUltimoPerfilDeSql($conexao, $intUid) {
	$querySelectPerfilById = "SELECT 
					u.id_usuario, u.nm_usuario, u.id_cargo, c.nm_cargo, u.nr_telefone, u.ds_email_pessoal, u.ds_email_tjsp, 
					u.id_local_atual, la.nm_local AS nm_local_atual, u.ds_posto, la.st_local AS st_local_atual, 
					u.ds_md5, u.ds_observacao,
				    o.id_opcao, o.id_local_desejado, ld.nm_local AS nm_local_desejado, ld.st_local AS st_local_desejado, 
				    o.st_opcao  
				FROM t_pmt_usuarios AS u 
				INNER JOIN t_pmt_cargos AS c ON (u.id_cargo = c.id_cargo)  
				INNER JOIN t_pmt_opcoes_permuta AS o ON (u.id_usuario = o.id_usuario)  
				INNER JOIN t_pmt_locais AS la ON (u.id_local_atual = la.id_local) 
				INNER JOIN t_pmt_locais AS ld ON (o.id_local_desejado = ld.id_local) 
				WHERE 
					u.id_usuario={$intUid} AND c.st_cargo=1
				ORDER BY o.id_opcao";
	$result = mysqli_query($conexao, $querySelectPerfilById);
	// if (mysqli_num_rows($result)>0) :
		$linha = mysqli_fetch_array($result);
		$arrPerfilDoUsuario['id'] = $linha['id_usuario'];
		$arrPerfilDoUsuario['nome'] = $linha['nm_usuario'];
		$arrPerfilDoUsuario['id_cargo'] = $linha['id_cargo'];
		$arrPerfilDoUsuario['nm_cargo'] = $linha['nm_cargo'];
		$arrPerfilDoUsuario['telefone'] = $linha['nr_telefone'];
		$arrPerfilDoUsuario['email_pessoal'] = $linha['ds_email_pessoal'];
		$explodeEmail = explode('@', $linha['ds_email_tjsp']);
		// $arrPerfilDoUsuario['id_email'] = $explodeEmail[0];
		$arrPerfilDoUsuario['email_id'] = $explodeEmail[0];
		$arrPerfilDoUsuario['email_tjsp'] = $linha['ds_email_tjsp'];
		$arrPerfilDoUsuario['senha'] = '';
		$arrPerfilDoUsuario['local_atual'] = $linha['id_local_atual'].': '.$linha['nm_local_atual'];
		$arrPerfilDoUsuario['local_origem_id'] = $linha['id_local_atual'];
		$arrPerfilDoUsuario['local_origem_nome'] = $linha['nm_local_atual'];
		$arrPerfilDoUsuario['posto'] = $linha['ds_posto'];
		$arrPerfilDoUsuario['md5'] = $linha['ds_md5'];
		$arrPerfilDoUsuario['observacoes'] = $linha['ds_observacao'];
		// $arrPerfilDoUsuario['locais_desejados'] = array();
		// if ($linha['st_local_atual']==1 && $linha['st_local_desejado']==1) :
		// 	$arrPerfilDoUsuario['locais_desejados'][$linha['id_local_desejado']] = $linha['nm_local_desejado'];
		// endif;
		// while ($linha = mysqli_fetch_array($result)) :
		// 	if ($linha['st_local_atual']==1 && $linha['st_local_desejado']==1) :
		// 		$arrPerfilDoUsuario['locais_desejados'][$linha['id_local_desejado']] = $linha['nm_local_desejado'];
		// 	endif;
		// endwhile;
		// return $arrPerfilDoUsuario;
		$arrPerfilDoUsuario['locais_desejados_cadastrados'] = array();
		if ($linha['st_local_atual']==1 && $linha['st_local_desejado']==1) :
			// $arrPerfilDoUsuario['locais_desejados_cadastrados'][$linha['id_local_desejado']] = $linha['nm_local_desejado'];
			$local = $linha['id_local_desejado'] . ": " . $linha['nm_local_desejado'];
				array_push($arrPerfilDoUsuario['locais_desejados_cadastrados'], $local);
		endif;
		while ($linha = mysqli_fetch_array($result)) :
			if ($linha['st_local_atual']==1 && $linha['st_local_desejado']==1) :
				// $arrPerfilDoUsuario['locais_desejados_cadastrados'][$linha['id_local_desejado']] = $linha['nm_local_desejado'];
				$local = $linha['id_local_desejado'] . ": " . $linha['nm_local_desejado'];
				array_push($arrPerfilDoUsuario['locais_desejados_cadastrados'], $local);
			endif;
		endwhile;
		$arrPerfilDoUsuario['locais_desejados_adicionar'] = null;
		$arrPerfilDoUsuario['locais_desejados_excluir'] = null;
		return $arrPerfilDoUsuario;
	// else :
	// 	return false;
	// endif;
}



function geraLinkOperacaoPerfil($arrDadosUsuario, $operacao) {
	$arrDadosLink = array (
		'op' => $operacao,
		'uid' => $arrDadosUsuario['id'],
		'mail' => $arrDadosUsuario['email_pessoal'],
		'h' => $arrDadosUsuario['md5'],
		);
	$strLink = URL_BASE.'perfil-operacao.php?'.http_build_query($arrDadosLink);
	$strLinkHtml = '<a href="'.$strLink.'">'.$strLink.'</a>';
	return $strLinkHtml;
}




function enviaLinkAtivacaoPerfilPorEmail($arrDadosUsuario) {

$listaOpcoesPermuta = "";
foreach ($arrDadosUsuario['locais_desejados_cadastrados'] as $idLocal => $nomeLocal) :
	$listaOpcoesPermuta .= "<li>{$nomeLocal}</li>";
endforeach;

$strLink = geraLinkOperacaoPerfil($arrDadosUsuario, 'ativacao');
$para = $arrDadosUsuario['email_pessoal'];
$assunto = "Link para ativacao de seu perfil Permutz";

$msg = <<<MSG
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Link para ativação do seu perfil Permutz</title>
	<style>
		body {font-family: "Helvetica Neue", Helvetica, Arial, sans-serif}
		p, li {margin-top: 15px; margin-bottom: 15px;}
	</style>
</head>
<body>
	<h1>Link para ativação do seu perfil Permutz</h1>
	<p>Olá!</p>
	<p>Estamos disponibilizando o link abaixo, pra que você possa ativar seu perfil Permutz:</p>
	<p><a href={$strLink}>{$strLink}</a></p>
	<p>Estes são os dados do seu perfil:</p>
	<ul>
		<li><strong>Nome:</strong> {$arrDadosUsuario['nome']}</li>
		<li><strong>Cargo:</strong> {$arrDadosUsuario['nm_cargo']}</li>
		<li><strong>Telefone:</strong> {$arrDadosUsuario['telefone']}</li>
		<li><strong>Lotação:</strong> {$arrDadosUsuario['local_atual']} ({$arrDadosUsuario['posto']})</li>
		<li>
			<strong>Opções de Permuta:</strong>
			<ul>
				{$listaOpcoesPermuta}
			</ul>
		</li>
		<li><strong>Observações:</strong> {$arrDadosUsuario['observacoes']}</li>
	</ul>
	<p>Atenciosamente,</p>
	<p><a href="{URL_BASE}">Equipe Permutz</a></p>
</body>
</html>
MSG;

$remetente = "no-reply@renefb.info";

if (enviaEmail($para, $assunto, $msg, $remetente, $remetente)) :
	return true;
else :
	return false;
endif;

// echo $msg;
}





function enviaEmail($para, $assunto, $mensagem, $de, $email_servidor) {
	/// revisar todas as variáveis

	$email_servidor = "no-reply@renefb.info";
	$de = $email_servidor;
	$headers = "From: $email_servidor\r\n" .
	           "Bcc: monitoramento@permutz.renefb.info\r\n" .
	           "Reply-To: $de\r\n" .
	           "X-Mailer: PHP/" . phpversion() . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	if (mail($para, $assunto, nl2br($mensagem), $headers, "-f$email_servidor")) :
		return true;
	else :
		return false;
	endif;
}




function recuperaPerfilDoUsuarioDeSql($conexao, $intUid, $strEmailTjsp, $strMd5) {
	$arrPerfilDoUsuario = array();
	$querySelectPerfil = "SELECT 
					u.id_usuario, u.nm_usuario, u.id_cargo, c.nm_cargo, u.nr_telefone, u.ds_md5, u.ds_observacao, 
				    u.ds_email_pessoal, u.ds_email_tjsp, u.id_local_atual, 
				    la.nm_local AS nm_local_atual, u.ds_posto, la.st_local AS st_local_atual, 
				    o.id_opcao, o.id_local_desejado, ld.nm_local AS nm_local_desejado, ld.st_local AS st_local_desejado, 
				    o.st_opcao  
				FROM t_pmt_usuarios AS u 
				INNER JOIN t_pmt_cargos AS c ON (u.id_cargo = c.id_cargo)  
				INNER JOIN t_pmt_opcoes_permuta AS o ON (u.id_usuario = o.id_usuario)  
				INNER JOIN t_pmt_locais AS la ON (u.id_local_atual = la.id_local) 
				INNER JOIN t_pmt_locais AS ld ON (o.id_local_desejado = ld.id_local) 
				WHERE 
					u.id_usuario={$intUid} AND u.ds_email_tjsp='{$strEmailTjsp}' AND u.ds_md5='{$strMd5}' AND c.st_cargo=1";
				// ORDER BY o.id_opcao";
	// $querySelectPerfil = mysqli_real_escape_string($conexao, $querySelectPerfil);
	$result = mysqli_query($conexao, $querySelectPerfil);
	if (mysqli_num_rows($result)>0) :
		$linha = mysqli_fetch_array($result);
		$arrPerfilDoUsuario['id'] = $linha['id_usuario'];
		$arrPerfilDoUsuario['nome'] = $linha['nm_usuario'];
		$arrPerfilDoUsuario['id_cargo'] = $linha['id_cargo'];
		$arrPerfilDoUsuario['nm_cargo'] = $linha['nm_cargo'];
		$arrPerfilDoUsuario['telefone'] = $linha['nr_telefone'];
		$arrPerfilDoUsuario['email_pessoal'] = $linha['ds_email_pessoal'];
		$explodeEmail = explode('@', $linha['ds_email_tjsp']);
		$arrPerfilDoUsuario['email_tjsp'] = $explodeEmail[0];
		// $arrPerfilDoUsuario['email_tjsp'] = $linha['ds_email_tjsp'];
		$arrPerfilDoUsuario['senha'] = '';
		$arrPerfilDoUsuario['local_atual'] = $linha['id_local_atual'].': '.$linha['nm_local_atual'];
		$arrPerfilDoUsuario['local_origem_id'] = $linha['id_local_atual'];
		$arrPerfilDoUsuario['local_origem_nome'] = $linha['nm_local_atual'];
		$arrPerfilDoUsuario['posto'] = $linha['ds_posto'];
		$arrPerfilDoUsuario['md5'] = $linha['ds_md5'];
		$arrPerfilDoUsuario['observacoes'] = $linha['ds_observacao'];
		$arrPerfilDoUsuario['locais_desejados_cadastrados'] = array();
		if ($linha['st_local_atual']==1 && $linha['st_local_desejado']==1) :
			$local = 
			$local = $linha['id_local_desejado'] . ": " . $linha['nm_local_desejado'];
			array_push($arrPerfilDoUsuario['locais_desejados_cadastrados'], $local);
		endif;
		while ($linha = mysqli_fetch_array($result)) :
			if ($linha['st_local_atual']==1 && $linha['st_local_desejado']==1) :
				$local = $linha['id_local_desejado'] . ": " . $linha['nm_local_desejado'];
				array_push($arrPerfilDoUsuario['locais_desejados_cadastrados'], $local);
			endif;
		endwhile;
		$arrPerfilDoUsuario['locais_desejados_adicionar'] = null;
		$arrPerfilDoUsuario['locais_desejados_excluir'] = null;
		return $arrPerfilDoUsuario;
	else :
		return false;
	endif;
}



function ativaPerfilDoUsuarioParaSql($conexao, $intUid, $strEmailPessoal, $strMd5) {
	$queryUpdateUsuario = "UPDATE t_pmt_usuarios SET st_usuario=1 WHERE id_usuario={$intUid} AND ds_email_pessoal='{$strEmailPessoal}' AND ds_md5='{$strMd5}'";
	$result = mysqli_query($conexao, $queryUpdateUsuario);
	if(mysqli_affected_rows($conexao) == 1) :
		$queryUpdateOpcoes = "UPDATE t_pmt_opcoes_permuta SET st_opcao=1 WHERE id_usuario={$intUid}";
		mysqli_query($conexao, $queryUpdateOpcoes);
		return true;
	else:
		return false;
	endif;
}




function gravaOpcoesValidasDePermutaDeSqlParaJson($conexao) {
	$arrOpcoesValidas = array();
	$querySelectOpcoesValidas = 
		"SELECT 
			o.id_opcao, o.id_usuario, 
		    u.nm_usuario, u.id_cargo, u.nr_telefone, u.ds_email_pessoal, u.ds_email_tjsp, u.ds_posto, 
		    c.nm_cargo, u.ds_observacao, 
		    la.id_local AS id_local_atual, la.nm_local AS nm_local_atual, 
		    ld.id_local AS id_local_desejado, ld.nm_local AS nm_local_desejado 
		FROM t_pmt_opcoes_permuta AS o
		INNER JOIN t_pmt_usuarios AS u ON (o.id_usuario = u.id_usuario)
		INNER JOIN t_pmt_cargos AS c ON (u.id_cargo = c.id_cargo) 
		INNER JOIN t_pmt_locais AS la ON (u.id_local_atual = la.id_local)
		INNER JOIN t_pmt_locais AS ld ON (o.id_local_desejado = ld.id_local)
		WHERE o.st_opcao=1 AND u.st_usuario = 1 AND c.st_cargo=1 AND la.st_local=1 AND ld.st_local=1
		ORDER BY o.id_opcao";
	// $querySelectOpcoesValidas = mysqli_real_escape_string($conexao, $querySelectOpcoesValidas);
	$result = mysqli_query($conexao, $querySelectOpcoesValidas);
	while ($linha = mysqli_fetch_array($result)) :
		$arrOpcoesValidas[$linha['id_opcao']] = array(
			'id_usuario' => $linha['id_usuario'],
			'nm_usuario' => $linha['nm_usuario'],
			'nm_cargo' => $linha['nm_cargo'],
			'ds_email_pessoal' => $linha['ds_email_pessoal'],
			'ds_email_tjsp' => $linha['ds_email_tjsp'],
			'nr_telefone' => $linha['nr_telefone'],
			'ds_posto' => $linha['ds_posto'],
			'ds_observacao' => $linha['ds_observacao'],
			'id_local_atual' => $linha['id_local_atual'],
			'nm_local_atual' => $linha['nm_local_atual'],
			'id_local_desejado' => $linha['id_local_desejado'],
			'nm_local_desejado' => $linha['nm_local_desejado']
		);
	endwhile;
	/* escreve o conteudo do array $vetorDeOpcoes, em formato json, no arquivo mapadeopcoes.json */
	$jsonStream = fopen(JSON_OPCOES_VALIDAS_DE_PERMUTA, "w+");
	fwrite($jsonStream, json_encode($arrOpcoesValidas));
	fclose($jsonStream);
}



function recuperaIdsOpcoesDePermutaDoUsuarioDeJson($intIdUsuario) {
	$arrIdsOpcoesValidasDePermuta = recuperaOpcoesValidasDePermutaDeJsonParaArray();
	$arrIdsOpcoesDoUsuario = array();
	foreach ($arrIdsOpcoesValidasDePermuta as $intIdOpcao => $arrDadosOpcao) :
		if($arrDadosOpcao['id_usuario']==$intIdUsuario) :
			$arrIdsOpcoesDoUsuario[$intIdOpcao] = $arrDadosOpcao;
		endif;
	endforeach;
	if (count($arrIdsOpcoesDoUsuario)>0) :
		return $arrIdsOpcoesDoUsuario;
	else :
		return false;
	endif;
}




function atualizaUsuarioParaSql($conexao, $intId, $strNome, $intIdCargo, $strTelefone, $strEmailPessoal, $strEmailTjsp, $strSenha, $intIdLocalAtual, $strPostoDeTrabalho, $txtObservacao) {
	$queryUpdateUsuario = "UPDATE t_pmt_usuarios SET 
							nm_usuario=UPPER('{$strNome}'), 
							id_cargo={$intIdCargo}, 
							nr_telefone='{$strTelefone}', 
							ds_email_pessoal=LOWER('{$strEmailPessoal}'), 
							ds_email_tjsp=LOWER('{$strEmailTjsp}'), 
							ds_senha=md5('{$strSenha}'), 
							id_local_atual={$intIdLocalAtual}, 
							ds_posto='{$strPostoDeTrabalho}', 
							st_usuario = 1, 
							ds_md5=md5('{$strEmailPessoal}'), 
							ds_observacao = '{$txtObservacao}',
							dt_alteracao = CURRENT_TIMESTAMP  
						WHERE id_usuario={$intId}";
	if (mysqli_query($conexao, $queryUpdateUsuario)) :
		return true;
	else :
		return false;
	endif;
}



function atualizaOpcoesDePermutaDoUsuarioParaSql($conexao, $intIdUsuario, $arrIdsLocaisExistentes, $arrIdsLocaisAdicionar, $arrIdsLocaisExcluir) {
	
	$arrIdsLocaisExistentesFiltrados = is_array($arrIdsLocaisExistentes) ? $arrIdsLocaisExistentes : array();
	
	$queryOpcoes = "";
	
	if(isset($arrIdsLocaisExcluir) && is_array($arrIdsLocaisExcluir)) :
		$arrIdsLocaisExcluirFiltrados = array_intersect($arrIdsLocaisExcluir, $arrIdsLocaisExistentesFiltrados);
		foreach ($arrIdsLocaisExcluirFiltrados as $intIdLocalExcluir) :
			$intIdLocalExcluir = intval($intIdLocalExcluir);
			// $queryOpcoes .= "UPDATE t_pmt_opcoes_permuta SET st_opcao=0 WHERE id_usuario={$intIdUsuario} AND id_local_desejado={$intIdLocalExcluir};";
			// $queryOpcoes .= "DELETE FROM t_pmt_opcoes_permuta WHERE id_usuario={$intIdUsuario} AND id_local_desejado={$intIdLocalExcluir};";
			$queryExcluir = "DELETE FROM t_pmt_opcoes_permuta WHERE id_usuario={$intIdUsuario} AND id_local_desejado={$intIdLocalExcluir};";
			mysqli_query($conexao, $queryExcluir);
		endforeach;
	endif;

	if(isset($arrIdsLocaisAdicionar) && is_array($arrIdsLocaisAdicionar)) :
		$arrIdsLocaisAdicionarFiltrados = array();
		foreach ($arrIdsLocaisAdicionar as $strIdLocalAdicionar) {
			// if (array_search($strIdLocalAdicionar, haystack)) :
				array_push($arrIdsLocaisAdicionarFiltrados, intval($strIdLocalAdicionar));
			// endif;
		}
		$arrIdsLocaisAdicionarFiltrados = array_diff($arrIdsLocaisAdicionarFiltrados, $arrIdsLocaisExistentesFiltrados);

		foreach ($arrIdsLocaisAdicionarFiltrados as $intIdLocalAdicionar) :
			$queryAdicionar = "INSERT INTO t_pmt_opcoes_permuta (id_usuario, id_local_desejado, st_opcao) VALUES ({$intIdUsuario}, {$intIdLocalAdicionar}, 1);";
			mysqli_query($conexao, $queryAdicionar);
		endforeach;
	endif;
}




function recuperaOpcoesValidasDePermutaDeJsonParaArray() {
	// abre o arquivo json para leitura
	$jsonStream = fopen(JSON_OPCOES_VALIDAS_DE_PERMUTA, "r");

	// armazena o conteudo do arquivo
	$jsonString = fgets($jsonStream);

	// fecha o arquivo json
	fclose($jsonStream);

	// converte a string num array e retorna o resultado
	return json_decode($jsonString, true);
}



function recuperaIdsOpcoesDePermutaDoUsuarioDeSql($conexao, $intIdUsuario) {
	$arrIdsOpcoesDoUsuario = array();
	$querySelectOpcoesByIdUsuario = 
		"SELECT 
			o.id_opcao, o.id_usuario, la.id_local AS id_local_atual, ld.id_local AS id_local_desejado 
		FROM t_pmt_opcoes_permuta AS o
	    INNER JOIN t_pmt_usuarios AS u ON (o.id_usuario = u.id_usuario)
	    INNER JOIN t_pmt_locais AS la ON (u.id_local_atual = la.id_local)
	    INNER JOIN t_pmt_locais AS ld ON (o.id_local_desejado = ld.id_local)
	    WHERE u.id_usuario = {$intIdUsuario}
	    ORDER BY o.id_opcao";
	// $querySelectOpcoesByIdUsuario = mysqli_real_escape_string($conexao, $querySelectOpcoesByIdUsuario);
	if ($result = mysqli_query($conexao, $querySelectOpcoesByIdUsuario)) :
		if (mysqli_num_rows($result)>0) :
			while ($linha = mysqli_fetch_array($result)) :
				$arrOrigemDestino = array('id_local_atual'=>$linha['id_local_atual'], 'id_local_desejado'=>$linha['id_local_desejado']);
				// $arrOpcaoUsuario = array($linha['id_opcao'] => $arrOrigemDestino);
				// array_push($arrIdsOpcoesDoUsuario, $arrOpcaoUsuario);
				$arrIdsOpcoesDoUsuario[$linha['id_opcao']] = $arrOrigemDestino;
			endwhile;
			return $arrIdsOpcoesDoUsuario;
		else :
			return false;
		endif;
	else :
		return false;
	endif;
}


function recuperaPerfilDoUsuarioDeJson($intIdUsuario) {
	$arrIdsOpcoesDoUsuario = recuperaIdsOpcoesDePermutaDoUsuarioDeJson($intIdUsuario);
	$arrPerfilDoUsuario = array_slice(current($arrIdsOpcoesDoUsuario), 0, 10, true);
	$arrPerfilDoUsuario['ids_opcoes_permuta'] = array();
	foreach ($arrIdsOpcoesDoUsuario as $intIdOpcao => $arrDadosOpcao) :
		$arrPerfilDoUsuario['ids_opcoes_permuta'][$intIdOpcao]['id_local_desejado'] = $arrDadosOpcao['id_local_desejado'];
		$arrPerfilDoUsuario['ids_opcoes_permuta'][$intIdOpcao]['nm_local_desejado'] = $arrDadosOpcao['nm_local_desejado'];
	endforeach;
	return $arrPerfilDoUsuario;
}







/*
 * função que faz a mágica
 * a partir de um array de referência, que indica as opções de origem e destino de um usuário específico, a função retorna
 * outro array, cujas chaves indicam a sequência dos IDs de todas as opções de permuta que combinam com as opções do usuário  
 * e os valores indicam os IDs dos locais de origem e destino da última opção identificada na respectiva chave
 */
function combinaOpcoesDePermutasRecursivamente($conexao, $arrOpcoesDeReferencia, $intIteracoes) {
	if (is_array($arrOpcoesDeReferencia) && count($arrOpcoesDeReferencia)>0 ) :
		
		$arrOpcoes = $arrOpcoesDeReferencia;
		
		$opcaoAtual = key($arrOpcoes);
		
		$idLocalAtual = $arrOpcoes[$opcaoAtual]['id_local_atual'];
		
		if ($intIteracoes<0 || $intIteracoes>6) :
			$i = 6;
		else :
			$i = $intIteracoes;
		endif;
		
		do {
			$strOpcaoInicial = key($arrOpcoesDeReferencia); // definido como string pq, ao longo das iterações, recebe hifens
			
			$intIdLocalDestino = $arrOpcoesDeReferencia[$strOpcaoInicial]['id_local_desejado'];
			
			$querySelectOpcoesByIdLocalDestino = "SELECT 
		 					o.id_opcao, u.id_usuario, lo.id_local AS id_local_atual, ld.id_local AS id_local_desejado 
		 				FROM t_pmt_usuarios AS u
		 				INNER JOIN t_pmt_opcoes_permuta AS o ON (u.id_usuario = o.id_usuario)
		 				INNER JOIN t_pmt_locais AS lo ON (u.id_local_atual = lo.id_local)
		 				INNER JOIN t_pmt_locais AS ld ON (o.id_local_desejado = ld.id_local)
		 				WHERE u.st_usuario=1 AND ld.st_local=1 AND o.st_opcao=1 AND lo.id_local={$intIdLocalDestino}";
		 	
		 	$result = mysqli_query($conexao, $querySelectOpcoesByIdLocalDestino);
		 	
		 	while ($linha = mysqli_fetch_array($result)) :
		 	
		 		if ($linha['id_local_atual']!=$idLocalAtual) :	
		 		
			 		$arrOpcoes[$strOpcaoInicial.'-'.$linha['id_opcao']] = array(
			 				'id_local_atual'=> $linha['id_local_atual'],
			 				'id_local_desejado' => $linha['id_local_desejado']
			 			);

		 		endif;
		 	
		 	endwhile;

		} while (next($arrOpcoesDeReferencia));
		
		$i--;
		
		if ($i>1) :
			
			$arrTemp = combinaOpcoesDePermutasRecursivamente($conexao, $arrOpcoes, $i);
			
			$arrOpcoes = $arrTemp;
		
		endif;

		return $arrOpcoes;

	else :
		
		return false;
	
	endif;
}

/*
 * função que verifica se o array gerado na função anterior possui alguma combinação cujo destino final coincida com a origem do usuário
 */
function buscaPermutz($conexao, $arrIdsOpcoesDePermutaDoUsuario, $intGrausDePermuta) {
	if (is_array($arrIdsOpcoesDePermutaDoUsuario) && count($arrIdsOpcoesDePermutaDoUsuario)>0) :
		$arrPermutz = array();
		$arrOpcoesValidas = recuperaOpcoesValidasDePermutaDeJsonParaArray();
		$intIdOpcaoInicialDePermuta = key($arrIdsOpcoesDePermutaDoUsuario);
		$intIdLocalAtual =  $arrIdsOpcoesDePermutaDoUsuario[$intIdOpcaoInicialDePermuta]['id_local_atual'];
		$arrCombinacoes = combinaOpcoesDePermutasRecursivamente($conexao, $arrIdsOpcoesDePermutaDoUsuario, $intGrausDePermuta);
		foreach ($arrCombinacoes as $strIdsOpcoes => $arrDadosOpcao) {
			if ($arrDadosOpcao['id_local_desejado'] == $intIdLocalAtual) :
				$arrIdsOpcoes = explode('-', $strIdsOpcoes);
				foreach ($arrIdsOpcoes as $intIdOpcao) :
					$arrPermutz[$strIdsOpcoes][$intIdOpcao] = $arrOpcoesValidas[$intIdOpcao];
				endforeach;
			endif;
		}
		return $arrPermutz;
	else :
		return false;
	endif;
}



function recuperaUsuarioByEmailStatusDeSql($conexao, $strEmailUsuario) {
	$querySelectUsuarioByEmail = "
		SELECT * FROM t_pmt_usuarios AS u 
		INNER JOIN t_pmt_cargos AS c ON (u.id_cargo = c.id_cargo) 
		WHERE ds_email_pessoal='{$strEmailUsuario}' AND st_usuario=1 ORDER BY id_usuario ASC LIMIT 1";
	// $querySelectUsuarioByEmail = mysqli_real_escape_string($conexao, $querySelectUsuarioByEmail);
	if ($result = mysqli_query($conexao, $querySelectUsuarioByEmail)) :
		// if (mysqli_num_rows($result)==1) :
			// $dadosUsuario = mysqli_fetch_array($result);

			while($linha = mysqli_fetch_array($result)) :
				$arrPerfilDoUsuario['id'] = $linha['id_usuario'];
				$arrPerfilDoUsuario['nome'] = $linha['nm_usuario'];
				$arrPerfilDoUsuario['id_cargo'] = $linha['id_cargo'];
				$arrPerfilDoUsuario['nm_cargo'] = $linha['nm_cargo'];
				$arrPerfilDoUsuario['telefone'] = $linha['nr_telefone'];
				$arrPerfilDoUsuario['email_pessoal'] = $linha['ds_email_pessoal'];
				$explodeEmail = explode('@', $linha['ds_email_tjsp']);
				// $arrPerfilDoUsuario['id_email'] = $explodeEmail[0];
				$arrPerfilDoUsuario['email_tjsp'] = $explodeEmail[0];
				// $arrPerfilDoUsuario['email_tjsp'] = $linha['ds_email_tjsp'];
				// $arrPerfilDoUsuario['local_atual'] = $linha['id_local_atual'].': '.$linha['nm_local_atual'];
				// $arrPerfilDoUsuario['local_origem_id'] = $linha['id_local_atual'];
				// $arrPerfilDoUsuario['local_origem_nome'] = $linha['nm_local_atual'];
				// $arrPerfilDoUsuario['posto'] = $linha['ds_posto'];
				$arrPerfilDoUsuario['md5'] = $linha['ds_md5'];
				// $arrPerfilDoUsuario['observacoes'] = $linha['ds_observacao'];
			endwhile;

			return $arrPerfilDoUsuario;
		// else :
		// 	return false;
		// endif;
	else :
		return false;
	endif;
}





function recuperaUsuarioByEmailSenha($conexao, $strEmailUsuario, $strSenhaUsuario) {
	$querySelectUsuarioByEmailSenha = "
		SELECT * FROM t_pmt_usuarios 
		WHERE ds_email_tjsp='{$strEmailUsuario}' AND ds_senha=md5('{$strSenhaUsuario}') 
		ORDER BY id_usuario ASC LIMIT 1";
	// $querySelectUsuarioByEmail = mysqli_real_escape_string($conexao, $querySelectUsuarioByEmail);

	if ($result = mysqli_query($conexao, $querySelectUsuarioByEmailSenha)) :
		
			while($linha = mysqli_fetch_array($result)) :
				$arrPerfilDoUsuario['id'] = $linha['id_usuario'];
				// $arrPerfilDoUsuario['nome'] = $linha['nm_usuario'];
				// $arrPerfilDoUsuario['id_cargo'] = $linha['id_cargo'];
				// $arrPerfilDoUsuario['nm_cargo'] = $linha['nm_cargo'];
				// $arrPerfilDoUsuario['telefone'] = $linha['nr_telefone'];
				// $arrPerfilDoUsuario['email_pessoal'] = $linha['ds_email_pessoal'];
				// $explodeEmail = explode('@', $linha['ds_email_tjsp']);
				// $arrPerfilDoUsuario['id_email'] = $explodeEmail[0];
				// $arrPerfilDoUsuario['email_tjsp'] = $explodeEmail[0];
				$arrPerfilDoUsuario['email_tjsp'] = $linha['ds_email_tjsp'];
				// $arrPerfilDoUsuario['local_atual'] = $linha['id_local_atual'].': '.$linha['nm_local_atual'];
				// $arrPerfilDoUsuario['local_origem_id'] = $linha['id_local_atual'];
				// $arrPerfilDoUsuario['local_origem_nome'] = $linha['nm_local_atual'];
				// $arrPerfilDoUsuario['posto'] = $linha['ds_posto'];
				$arrPerfilDoUsuario['md5'] = $linha['ds_md5'];
				// $arrPerfilDoUsuario['observacoes'] = $linha['ds_observacao'];
			endwhile;

			return $arrPerfilDoUsuario;
		// else :
		// 	return false;
		// endif;
	else :
		return false;
	endif;
}





function enviaLinkEdicaoPerfilPorEmail($arrDadosUsuario) {
	$arrDadosLink = array (
		'uid' => $arrDadosUsuario['id_usuario'],
		'mail' => $arrDadosUsuario['ds_email_pessoal'],
		'h' => $arrDadosUsuario['ds_md5'],
	);
	$strLink = URL_BASE.'perfil-edicao-form.php?'.http_build_query($arrDadosLink);

	$para = $arrDadosUsuario['ds_email_pessoal'];
	$assunto = "Link para edição de seu perfil Permutz";

	$msg = "<p>Olá!</p>";
	$msg .= "<p>Estamos disponibilizando o link abaixo, pra que você possa editar seu perfil Permutz:</p>";
	$msg .= "<p><a href=\"{$strLink}\">{$strLink}</a></p>";
	$msg .= "<p>Atenciosamente,</p>";
	$msg .= "<p>Equipe Permutz</p>";

	$remetente = "no-reply@renefb.info";

	if (enviaEmail($para, $assunto, $msg, $remetente, $remetente)) :
		return true;
	else :
		return false;
	endif;
}



function recuperaListaLocais($conexao) {
	$listaLocais = array();
	$querySelectLocais = "SELECT * FROM t_pmt_locais WHERE st_local=1 ORDER BY nm_local ASC";
	$result = mysqli_query($conexao, $querySelectLocais);
	while ($linha = mysqli_fetch_array($result)) :
		$listaLocais[$linha['id_local']] = $linha['nm_local'];
	endwhile;
	return $listaLocais;
}




function validaOpcoesDePermuta($idsLocaisDestinoAtuais, $idsLocaisDestinoAdicionar, $idsLocaisDestinoExcluir) {
	if (is_array($idsLocaisDestinoAtuais)) :
		$array1 = $idsLocaisDestinoAtuais;
		if (is_array($idsLocaisDestinoAdicionar)):
			$array1 = array_merge($idsLocaisDestinoAtuais, $idsLocaisDestinoAdicionar);
		endif;
		$array2 = $array1;
		if (is_array($idsLocaisDestinoExcluir)) :
			$array2 = array_diff($array1, $idsLocaisDestinoExcluir);
		endif;
	elseif (is_array($idsLocaisDestinoAdicionar)) :
		$array2 = $idsLocaisDestinoAdicionar;
		if (is_array($idsLocaisDestinoExcluir)) :
			$array2 = array_diff($idsLocaisDestinoAtuais, $idsLocaisDestinoExcluir);
		endif;
	else :
		$array2 = array();
	endif;

	return count($array2);
}



function excluiPerfil($conexao, $intIdUsuario) {
	$queryDeleteOpcoesByIdUsuario = "DELETE FROM t_pmt_opcoes_permuta WHERE id_usuario={$intIdUsuario}";
	$result1 = mysqli_query($conexao, $queryDeleteOpcoesByIdUsuario);

	$queryDeleteUsuarioById = "DELETE FROM t_pmt_usuarios WHERE id_usuario={$intIdUsuario}";
	$result2 = mysqli_query($conexao, $queryDeleteUsuarioById);

	if($result1 || $result2) :
		return true;
	else :
		return false;
	endif;
}







function enviaLinkAtivacaoEmMassa($conexao) {
	$querySelectUsuarios = "SELECT id_usuario, nm_usuario, ds_email_pessoal, ds_md5 FROM t_pmt_usuarios ORDER BY id_usuario ASC";
	$result = mysqli_query($conexao, $querySelectUsuarios);
	while ($arrDadosUsuario = mysqli_fetch_array($result)) :
		$arrDadosLink = array (
			'op' => 'edicao',
			'uid' => $arrDadosUsuario['id_usuario'],
			'nome' => $arrDadosUsuario['nm_usuario'],
			'mail' => $arrDadosUsuario['ds_email_pessoal'],
			'h' => $arrDadosUsuario['ds_md5'],
		);
		$strLink = URL_BASE.'perfil-operacao.php?'.http_build_query($arrDadosLink);

		$para = $arrDadosUsuario['ds_email_pessoal'];
		$assunto = "Link para reativação de seu perfil Permutz";

		$msg = "
			<style>
				p, li {font-family: Arial, Verdana, sans-serif; margin-top: 1.0em; margin-bottom: 1.0em}
			</style>
			<p>Olá, {$arrDadosUsuario['nm_usuario']}!</p>
			<p>Este e-mail é só pra avisar que, graças às sugestões e contribuições dos usuários, o Permutz voltou ao ar com algumas novidades:</p>
			<ul>
				<li>Os campos referentes aos locais de origem e destino agora exibem a lista de todas os locais disponíveis.</li>
				<li>O formulário de cadastro passa a contar com um campo de observações, pra que o usuário possa acrescentar quaisquer informações relevantes sobre seu perfil ou suas opções de permuta.</li>
				<li>Foi adicionado à tela de alteração do perfil um botão que permite a exclusão permanente dos dados do usuário.</li>
				<li>O processo de criação/alteração de perfil agora exibe uma tela de confirmação e validação dos dados do usuário antes do registro no banco de dados.</li>
				<li>Após concluir o cadastro, os novos usuários devem receber, por e-mail, uma mensagem de confirmação com o link para ativação do perfil.</li>
			</ul>
			<p>Além destes ajustes, foi preciso fazer uma limpeza do banco de dados, já que as falhas da versão anterior do sistema fizeram com que muitos perfis fossem duplicados ou gravados com dados inconsistentes. Por isso, os perfis duplicados ou inconsistentes foram removidos, de forma a não comprometer o mecanismo de busca de combinações de permutas.</p>
			<p><strong>Os demais perfis, como o seu, foram desativados e estão sendo contatados por e-mail para que acessem o link de ativação abaixo.</strong> Assim, os usuários já cadastrados terão a oportunidade de revisar seus dados e adicionar observações aos perfis, caso queiram.</p>
			<p><a href=\"{$strLink}\">{$strLink}</a></p>
			<p>Atenciosamente,</p>
			<p>Renê</p>
			";

		$remetente = "no-reply@renefb.info";

		if (AMBIENTE == 1) :
			enviaEmail($para, $assunto, $msg, $remetente, $remetente);
		else :
			echo "<p>De: {$remetente}</p>";
			echo "<p>Para: {$para}</p>";
			echo "<p>Assunto: {$assunto}</p>";
			echo "## ## ##";
			echo $msg;
			echo "<hr/>";
		endif;

	endwhile;



}



function zRecuperaUsuarioByEmailStatusDeSql($conexao, $strEmailUsuario, $strSenhaUsuario) {
	$querySelectUsuarioByEmail = "
		SELECT * FROM t_pmt_usuarios AS u 
		INNER JOIN t_pmt_cargos AS c ON (u.id_cargo = c.id_cargo) 
		WHERE ds_email_tjsp='{$strEmailUsuario}' 
		AND ds_senha = md5('{$strSenhaUsuario}') 
		AND st_usuario=1 ORDER BY id_usuario ASC LIMIT 1";
	// $querySelectUsuarioByEmail = mysqli_real_escape_string($conexao, $querySelectUsuarioByEmail);
	if ($result = mysqli_query($conexao, $querySelectUsuarioByEmail)) :
		// if (mysqli_num_rows($result)==1) :
			// $dadosUsuario = mysqli_fetch_array($result);

			while($linha = mysqli_fetch_array($result)) :
				$arrPerfilDoUsuario['id'] = $linha['id_usuario'];
				$arrPerfilDoUsuario['nome'] = $linha['nm_usuario'];
				$arrPerfilDoUsuario['id_cargo'] = $linha['id_cargo'];
				$arrPerfilDoUsuario['nm_cargo'] = $linha['nm_cargo'];
				$arrPerfilDoUsuario['telefone'] = $linha['nr_telefone'];
				$arrPerfilDoUsuario['email_pessoal'] = $linha['ds_email_pessoal'];
				$explodeEmail = explode('@', $linha['ds_email_tjsp']);
				// $arrPerfilDoUsuario['id_email'] = $explodeEmail[0];
				$arrPerfilDoUsuario['email_tjsp'] = $explodeEmail[0];
				// $arrPerfilDoUsuario['email_tjsp'] = $linha['ds_email_tjsp'];
				// $arrPerfilDoUsuario['local_atual'] = $linha['id_local_atual'].': '.$linha['nm_local_atual'];
				// $arrPerfilDoUsuario['local_origem_id'] = $linha['id_local_atual'];
				// $arrPerfilDoUsuario['local_origem_nome'] = $linha['nm_local_atual'];
				// $arrPerfilDoUsuario['posto'] = $linha['ds_posto'];
				$arrPerfilDoUsuario['md5'] = $linha['ds_md5'];
				// $arrPerfilDoUsuario['observacoes'] = $linha['ds_observacao'];
			endwhile;

			return $arrPerfilDoUsuario;
		// else :
		// 	return false;
		// endif;
	else :
		return false;
	endif;
}


function zGeraLinkOperacaoPerfil($arrDadosUsuario, $operacao) {
	$arrDadosLink = array (
		'op' => $operacao,
		'uid' => $arrDadosUsuario['id'],
		'mail' => $arrDadosUsuario['email_pessoal'],
		'h' => $arrDadosUsuario['md5'],
		);
	$strLink = URL_BASE.'perfil-operacao.php?'.http_build_query($arrDadosLink);
	return $strLink;
}






function recuperaUsuarioByEmailDeSql($conexao, $strEmailUsuario) {
	$querySelectUsuarioByEmail = "
		SELECT * FROM t_pmt_usuarios WHERE ds_email_tjsp='{$strEmailUsuario}' AND st_usuario=1 ORDER BY id_usuario ASC LIMIT 1";
	if ($result = mysqli_query($conexao, $querySelectUsuarioByEmail)) :
		$linha = mysqli_fetch_array($result);
		$arrPerfilDoUsuario['id'] = $linha['id_usuario'];
		$arrPerfilDoUsuario['nome'] = $linha['nm_usuario'];
		$arrPerfilDoUsuario['id_cargo'] = $linha['id_cargo'];
		$arrPerfilDoUsuario['telefone'] = $linha['nr_telefone'];
		$arrPerfilDoUsuario['email_pessoal'] = $linha['ds_email_pessoal'];
		$arrPerfilDoUsuario['email_tjsp'] = $linha['ds_email_tjsp'];
		$arrPerfilDoUsuario['local_origem_id'] = $linha['id_local_atual'];
		$arrPerfilDoUsuario['posto'] = $linha['ds_posto'];
		$arrPerfilDoUsuario['observacoes'] = $linha['ds_observacao'];
		return $arrPerfilDoUsuario;
	else :
		return false;
	endif;
}
