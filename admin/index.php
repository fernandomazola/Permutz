<?php 

session_start();

require_once('admin-access.php');





if (isset($_SESSION['admin']) && $_SESSION['admin']==true) :
	
	header('Location:admin-painel.php');

else : 

	require_once('admin-cabecalho.php');

?>

		<form class="form-horizontal" action="index.php" method="post">
			
			<fieldset>
				
				<legend class="text-center">Login Admin</legend>

				<div class="form-group">
					<label for="admin-name" class="col-sm-2 control-label">AdminName:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control text-center" name="admin-name">
					</div>
				</div>

				<div class="form-group">
					<label for="admin-pw" class="col-sm-2 control-label">AdminPass:</label>
					<div class="col-sm-10">
						<input type="password" class="form-control text-center" name="admin-pw">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2"></div>
					<div class="col-sm-10">
						<input type="submit" class="btn btn-block btn-success" name="admin-enter">
					</div>
				</div>

			</fieldset>

		</form>



<?php 

	require_once('admin-rodape.php');

endif; 

?>