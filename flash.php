<?php

session_start();
if ($_SESSION['flash']) :
	
?>

<?php require_once("core/tpl/cabecalho.tpl.php"); ?>
	<h1 class="text-center"><?= $_SESSION['flash']['titulo'] ?></h1>
	<hr>
	
	<div class="bs-callout bs-callout-<?= $_SESSION['flash']['classe-contexto'] ?>">
		<h3 class="text-center"><?= $_SESSION['flash']['msg'] ?></h3>
		<?= $_SESSION['flash']['info-adicional'] ?>
	</div>
<?php require_once("core/tpl/rodape.tpl.php"); ?>

<?php
	
	unset($_SESSION['flash']);

else:

	header("Location: index.php");
	die();

endif;