<?php

session_start();
require_once('../core/inc/conex.inc.php');

if ($_SESSION['admin']) :

	if (isset($_GET['criterio']) && $_GET['criterio']=='id_usuario') :

		$ordem_nm_inversa = 'ASC';

		if (isset($_GET['ordem'])) :
			switch ($_GET['ordem']) :
				case 'ASC':
					$ordem_id = 'ASC';
					$ordem_id_inversa = 'DESC';
					break;
				case 'DESC':
					$ordem_id = 'DESC';
					$ordem_id_inversa = 'ASC';
					break;
				default:
					$ordem_id = 'ASC';
					$ordem_id_inversa = 'DESC';
					break;
			endswitch;
		else :
			$ordem_id = 'ASC';
			$ordem_id_inversa = 'DESC';
		endif;
		
		$query = "SELECT * FROM t_pmt_usuarios ORDER BY id_usuario {$ordem_id}";

	elseif (isset($_GET['criterio']) && $_GET['criterio']=='nm_usuario') :

		$ordem_id_inversa = 'ASC';

		if (isset($_GET['ordem'])) :
			switch ($_GET['ordem']) :
				case 'ASC':
					$ordem_nm = 'ASC';
					$ordem_nm_inversa = 'DESC';
					break;
				case 'DESC':
					$ordem_nm = 'DESC';
					$ordem_nm_inversa = 'ASC';
					break;
				default:
					$ordem_nm = 'ASC';
					$ordem_nm_inversa = 'DESC';
					break;
			endswitch;
		else :
			$ordem_nm = 'ASC';
			$ordem_nm_inversa = 'DESC';
		endif;

		$query = "SELECT * FROM t_pmt_usuarios ORDER BY nm_usuario {$ordem_nm}";

	else :
		
		$ordem_id_inversa = 'ASC';
		$ordem_nm_inversa = 'DESC';
		$query = "SELECT * FROM t_pmt_usuarios ORDER BY id_usuario DESC";
	
	endif;
		

	$lista_usuarios = array();

	$result = mysqli_query($conexao, $query);

	while ($linha = mysqli_fetch_assoc($result)) :
		array_push($lista_usuarios, $linha);
	endwhile;

	require_once('admin-cabecalho.php');

?>

	<h1 class="text-center">PAINEL DE ADMINISTRAÇÃO</h1>

	<h2 class="text-center">Gestão de Usuários</h2>
	
	
	<style>
		@-moz-document url-prefix() {
		    fieldset {
		        display: table-cell;
		    }
		}
	</style>

	
	<div class="responsive-table">
		
		<table class="table table-condensed table-striped table-hover">
			
			<thead>
				<tr>
					<th class="text-center">
						<span class="glyphicon glyphicon-sort"></span>
						<a href="gestao-usuarios-lista.php?criterio=id_usuario&ordem=<?= $ordem_id_inversa ?>">ID</a>
					</th>
					<th>
						<span class="glyphicon glyphicon-sort"></span>
						<a href="gestao-usuarios-lista.php?criterio=nm_usuario&ordem=<?= $ordem_nm_inversa ?>">NOME</a>
					</th>
					<th class="text-center">E-MAIL</th>
					<th>EDITAR</th>
				</tr>
			</thead>

			<tbody>
				
			<?php

			foreach ($lista_usuarios as $usuario) :

				?>

				<tr>
					<td class="text-center">&nbsp;&nbsp;<?= $usuario['id_usuario'] ?></td>
					<td><?= $usuario['nm_usuario'] ?></td>
					<td class="text-center"><?= $usuario['ds_email_tjsp'] ?></td>
					<td class="text-center">
						<a href="gestao-locais-script.php?op=edicao&id_usuario=<?= $usuario['id_usuario'] ?>">
							<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
						</a>
					</td>
				</tr>

				<?php

			endforeach;


			?>

			</tbody>

		</table>	

	</div>

<?php 

	require_once('admin-rodape.php');

else :

	header('Location:index.php');

endif;