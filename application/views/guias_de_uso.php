<style>
#myModal .modal-content {
    background-color: unset;
    border: none;
    box-shadow: none;
}

.modal-backdrop.in {
    opacity: 0.75;
}
</style>

<script type="text/javascript">
$(function() {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$('video').trigger('pause');
	});

	$('body').on('shown.bs.modal', '.modal', function () {
		$('video').trigger('play');
	});
});
</script>
         <div id="contenido-titulo" >
         <h1 >Guías de uso</h1>
         </div>
         
         <div id="contenido-linea" >
         <div id="contenido-mundo" ><img src="<?php echo base_url('images/mundo.png'); ?>"></div>
         </div>
         
         <br><br>
         <div id="contenido-texto" >
         Si tienes alguna duda sobre el funcionamiento de la plataforma del Centro de Capacitación Virtual, aquí podrás resolver todas tus dudas consultando los videotutoriales. 
         <br /><br />
         
         <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">Video</button>
         
         <!-- Video -->
         <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
         <div align="center" class="embed-responsive embed-responsive-16by9">
		    <video autoplay="autoplay" controls="controls" preload="auto" class="embed-responsive-item">
		        <source src="<?php echo base_url('media/como_ingresar4.mp4')?>" type="video/mp4" />
		    </video>
		</div>
		</div>
		</div>
		</div>
         <!-- Fin video -->
         
         <!-- guia -->
         <div  class="guia-modulo" >
         
         
         <div class="guia-icono" >
         <img  src="<?php echo base_url(); ?>images/iconos/video.png" >
         </div>
         
         <div class="guia-texto" >
         
         Te presentamos un video que muestra una visión general del como están conformados  los cursos de capacitación 	virtual.
         <br />
         <br />
         
         <div class="guia-links" > 
          <a href="<?php echo base_url(); ?>images/guias/moodle" target="_blank" >Ver video </a>
         <br />
         <a href="<?php echo base_url(); ?>images/guias/moodle/moodle.zip" target="_blank"  >Descargar video</a>
         </div>
         
       </div>
         
         </div>
         <!-- Fin guia -->
         
         
         
         
         <br style="clear: both;">
         </div>
         
         <br><br>