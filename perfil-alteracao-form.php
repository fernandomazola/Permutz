<?php require_once("core/tpl/cabecalho.tpl.php"); ?>
		

		<!-- <script type="text/javascript" charset="utf-8" src="bootstrap/js/bootstrap-typeahead.js"></script> -->
		<script type="text/javascript" charset="utf-8" src="core/js/funcoes.js"></script>

		<h1 class="text-center">Quer alterar seu perfil Permutz?<br>&nbsp;</h1><br>
		<form class="" id="form-requisicao" name="form-requisicao" method="post" action="perfil-script.php">
			
			<!-- dados de contato -->
			<fieldset>
				<legend>Informe seus dados de acesso</legend>
				
				<!-- E-MAIL -->
				<label for="input-usuario-email-tjsp">E-mail do TJSP</label>
 				<div class="input-group">
 					<input type="text" class="form-control" id="input-usuario-email-tjsp" name="input-usuario-email-tjsp" value="" placeholder="seu e-mail tjsp.jus.br" aria-describedby="span-dominio-email-tjsp">
 					<span class="input-group-addon" id="span-dominio-email-tjsp">@ tjsp.jus.br</span>
 				</div>
 				<br>
 				
 				<!-- SENHA -->
 				<div class="form-group">
 					<label for="usuario-senha">Senha</label>
 					<input type="password" class="form-control" id="usuario-senha" name="usuario-senha" value="" placeholder="senha">
 				</div>
 				
 				<br>
 			
				<input type="submit" class="btn btn-primary btn-lg btn-block" id="" name="btn-perfil-alteracao" value="Enviar">
			</fieldset>
		</form>

		<br>

		<div class="coml-md-12">
			<p class="text-right">esqueceu sua senha? clique <a href="recuperar-senha.php">aqui</a></p>
		</div>

<?php require_once("core/tpl/rodape.tpl.php"); ?>