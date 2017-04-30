<?php
/*
 este é o formulário padrão utilizado na criação e edição de perfis
 */

session_start();
session_destroy();
require_once("core/inc/conex.inc.php");
require_once("core/inc/funcoes.inc.php");

/*
 definindo atributos defaul da página, caso nenhum outro seja setado na sessão
 */
$paginaDefault = array(
	'titulo' => 'Criar perfil',
	'classe-contexto' => 'bg-default',
	);
$pagina = isset($_SESSION['pagina']) ? $_SESSION['pagina'] : $paginaDefault;

if (isset($_POST['usuario-id'])) :
	$pagina['titulo'] = 'Alterar perfil';
endif;

/*
 setando os atributos de validação do formulário, caso nenhum outro seja setado na sessão 
 */
$formValidacaoDefault = array(
	'usuario-nome-validacao' => '',
	'usuario-telefone-validacao' => '',
	'usuario-email-pessoal-validacao' => '',
	'usuario-email-tjsp-validacao' => '',
	'usuario-senha-validacao' => '',
	'local-origem-id-validacao' => '',
	'usuario-posto-validacao' => '',
	'locais-destino-id-validacao' => '',
	);
$formValidacao = isset($_SESSION['form-validacao']) ? $_SESSION['form-validacao'] : $formValidacaoDefault;

/*
 Definindo atributos default do perfil, caso não exista nenhum setado na sessão.
 Caso o perfil esteja sendo criado, o formulário é criado sem nenhum valor preenchido.
 Em caso de edição, os campos já devem conter os dados do perfil
 */
$usuarioDefault = array(
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
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : $usuarioDefault;


/*
 recuperando a lista de locais cadastrados no banco de dados
 */
$listaLocais = recuperaListaLocais($conexao);

/*
 html do cabeçalho
 */
require_once('core/tpl/cabecalho.tpl.php');

?>
	
	<h1 class="text-center bg <?= $pagina['classe-contexto'] ?>"><?= $pagina['titulo'] ?></h1>
	<form class="" id="form-perfil" name="form-perfil" method="post" action="perfil-confirmacao.php">
		
		<input type="hidden" class="" id="usuario-id" name="usuario-id" value="<?= $usuario['id'] ?>" />
		
		<!-- dados de contato -->
		<fieldset>
			
			<legend>Dados de contato</legend>
			
			<!-- NOME -->
			<div class="form-group <?= $formValidacao['usuario-nome-validacao'] ?>">
				<label class="control-label" for="usuario-nome">Nome Completo</label>
				<input type="text" class="form-control" id="usuario-nome" name="usuario-nome" value="<?= $usuario['nome'] ?>" placeholder="Seu nome completo facilita o contato dos interessados">
			</div>

			<!-- TELEFONE -->
			<div class="form-group <?= $formValidacao['usuario-telefone-validacao'] ?>">
				<label class="control-label" for="usuario-telefone">Telefone (com DDD)</label>
				<input type="text" class="form-control" id="usuario-telefone" name="usuario-telefone" value="<?= $usuario['telefone'] ?>" placeholder="11900001111 (somente números)">
			</div>

			<!-- E-MAIL PESSOAL -->
			<div class="form-group <?= $formValidacao['usuario-email-pessoal-validacao'] ?>">
				<label class="control-label" for="usuario-email-pessoal">E-mail pessoal</label>
				<input type="email" class="form-control" id="usuario-email-pessoal" name="usuario-email-pessoal" value="<?= $usuario['email_pessoal'] ?>" placeholder="seu e-mail pessoal">
			</div>
			<br><br><br>

		</fieldset>



		<!-- dados de acesso -->
		<fieldset>
			
			<legend>Dados de acesso</legend>

			<!-- EMAIL TJSP -->
			<div class=" <?= $formValidacao['usuario-email-tjsp-validacao'] ?>">
				<label class="control-label" for="usuario-email-tjsp">E-mail do TJSP</label>
			</div>
				<div class="input-group <?= $formValidacao['usuario-email-tjsp-validacao'] ?>">
					<input type="text" class="form-control" id="usuario-email-tjsp" name="usuario-email-tjsp" value="<?= $usuario['email_tjsp'] ?>" placeholder="seu e-mail tjsp.jus.br" aria-describedby="span-dominio-email-tjsp">
					<span class="input-group-addon" id="span-dominio-email-tjsp">@ tjsp.jus.br</span>
				</div>
				<span id="helpBlock" class="help-block text-justify">
					recomendamos que adicione o e-mail <strong>no-reply@permutz.renefb.info</strong> à lista de remetentes e destinatários confiáveis em seu e-mail do TJSP. clique <a href="https://correio.tjsp.jus.br/ecp/?rfr=owa" target="_blank">aqui</a> para acessar as configurações do seu e-mail do TJSP (procure pelo menu <em>Bloquear ou Permitir</em>).
				</span>
			<br>

			<!-- SENHA -->
			<div class="form-group <?= $formValidacao['usuario-senha-validacao'] ?>">
				<label class="control-label" for="usuario-senha1">Senha</label>
				<input type="password" class="form-control" id="usuario-senha1" name="usuario-senha1" value="<?= $usuario['senha'] ?>" placeholder="senha">
			</div>
			<br>

			<!-- CONFIRMA SENHA -->
			<div class="form-group <?= $formValidacao['usuario-senha-validacao'] ?>">
				<label class="control-label" for="usuario-senha2">Repita a senha</label>
				<input type="password" class="form-control" id="usuario-senha2" name="usuario-senha2" value="<?= $usuario['senha'] ?>" placeholder="repita a senha">
				<span id="helpBlock" class="help-block">sua senha deve ter, pelo menos, 4 caracteres</span>
			</div>

		</fieldset>
		<br><br><br>

			
		<!-- dados de lotação -->
		<fieldset>

			<legend>Dados de lotação</legend>
				
			<!-- CARGO -->
			<div class="form-group">
				<label for="usuario-cargo-id">Cargo</label>
				<!-- <input type="text" class="form-control" id="usuario-cargo-id" name="usuario-cargo-id" value="Escrevente" placeholder="Seu cargo" readonly> -->
				<select class="form-control" id="usuario-cargo-id" name="usuario-cargo-id" readonly>
					<option value="2" selected>Escrevente</option>
				</select>
			</div>

			<!-- LOCAL DE ORIGEM -->
			<div class="form-group  <?= $formValidacao['local-origem-id-validacao'] ?>">
				<label class="control-label" for="local_origem_id">Local de Origem</label>
				<select class="form-control" id="opcao-origem" name="local-origem-id" placeholder="Escolha na lista">
					<option value=""></option>
					<?php
						foreach ($listaLocais as $idLocal => $nomeLocal) {
							$idLocal = intval($idLocal);
							$option = "<option value=\"{$idLocal}\" ";
							if (isset($usuario['local_origem_id']) && $usuario['local_origem_id']==$idLocal) :
								$option .= "selected";
							endif;
							$option .= ">{$nomeLocal}</option>";
							echo $option;
						}
					?>
				</select>
				<input type="hidden" class="" id="local-origem-nome" name="local-origem-nome" value="<?= $usuario['local_origem_nome'] ?>" />
			</div>

			<!-- UNIDADE DE TRABALHO -->
			<div class="form-group <?= $formValidacao['usuario-posto-validacao'] ?>">
				<label class="control-label" for="usuario-posto">Unidade de Trabalho</label>
				<input type="text" class="form-control" id="usuario-posto" name="usuario-posto" value="<?= $usuario['posto'] ?>" placeholder="Unidade de Trabalho">
				<span id="helpBlock" class="help-block">(a mesma que consta de seu <a href="http://www.tjsp.jus.br/frequencia/Login.aspx">Módulo de Frequência</a>)</span>
			</div>

		</fieldset>
		<br><br><br>

		
		<!-- opções de permuta -->
		<fieldset>

			<legend>Opções de permuta</legend>

			<!-- INPUT LOCAIS DESTINO -->
			<div class="form-group <?= $formValidacao['locais-destino-id-validacao'] ?>">
				<label class="sr-only" for="opcao-destino">Adicionar opções de permuta</label>
				<div class="input-group">
					<select class="form-control" id="opcao-destino" name="opcao-destino" placeholder="Escolha na lista">
						<option></option>
						<?php
							foreach ($listaLocais as $idLocal => $nomeLocal) :
								echo "<option value=\"{$idLocal}\">{$nomeLocal}</option>";
							endforeach;
						?>
					</select>

					<span class="input-group-btn">
						<button type="button" class="btn btn-primary" id="btn-adicionar-destino">Adicionar à lista</button>
					</span>
				</div>
				<span id="helpBlock" class="help-block">Caso encontre problemas na lista de locais disponíveis, entre em contato através do e-mail <a href="mailto:contato@permutz.renefb.info">contato@permutz.renefb.info</a>.</span>
			</div>
			

			<div class="hidden" id="destinos-adicionar">destinos adicionar
				<?php 
				if (isset($usuario['locais_desejados_adicionar']) && is_array($usuario['locais_desejados_adicionar'])) :
					foreach ($usuario['locais_desejados_adicionar'] as $i=>$local) :
						$localDados = explode(': ', $local);
				?>
						<input type="hidden" name="locais-desejados-adicionar[]" id="local-adicionar-<?= $localDados[0] ?>" value="<?= $local ?>" />
				<?php
					endforeach; 
				endif;
				?>
			</div>
			

			<div class="hidden" id="destinos-excluir">destinos excluir
				<?php 
				if (isset($usuario['locais_desejados_excluir']) && is_array($usuario['locais_desejados_excluir'])) :
					foreach ($usuario['locais_desejados_excluir'] as $i=>$local) :
						$localDados = explode(': ', $local);
				?>
						<input type="hidden" name="locais-desejados-excluir[]" id="local-excluir-<?= $localDados[0] ?>" value="<?= $local ?>" />
				<?php
					endforeach; 
				endif;
				?>
			</div>

			<div class="hidden" id="destinos-existentes">destinos existentes
				<?php 
				if (isset($usuario['locais_desejados_cadastrados']) && is_array($usuario['locais_desejados_cadastrados'])) :
					foreach ($usuario['locais_desejados_cadastrados'] as $i=>$local) :
						$localDados = explode(': ', $local);
				?>
						<input type="hidden" name="locais-desejados-cadastrados[]" id="local-cadastrado-<?= $localDados[0] ?>" value="<?= $local ?>" />
				<?php
					endforeach; 
				endif;
				?>
			</div>
			
			
			<!-- TABLE LOCAIS DESTINO -->
			<table class="table table-striped">
				<tbody>
					<!-- aqui são criadas dinamicamente as linhas/colunas com as opções de permuta -->
					<?php 
					if (is_array($usuario['locais_desejados_cadastrados'])) :
						foreach ($usuario['locais_desejados_cadastrados'] as $i=>$local) :
							$localDados = explode(': ', $local); 
					?>
						<tr id="linha-local-<?= $localDados[0] ?>">
							<td><?= $localDados[1] ?></td>
							<td>
								<a class="btn btn-danger pull-right" role="button" onclick="excluiDestino(<?= $localDados[0] ?>, '<?= $localDados[1] ?>');">excluir</a>
							</td>
						</tr>
					<?php
						endforeach;
					endif;
					?>
					<!--  -->
					<?php 
					if (is_array($usuario['locais_desejados_adicionar'])) :
						foreach ($usuario['locais_desejados_adicionar'] as $i=>$local) :
							$localDados = explode(': ', $local);
					?>
						<tr id="linha-local-<?= $localDados[0] ?>">
							<td><?= $localDados[1] ?></td>
							<td>
								<a class="btn btn-danger pull-right" role="button" onclick="excluiDestino(<?= $localDados[0] ?>, '<?= $localDados[1] ?>');">excluir</a>
							</td>
						</tr>
					<?php
						endforeach;
					endif;
					?>
				</tbody>
			</table>

		</fieldset>
		<br><br><br>



		<fieldset>
			
			<legend>Observações</legend>
			
			<!-- OBSERVAÇÕES -->
			<textarea class="form-control" name="usuario-observacoes" id="usuario-observacoes" rows="3" placeholder="Informações adicionais sobre dados de lotação ou opções de permuta"><?= $usuario['observacoes'] ?></textarea>
		
		</fieldset>
		<br><br>

		<!-- BOTÕES -->
		<?php

			if($usuario['id']>0) :
				echo '<button type="submit" class="btn btn-primary btn-lg btn-block" name="btn-perfil-form-submit" value="alterar">Alterar Perfil</button><br/>';
				echo '<button type="submit" class="btn btn-danger btn-lg btn-block" name="btn-perfil-form-submit" value="excluir">Excluir Perfil</button><br/>';
			else :
				echo '<button type="submit" class="btn btn-success btn-lg btn-block" name="btn-perfil-form-submit" value="criar">Criar Perfil</button><br/>';
			endif;

		?>

	</form>


	<script type="text/javascript" charset="utf-8" src="core/js/funcoes.js"></script>
	<script>
		/* desabilitando o submit através do botão enter: */
		$('#form-perfil input').on('keypress', function(e) {
		    return e.which !== 13;
		});
	</script>

<?php

/*
 html do rodapé
 */
require_once('core/tpl/rodape.tpl.php');