 </div>

  <footer class="foot" >
      <br>
      <div id="foot-logos">
      <a href="http://www.conacyt.gob.mx/" target="_blank"><img class="footer-img" src="<?php echo base_url(); ?>images/conacyt.png" ></a>
     <a href="http://www.sep.gob.mx/" target="_blank"> <img class="footer-img" src="<?php echo base_url(); ?>images/sep.png" ></a>
      <a href="http://www.anuies.mx/" target="_blank"><img class="footer-img" src="<?php echo base_url(); ?>images/anuies.png" ></a>
      <a href="http://www.unam.mx/" target="_blank"><img class="footer-img" src="<?php echo base_url(); ?>images/unam.png" ></a>
      <a href="http://www.cinvestav.mx/" target="_blank"><img class="footer-img" src="<?php echo base_url(); ?>images/cinvestav.png" ></a>
      <a href="http://www.ipn.mx/" target="_blank"><img class="footer-img" src="<?php echo base_url(); ?>images/ipn.png" ></a>
      <a href="http://www.uam.mx/" target="_blank"><img class="footer-img" src="<?php echo base_url(); ?>images/uam.png" ></a>
      <a href="http://www.udg.mx/" target="_blank"><img class="footer-img" src="<?php echo base_url(); ?>images/guadalajara.png" ></a>
      <a href="http://www.cudi.edu.mx/" target="_blank"><img class="footer-img" src="<?php echo base_url(); ?>images/cudi.png" ></a>
      </div>
            
      <br clear="all" />
      <div id="foot-texto" >
      CONSORCIO NACIONAL DE RECURSOS DE INFORMACIÓN CIENTÍFICA Y TECNOLÓGICA (CONRICyT)
      <br>
Copyright © 2011 Derechos Reservados<br><br>

Av. Insurgentes Sur 1582, Col. Crédito Constructor, Del. Benito Juárez
<br>
C.P.: 03940, México, D.F. Tel: (55) 5322-7700. Ext. 4020 a la 4026
<br><br><br>
       </div>
      
      </footer>
      
        <!-- Modal -->
		<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-sm">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">Autenticarse al<br />Centro de Capacitación</h4>
		      </div>
		      <div class="modal-body">
		        <p class="text-danger text-center hide">Tu usuario o contraseña son incorrectos</p>
		        <?php
		        $attr = array(
		        		'id'	=> 'formLogin',
		        		'name'	=> 'formLogin',
		        		'class'	=> 'form-group'
		        );
		        echo form_open(base_url('login/entrar'), $attr);
		        
		        $attr = array(
		        		'id'	=> 'usr_modal',
		        		'name'	=> 'usr_modal',
		        		'class'	=> 'form-control',
		        		'placeholder' => 'Usuario'
		        );
		        echo '<div class="form-group">';
		        echo form_input($attr);
		        echo '</div>';
		        
		        $attr = array(
		        		'id'	=> 'curso_modal',
		        		'name'	=> 'curso_modal',
		        		'type'	=> 'hidden',
		        		'value'	=> ''
		        );
		        echo form_input($attr);
		        
		        $attr = array(
		        		'id'	=> 'pass_modal',
		        		'name'	=> 'pass_modal',
		        		'class'	=> 'form-control',
		        		'placeholder' => 'Contraseña'
		        );
		        echo '<div class="form-group">';
		        echo form_password($attr);
		        echo '</div>';
		        
		        $attr = array(
		        		'id'	=> 'btn_modal',
		        		'name'	=> 'btn_modal',
		        		'class'	=> 'btn btn-primary',
		        		'content' => 'Iniciar sesión'
		        );
		        echo '<div class="form-group">';
		        echo form_button($attr);
		        echo '</div>';
		        
		        echo '<p><a href="'.base_url('registro').'">Regístrate aquí</a></p>';
		        
		        echo form_close();
		        ?>
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Fin ventana modal -->
		
		<!-- Modal error login -->
		<div class="modal fade" id="modalErrorLogin" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-sm">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Cerrar</span></button>
		        <p class="glyphicon glyphicon-alert text-center"></p>
		      </div>
		      <div class="modal-body">
		      	<h4>¡Usuario o contraseña invalidos!</h4>
		      	<p>Por favor intenta de nuevo. Si el error persiste favor de comuncarte a:<br />
		      	Centro de Capacitación Virtual CONRICYT<br />
		      	Tel: (55) 5322 7700 ext. 4026<br />
		      	Email: centro.capacitacion@conricyt.mx</p>
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Fin modal error -->
      
      
         </div>
   <!-- Fin contenedor 1-->
      




</body>
</html>