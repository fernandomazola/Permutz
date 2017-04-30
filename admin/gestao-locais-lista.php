<?php

session_start();
require_once('../core/inc/conex.inc.php');

if ($_SESSION['admin']) :

	if (isset($_GET['criterio']) && $_GET['criterio']=='id_local') :

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
		
		$query = "SELECT * FROM t_pmt_locais ORDER BY id_local {$ordem_id}";

	elseif (isset($_GET['criterio']) && $_GET['criterio']=='nm_local') :

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

		$query = "SELECT * FROM t_pmt_locais ORDER BY nm_local {$ordem_nm}";

	else :
		
		$ordem_id_inversa = 'DESC';
		$ordem_nm_inversa = 'DESC';
		$query = "SELECT * FROM t_pmt_locais ORDER BY nm_local ASC";
	
	endif;
		

	$lista_locais = array();

	$result = mysqli_query($conexao, $query);

	while ($linha = mysqli_fetch_assoc($result)) :
		array_push($lista_locais, $linha);
	endwhile;

	require_once('admin-cabecalho.php');

?>

	<h1 class="text-center">PAINEL DE ADMINISTRAÇÃO</h1>

	<h2 class="text-center">Gestão de Locais</h2>
	
	
	<style>
		@-moz-document url-prefix() {
		    fieldset {
		        display: table-cell;
		    }
		}
	</style>


	<form class="form-horizontal" action="gestao-locais-script.php" method="post">
		
		<fieldset>
			
			<legend class="text-center">Edição de Locais</legend>

			<div class="form-group">
				<label for="local-label-atual" class="col-sm-2 control-label">Atual:</label>
				<div class="col-sm-10">
					<select class="form-control" name="local-label-atual">
						<option value=""></option>
						<option value="999">criar novo local</option>
						<?php foreach ($lista_locais as $local) : ?>
							<option value="<?= $local['id_local'] ?>"><?= $local['nm_local'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="local-label-novo" class="col-sm-2 control-label">Novo:</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="local-label-novo">
				</div>
			</div>

			<div class="form-group">
				<label for="local-status" class="col-sm-2 control-label">Status:</label>
				<div class="col-sm-5 text-center">
					<input type="radio" name="local-status" value="1" checked> Ativo
				</div>
				<div class="col-sm-5 text-center">
					<input type="radio" name="local-status" value="0"> Inativo
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-2"></div>
				<div class="col-sm-10">
					<input type="submit" class="btn btn-block btn-success" name="local-gravar" value="Gravar">
				</div>
			</div>

		</fieldset>

	</form>


	<hr>

	
	<div class="responsive-table">
		
		<table class="table table-condensed table-striped table-hover">
			
			<thead>
				<tr>
					<th class="text-center">
						<span class="glyphicon glyphicon-sort"></span>
						<a href="gestao-locais-lista.php?criterio=id_local&ordem=<?= $ordem_id_inversa ?>">ID</a>
					</th>
					<th>
						<span class="glyphicon glyphicon-sort"></span>
						<a href="gestao-locais-lista.php?criterio=nm_local&ordem=<?= $ordem_nm_inversa ?>">NOME</a>
					</th>
					<th class="text-center">STATUS</th>
				</tr>
			</thead>

			<tbody>
				
			<?php

			foreach ($lista_locais as $local) :

				?>

				<tr>
					<td class="text-center">&nbsp;&nbsp;<?= $local['id_local'] ?></td>
					<td><?= $local['nm_local'] ?></td>
					<td class="text-center"><?= $local['st_local'] ?></td>
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