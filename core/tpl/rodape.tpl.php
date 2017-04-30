	</div>
	<!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="bootstrap/js/jquery.js"></script>
    <script>window.jQuery || document.write('<script src="bootstrap/js/jquery.js"><\/script>')</script>
    <script src="bootstrap/js/bootstrap.min.js"></script> -->
</body>
</html>
<?php
	if (isset($conexao)) :
		mysqli_close($conexao);
	endif;
?>