$('#opcao-origem').change(function(){
	var intIdOrigem = $('#opcao-origem').val();
	var strOrigem = $('#opcao-origem option:selected').text();
	if ($('#local-cadastrado-'+intIdOrigem).length>0 || $('#local-adicionar-'+intIdOrigem).length>0) {
		alert('O local de origem deve ser diferente dos locais de destino');
		$('#opcao-origem').val('');
		$('#local-origem-nome').val('');
		$('#opcao-origem').focus();
		return false;
	} else {
		$('#local-origem-nome').val(strOrigem);
	}
	// alert(strOrigem);
	// alert('teste');
});

/* adicionar destino à lista */
$('#btn-adicionar-destino').click(function(){
	if ($('#opcao-destino').val()=='') {
		alert("Por favor, insira um local válido");
		return false;
	} else if (parseInt($('#opcao-origem').val()) == parseInt($('#opcao-destino').val())) {
		alert('O local de destino deve ser diferente do local de origem');
		return false;
	} else {
		var intIdDestino = parseInt($('#opcao-destino').val())	;
		var strDestino = $('#opcao-destino option:selected').text();
		// if ($('#linha-local-31').length>0 || $('#local-adicionar-31').length>0) {
		if ($('#linha-local-'+intIdDestino).length>0 || $('#local-adicionar-'+intIdDestino).length>0) {
			alert('Destino já existente na lista');
		} else {
			// intIdDestino = parseInt(intIdDestino);
			if ($('#local-cadastrado-'+intIdDestino).length==0) {
				var campoAdicionarLocal = '<input type="text" name="locais-desejados-adicionar[]" id="local-adicionar-'+intIdDestino+'" value="'+intIdDestino+": "+strDestino+'" />';
				$('#destinos-adicionar').append(campoAdicionarLocal);
			}			
			var linhaDadosLocal = '<tr id="linha-local-'+intIdDestino+'"><td>'+strDestino+'</td><td><a class="btn btn-danger pull-right" role="button" onclick="excluiDestino('+intIdDestino+', \''+strDestino+'\');">excluir</a></td></tr>';
			$('table').append(linhaDadosLocal);
			if ($('#local-excluir-'+intIdDestino).length>0) {
			 	var campoExcluirLocal = document.getElementById('local-excluir-'+intIdDestino);
				campoExcluirLocal.parentNode.removeChild(campoExcluirLocal);
			}
		}
		// $('#input-opcao-destino').val('');
		$('#opcao-destino').focus();
	}
});

/* excluir destino da lista */
function excluiDestino(intId, strDestino) {
	// strId = id.toString();
	var linha = document.getElementById('linha-local-'+intId);
	linha.parentNode.removeChild(linha);
	var campo;
	if (campo = document.getElementById('local-adicionar-'+intId)) {
		campo.parentNode.removeChild(campo);
	}
	if ($('#local-excluir-'+intId).length==0) {
	 	var campoExcluirLocal = '<input type="text" name="locais-desejados-excluir[]" id="local-excluir-'+intId+'" value="'+intId+': '+strDestino+'" />';
		$('#destinos-excluir').append(campoExcluirLocal);
	}
}