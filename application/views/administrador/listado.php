<!-- 
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/jquery.bs_grid.bs2.min.css'); ?>" />
<script type="text/javascript" src="<?php echo base_url('scripts/jquery.bs_grid.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/en.min.js'); ?>"></script>-->
<script type="text/javascript" src="<?php echo base_url('scripts/buscador.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/jquery-1.11.3.min.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/jquery.dataTables.min.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('css/dataTables.bootstrap.css'); ?>" />
<script type="text/javascript" src="<?php echo base_url('scripts/jquery.dataTables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/dataTables.bootstrap.min.js'); ?>"></script>
 <script type="text/javascript">

</script>


<script type="text/javascript">
function reiniciarTabla(){
	$('#resultados').dataTable().fnDestroy();
}


function mandarFormulario(){
	 var cadena = $("#formBuscador").serialize();
	 $.post("usuarios/listarRegistrados",  cadena , function(data){
		 var obj = $.parseJSON(data);
		
		 //alert(obj.toSource());
		 /*$.each(data, function(index, val) {
			 alert();
		 });*/
		 	
			var resultados = "";
			var num = 1;	
			var $tabla = $("#resultados");

			reiniciarTabla();	
			
			$tabla.find("tr:gt(0)").remove();
			
			$.each(obj, function(index, val) {
					
				resultados += '<tr>';
				resultados += '<td>'+num+'</td>';
				resultados += '<td>'+val.nombre+' '+val.ap_paterno+' '+val.ap_materno+'</td>';
				resultados += '<td>'+val.institucion+'</td>';
				resultados += '<td>'+val.login+'</td>';
				resultados += '<td>'+val.password+'</td>';
				resultados += '<td><a href="../registro/comprobante'+"/"+val.id_usuario+'"><span class="glyphicon glyphicon-download-alt"></span></a></td>';
				resultados += '</tr>';
				num++;
				});			
				$("#resultados").append(resultados);

				document.getElementById("total_resultados").innerHTML="Total de Registrados: " + (num-1);
				
				$(document).ready(function() {
									
				    $('#resultados').dataTable({	

				    	"aoColumnDefs": [
				    	                 { 'bSortable': false, 'aTargets': [ 4, 5 ] }
				    	              ],
				    	
				    	"language": {
						    "paginate": {
						      "previous": "&lt;&lt;", "next": "&gt;&gt;"
						    }
						  },

						  "sDom": '<"top">rt<"bottom"p><"clear">',
					    			    		    	
				    	"lengthMenu": [[ 20, 20], [20, 20]],
				    	searching: false,
				        });

				} );
				
			});//funtion data         
	}//mandar formulario
	$(function(){
	 $("#btnBuscar").click(function(variable){
		 variable.preventDefault();		
		 mandarFormulario(); });
	});
	
	    

		  
</script>

<script type="text/javascript">

function excel(){
	$("#formBuscador").submit();
}
$(function(){
	 $("#btnExcel").click(function(variable){
		 variable.preventDefault();		
		 excel(); });
	});
	  
</script>	

<style type="text/css">
#resultados td:last-of-type {
    text-align: center;
}
</style>

<div id="contenido-titulo">
	<h1>Listado de usuarios</h1>
</div>

<div id="contenido-linea">
	<div id="contenido-mundo">
		<img src="<?php echo base_url('images/mundo.png'); ?>">
	</div>
</div>

<br>
<br>
<div id="contenido-texto">

<?php
$attr = array(
		'id'	=> 'formBuscador',
		'name'	=> 'formBuscador',
		'class'	=> 'form-horizontal'
);

echo form_open(base_url('administrador/reporte/reporteExcel'), $attr);

echo '<div class="form-group">';
echo '<div class="col-sm-6 col-xs-12">';
echo form_label('Nombre');
$attr = array(
		'id'	=> 'nombre',
		'name'	=> 'nombre',
		'value'	=> (isset($filtro['nombre'])) ? $filtro['nombre'] : "",
		'class'	=> 'form-control',
);
echo form_input($attr);
echo '</div>';

echo '<div class="col-sm-6 col-xs-12">';
echo form_label('Apellido paterno');
$attr = array(
		'id'	=> 'ap_paterno',
		'name'	=> 'ap_paterno',
		'value'	=> (isset($filtro['ap_paterno'])) ? $filtro['ap_paterno'] : "",
		'class'	=> 'form-control'
);
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<div class="col-sm-6 col-xs-12">';
echo form_label('Apellido materno');
$attr = array(
		'id'	=> 'ap_materno',
		'name'	=> 'ap_materno',
		'value'	=> (isset($filtro['ap_materno'])) ? $filtro['ap_materno'] : "",
		'class'	=> 'form-control'
);
echo form_input($attr);
echo '</div>';

echo '<div class="col-sm-6 col-xs-12">';
echo form_label('Correo');
$attr = array(
		'id'	=> 'correo',
		'name'	=> 'correo',
		'type'	=> 'email',
		'value'	=> (isset($filtro['correo'])) ? $filtro['correo'] : "",
		'class'	=> 'form-control'
);
echo form_input($attr);
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
echo '<div class="col-sm-6 col-xs-12">';
echo form_label('Institución');
$attr = array(
		'id'	=> 'institucion',
		'name'	=> 'institucion',
		'value'	=> (isset($filtro['i.institucion'])) ? $filtro['i.institucion'] : "",
		'class'	=> 'form-control'
);
echo form_input($attr);
echo '</div>';

echo '<div class="col-sm-6 col-xs-12">';
echo '<div class="col-xs-12">'.form_label("&nbsp;").'</div>';
$attr = array(
		'id'		=> 'btnBuscar',
		'name'		=> 'btnBuscar',
		'content'	=> 'Buscar',
		'class'		=> 'btn btn-primary pull-right'
);
echo form_button($attr);


?>
<script >
mandarFormulario();
</script>


<a href= "#" id ="btnExcel" class="btn btn-success active pull-right" role="button" style="margin-right:10px" > Exportar a Excel</a>

<?php

echo '</div>';
echo '</div>';

echo form_close();
?>

<!-- <div id="demo_grid1"></div> -->

<div class="panel panel-default">
<div class="panel-heading" id="total_resultados">Total de Registrados: </div>
<table id="resultados" class="table table-striped table-condensed" >
  <thead>
  <tr>
    <th>No.</th>
    <th>Nombre</th>
    <th>Institución</th>
    <th>Correo</th>
    <th>Contraseña</th>
    <th>Comprobante</th>
  </tr>
  </thead>
  <?php
  /*
  $i = 1;
  foreach ( $usuarios as $usuario ) {
  ?>
  	<tr>
  		<td><?php echo $i++; ?></td>
  		<td><?php echo trim($usuario->nombre." ".$usuario->ap_paterno." ".$usuario->ap_materno); ?></td>
  		<td><?php echo $usuario->institucion; ?></td>
  		<td><?php echo $usuario->login; ?></td>
  		<td><?php echo $usuario->password; ?></td>
  		<td><a href="<?php echo base_url('registro/comprobante')."/".$usuario->id_usuario; ?>"><span class="glyphicon glyphicon-download-alt"></span></a></td>
  	</tr>
  <?php
  }
  */
  ?>
</table>
</div>
<!-- <nav>
  <ul class="pagination pagination-sm">
    <li class="disabled">
      <a href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <li class="active"><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li>
      <a href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav> -->
</div>