/*function inicializar() {
	$.getJSON("usuarios/listarRegistrados", function(data) {
		var resultados = "";
		var num = 1;
		
		$.each(data, function(index, val) {
			resultados += '<tr>';
			resultados += '<td>'+num+'</td>';
			resultados += '<td>'+val.nombre+' '+val.ap_paterno+' '+val.ap_materno+'</td>';
			resultados += '<td>'+val.institucion+'</td>';
			resultados += '<td>'+val.login+'</td>';
			resultados += '<td>'+val.password+'</td>';
			resultados += '<td><span class="glyphicon glyphicon-envelope"></span></td>';
			resultados += '</tr>';
			num++;
		});
		
		$("#resultados").append(resultados);
	});
}*/

$(function() {
	/*inicializar();*/
	$("#btnBuscar").click(function() {
		$("#formBuscador").submit();
	});
});