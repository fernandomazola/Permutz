<?php require_once("core/tpl/cabecalho.tpl.php"); ?>
		

		<!-- <script type="text/javascript" charset="utf-8" src="bootstrap/js/bootstrap-typeahead.js"></script> -->
		<script type="text/javascript" charset="utf-8" src="core/js/funcoes.js"></script>

		<h1 class="text-center">Esqueceu a senha do seu perfil Permutz?<br>&nbsp;</h1><br>
		<form class="" id="form-requisicao" name="form-requisicao" method="post" action="perfil-script.php">
			
			<!-- dados de contato -->
			<fieldset>
				<legend>Informe o e-mail associado a seu perfil</legend>
				
				<!-- E-MAIL -->
				<label for="input-usuario-email-tjsp">E-mail do TJSP</label>
 				<div class="input-group">
 					<input type="text" class="form-control" id="input-usuario-email-tjsp" name="input-usuario-email-tjsp" value="" placeholder="seu e-mail tjsp.jus.br" aria-describedby="span-dominio-email-tjsp">
 					<span class="input-group-addon" id="span-dominio-email-tjsp">@ tjsp.jus.br</span>
 				</div>
 				<br>
 				
 				<input type="submit" class="btn btn-danger btn-lg btn-block" id="" name="btn-perfil-recuperar" value="Recuperar Senha">
			</fieldset>
		</form>

<?php require_once("core/tpl/rodape.tpl.php"); ?>