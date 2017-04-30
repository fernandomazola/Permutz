<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../core/css/estilos.css">
	<script type="text/javascript" charset="utf-8" src="../bootstrap/js/jquery.js"></script>
	<script type="text/javascript" charset="utf-8" src="../bootstrap/js/bootstrap.min.js"></script>
	
	<!-- favicon (icon exchange) by Pavel Pavlov from the Noun Project -->
	<!-- https://thenounproject.com/zka11/ -->
	<link rel="shortcut icon" type="image/x-icon" href="../core/img/favicon.ico">
	<title>ADMIN PERMUTZ</title>
</head>

<body>
	<nav class="navbar navbar-inverse">
		<div class="container-fluid col-sm-6 col-sm-offset-3">
			<!-- brand e collapsed-menu -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-default" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="../index.php" class="navbar-brand">
					<!-- custom class navbar-brand-logo (não pertence ao estilo padrão do bootstrap) -->
					<span class="navbar-brand-logo">
						<span class="sr-only">PERMUTZ</span>
					</span>					
				</a>
			</div>
			<!-- nav links -->
			<div class="collapse navbar-collapse" id="navbar-collapse-default">
				<ul class="nav navbar-nav">
					<li><a href="admin-painel.php">Painel</a></li>
					<li><a href="gestao-locais-lista.php">Locais</a></li>
					<li><a href="gestao-usuarios-lista.php">Usuários</a></li>
					<li><a class="bg-primary" href="index.php?admin-exit=true">Sair</a></li>>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container col-sm-6 col-sm-offset-3">