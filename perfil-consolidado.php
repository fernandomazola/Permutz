<?php 
	session_start();
	require_once("core/inc/funcoes.inc.php");
	require_once("core/tpl/cabecalho.tpl.php");
	

	if (isset($_SESSION['perfilPermutz']) && count($_SESSION['perfilPermutz'])>0) :
?>
		

		<h1 class="text-center">Permutz consolidado com sucesso!</h1>
		<hr>
		
		<div class="bs-callout bs-callout-primary">
			<h2>Este é seu perfil Permutz:</h2>
			<dl>
				<dt class="text-lowercase">Nome:</dt>
				<dd><?=$_SESSION['perfilPermutz']['nm_usuario']?></dd>

				<dt class="text-lowercase">Cargo:</dt>
				<dd><?=$_SESSION['perfilPermutz']['nm_cargo']?></dd>
				
				<dt class="text-lowercase">Local atual:</dt>
				<dd><?=$_SESSION['perfilPermutz']['nm_local_atual']?></dd>
				
				<dt class="text-lowercase">Posto de trabalho atual:</dt>
				<dd><?=$_SESSION['perfilPermutz']['ds_posto']?></dd>
				
				<dt class="text-lowercase">E-mail institucional:</dt>
				<dd><?=$_SESSION['perfilPermutz']['ds_email_tjsp']?></dd>
				
				<dt class="text-lowercase">Telefone para contato:</dt>
				<dd><?=$_SESSION['perfilPermutz']['nr_telefone']?></dd>
				
				<dt class="text-lowercase">Locais desejados para permuta:</dt>
				<dd>
					<ul class="list-unstyled">
						<?php
							foreach ($_SESSION['perfilPermutz']['ids_opcoes_permuta'] as $intIdOpcao => $arrDadosOpcao) :
						?>
								<li><?=$arrDadosOpcao['nm_local_desejado']?></li>
						<?php
							endforeach;
						?>
					</ul>
				</dd>

				<dt class="text-lowercase">observacoes:</dt>
				<dd><?=$_SESSION['perfilPermutz']['ds_observacao']?></dd>

			</dl>
		</div>

		<?php if (isset($_SESSION['combinacoesPermutz']) && count($_SESSION['combinacoesPermutz'])>0) : ?>
				
				<div class="bs-callout bs-callout-success">
					<h3 class="text-center">Legal!!!</h3>
					<h3 class="text-center">Nosso super power combinator tabajara encontrou outros permutz que combinam com o seu:</h3>
					<?php
						$i=0;
						foreach ($_SESSION['combinacoesPermutz'] as $idCombinacao => $dadosCombinacao) :
							$i++;
					?>
							<div class="panel panel-success">
							<!-- <div class="panel-heading">Combinação #<?=$idCombinacao;?>:</div> -->
							<div class="panel-heading">Combinação #<?=$i;?></div>
								<div class="panel-body">
									<ul class="list-unstyled list-permutz">

									<?php foreach ($dadosCombinacao as $idOpcao => $dadosOpcao) : ?>
											
											<br>
											<li>
												<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
													<span class="text-uppercase">
														<strong><?=$dadosOpcao['nm_usuario'];?></strong>
														(<?=$dadosOpcao['nm_cargo'];?>)
													</span>
													<br>
												<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
													<a href="mailto:<?=$dadosOpcao['ds_email_tjsp'];?>">
														<?=$dadosOpcao['ds_email_tjsp'];?>
													</a>
													<br/>
												<span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
													<?=$dadosOpcao['nr_telefone'];?>
													<br/>
												<span class="glyphicon glyphicon-home" aria-hidden="true"></span>
													de <?=$dadosOpcao['nm_local_atual'];?>
													(<?=$dadosOpcao['ds_posto'];?>)
													<br/>
												<span class="glyphicon glyphicon-plane" aria-hidden="true"></span>
													para <?=$dadosOpcao['nm_local_desejado'];?>
													<br/>
												<span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>
													<span class="text-lowercase"><?=$dadosOpcao['ds_observacao'];?></span>													
											</li>
											<br>
									
									<?php endforeach; ?>
									
									</ul>
								</div>
							</div>
					<?php
						endforeach;	
					?>

				</div>
		
		<?php else: ?>
		
				<div class="bs-callout bs-callout-default">
					<h3 class="text-center">Que pena...</h3>
					<h3 class="text-center">Nosso combinator tabajara <span class="text-uppercase"><strong>ainda</strong></span> não encontrou nenhum permutz que combine com o seu</h3>
					<h4 class="text-center">Ajude a divulgar a ferramenta, pra que mais interessados se cadastrem e sua permuta se realize logo!</h4>
				</div>
		
		<?php endif; ?>

<?php
	else :
		header("Location:index.php");
	endif;
?>

<?php require_once("core/tpl/rodape.tpl.php"); ?>