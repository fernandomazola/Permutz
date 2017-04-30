<?php

session_start();
require_once("core/inc/conex.inc.php");
require_once("core/inc/funcoes.inc.php");

if(isset($_SESSION['usuario'])):
	unset($_SESSION['usuario']);
endif;

if(isset($_SESSION['form-validacao'])):
	unset($_SESSION['form-validacao']);
endif;

if(isset($_SESSION['pagina'])):
	unset($_SESSION['pagina']);
endif;

if(isset($_POST['btn-perfil-form-submit'])) :
	$op;
	$botao = array(
		'value' => $_POST['btn-perfil-form-submit'],
		'classe-contexto' => '',
		'display-contexto' => '',
		);
	switch ($_POST['btn-perfil-form-submit']) :
		case 'criar':
			$op = 1;
			$_POST['locais-desejados-cadastrados'] = null;
			$_POST['locais-desejados-excluir'] = null;
			$pagina = array(
					'titulo' => 'Confirma a criação de seu perfil?',
					'classe-contexto' => 'bg-default',
				);
			$botao['classe-contexto'] = 'btn-success';
			$botao['display-contexto'] = 'Criar Perfil';
			break;
		case 'alterar':
			$op = 2;
			// $_POST['locais-desejados-cadastrados'] = null;
			// $_POST['locais-desejados-excluir'] = null;
			$pagina = array(
					'titulo' => 'Confirma a alteração de seu perfil?',
					'classe-contexto' => 'bg-primary',
				);
			$botao['classe-contexto'] = 'btn-primary';
			$botao['display-contexto'] = 'Alterar Perfil';
			break;
		case 'excluir':
			$op = 3;
			// $_POST['locais-desejados-cadastrados'] = null;
			$_POST['locais-desejados-adicionar'] = null;
			$_POST['locais-desejados-excluir'] = null;
			$pagina = array(
					'titulo' => 'Confirma a exclusão de seu perfil?',
					'classe-contexto' => 'bg-danger',
				);
			$botao['classe-contexto'] = 'btn-danger';
			$botao['display-contexto'] = 'Excluir Perfil';
			break;
		default:
			$op = 1;
			$_POST['locais-desejados-cadastrados'] = null;
			$_POST['locais-desejados-excluir'] = null;
			$pagina = array(
					'titulo' => 'Confirma a criação de seu perfil?',
					'classe-contexto' => 'bg-default',
				);
			$botao['classe-contexto'] = 'btn-success';
			$botao['display-contexto'] = 'Criar Perfil';
			break;
	endswitch;


	require_once('core/tpl/cabecalho.tpl.php');
?>
	<h1 class="text-center bg <?= $pagina['classe-contexto'] ?>" style="height: 2.0em;line-height: 2.0em; border-radius: 10px"><?= $pagina['titulo'] ?></h1>
	<form class="" id="form-perfil" name="form-perfil" method="post" action="">
		
		<!-- ID -->
		<?php if (isset($_POST['usuario-id'])) : ?>
			<input type="hidden" class="" id="usuario-id" name="usuario-id" value="<?= $_POST['usuario-id'] ?>" />
		<?php endif; ?>
		
		<fieldset>
			
			<legend>Dados de contato</legend>
			
			<!-- NOME -->
			<div class="form-group">
				<label for="usuario-nome">Nome</label>
				<input type="text" class="form-control" id="usuario-nome" name="usuario-nome" value="<?= $_POST['usuario-nome'] ?>" readonly>
			</div>

			<!-- TELEFONE -->
			<div class="form-group">
				<label for="usuario-telefone">Telefone (com DDD)</label>
				<input type="text" class="form-control" id="usuario-telefone" name="usuario-telefone" value="<?= $_POST['usuario-telefone'] ?>" readonly>
			</div>

			<!-- E-MAIL PESSOAL -->
			<div class="form-group">
				<label for="usuario-email-pessoal">E-mail pessoal</label>
				<input type="text" class="form-control" id="usuario-email-pessoal" name="usuario-email-pessoal" value="<?= $_POST['usuario-email-pessoal'] ?>" readonly>
			</div>

		</fieldset>
		<br><br><br>

		<fieldset>
			
			<legend>Dados de contato</legend>
			
			<!-- E-MAIL TJSP -->
			<label for="usuario-email-id">E-mail do TJSP</label>
			<div class="input-group">
				<input type="text" class="form-control" id="usuario-email-tjsp" name="usuario-email-tjsp" value="<?= $_POST['usuario-email-tjsp'] ?>" aria-describedby="span-dominio-email-tjsp" readonly>
				<span class="input-group-addon" id="span-dominio-email-tjsp">@ tjsp.jus.br</span>
			</div>
			<br>

			<!-- SENHA -->
			<div class="form-group">
				<label for="usuario-senha1">Senha</label>
				<input type="password" class="form-control" id="usuario-senha1" name="usuario-senha1" value="<?= $_POST['usuario-senha1'] ?>" readonly>
			</div>

			<!-- CONFIRMAÇÃO DE SENHA -->
			<div class="form-group">
				<label for="usuario-senha2">Confirmação de senha</label>
				<input type="password" class="form-control" id="usuario-senha2" name="usuario-senha2" value="<?= $_POST['usuario-senha2'] ?>" readonly>
			</div>

		</fieldset>
		<br><br><br>
			
	<!-- dados de lotação -->
		<fieldset>
			
			<legend>Dados de lotação</legend>
			
			<!-- CARGO -->
			<div class="form-group">
				<input type="hidden" id="usuario-cargo-id" name="usuario-cargo-id" value="<?= $_POST['usuario-cargo-id'] ?>">
				<label for="usuario-cargo-nome">Cargo</label>
				<input type="text" class="form-control" id="usuario-cargo-nome" name="usuario-cargo-nome" value="Escrevente" readonly>
			</div>

			<!-- LOCAL DE ORIGEM -->
			<div class="form-group">
				<label for="local_origem_id">Local de Origem</label>
				<input type="hidden" id="local-origem-id" name="local-origem-id" value="<?= $_POST['local-origem-id'] ?>" />
				<input type="text" class="form-control" id="local-origem-nome" name="local-origem-nome" value="<?= $_POST['local-origem-nome'] ?>" readonly />
			</div>

			<!-- UNIDADE DE TRABALHO -->
			<div class="form-group">
				<label for="usuario-posto">Unidade de Trabalho</label>
				<input type="text" class="form-control" id="usuario-posto" name="usuario-posto" value="<?= $_POST['usuario-posto'] ?>" readonly />
			</div>
		</fieldset>
		<br><br><br>
		
		
		<!-- opções de permuta -->
		<fieldset>

			<legend>Opções de permuta</legend>
			
			<div class="form-group" id="destinos-existentes">
				
			<!-- TABLE LOCAIS EXISTENTES -->
			<?php if (isset($_POST['locais-desejados-cadastrados']) && is_array($_POST['locais-desejados-cadastrados'])) : ?>
				
				<!-- 1. LOCAIS DESEJADOS JÁ ASSOCIADOS AO PERFIL -->
				<table class="table table-striped">
					<thead>
						<tr><th>Destinos já associados ao perfil:</th></tr>
					</thead>
					<tbody>
						<?php
							foreach ($_POST['locais-desejados-cadastrados'] as $local) :
								$localDados = explode(': ', $local);
						?>
								<tr>
									<td>
										<input type="hidden" name="locais-desejados-cadastrados[]" id="local-id-<?= $localDados[0] ?>" value="<?= $local ?>" />
										<span><?= $localDados[1] ?></span>
									</td>
								</tr>
						<?php
							endforeach;
						?>
					</tbody>
				</table>
			
			<?php endif; ?>



			<!-- TABLE LOCAIS ADICIONAR -->
			<?php if (isset($_POST['locais-desejados-adicionar']) && is_array($_POST['locais-desejados-adicionar'])) : ?>
				
				<!-- 2. LOCAIS P/A ADICIONAR AO PERFIL -->
				<table class="table table-striped">
					<thead>
						<tr><th>Destinos a acrescentar:</th></tr>
					</thead>
					<tbody>
						<?php
							foreach ($_POST['locais-desejados-adicionar'] as $local) :
								$localDados = explode(': ', $local);
						?>
								<tr>
									<td>
										<input type="hidden" name="locais-desejados-adicionar[]" id="local-id-<?= $localDados[0] ?>" value="<?= $local ?>" />
										<span><?= $localDados[1] ?></span>
									</td>
								</tr>
						<?php
							endforeach;
						?>
					</tbody>
				</table>
			
			<?php endif; ?>


			

			<!-- TABLE LOCAIS EXCLUIR -->
			<?php if (isset($_POST['locais-desejados-excluir']) && is_array($_POST['locais-desejados-excluir'])) : ?>
				
				<!-- 3. LOCAIS A EXCLUIR AO PERFIL -->
				<table class="table table-striped">
					<thead>
						<tr><th>Destinos a excluir:</th></tr>
					</thead>
					<tbody>
						<?php
							foreach ($_POST['locais-desejados-excluir'] as $local) :
								$localDados = explode(': ', $local);
						?>
								<tr>
									<td>
										<input type="hidden" name="locais-desejados-excluir[]" id="local-id-<?= $localDados[0] ?>" value="<?= $local ?>" />
										<span><?= $localDados[1] ?></span>
									</td>
								</tr>
						<?php
							endforeach;
						?>
					</tbody>
				</table>
			
			<?php endif; ?>
			
		</fieldset>
		<br><br>
		
		
		<?php $_POST['usuario-observacoes'] = trim($_POST['usuario-observacoes']); ?>

		<!-- OBSERVAÇÕES -->
		<?php if(strlen($_POST['usuario-observacoes'])>2) : ?>
			<fieldset>
				<legend>Observações</legend>
				<textarea class="form-control" name="usuario-observacoes" id="usuario-observacoes" rows="3" readonly><?= $_POST['usuario-observacoes'] ?></textarea>
			</fieldset>
		<?php endif; ?>
		<br><br>


		<!-- BOTÕES  -->
		<button type="button" class="btn btn-default btn-lg btn-block" id="btn-voltar" name="btn-voltar">Voltar</button><br/>
		<button type="submit" class="btn <?= $botao['classe-contexto'] ?> btn-lg btn-block" id="btn-perfil-form-confirm" name="btn-perfil-form-op" value="<?= $botao['value'] ?>"><?= $botao['display-contexto'] ?></button>


	</form>


	<script type="text/javascript" charset="utf-8" src="core/js/funcoes.js"></script>
	<script>
		/* desabilitando o submit através do botão enter: */
		$('#form-perfil input').on('keypress', function(e) {
		    return e.which !== 13;
		});
		/* */
		$('#btn-voltar').click(function(){
			$('#form-perfil').attr('action', 'perfil-form.php');
			$('#form-perfil').submit();
		});
		/* */
		$('#btn-perfil-form-confirm').click(function(){
			var op = $('#btn-perfil-form-confirm').val();
			// var script = 'perfil-script.php?'
			$('#form-perfil').attr('action', 'perfil-script.php');
			$('#form-perfil').submit();
		});
	</script>

	<?php 

	require_once('core/tpl/rodape.tpl.php');

else:
	header("location:index.php");
endif;