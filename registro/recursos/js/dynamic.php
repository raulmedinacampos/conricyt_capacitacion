/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
<?php
include_once('../general.php');

function passId($id) {
	echo '';
	echo 'alert("'.$id.'")';
}

$dbh=Sdx_ConectaBase();
mysqli_set_charset($dbh, "utf8");
?>

require([
    "dojo/parser",
    "dojo/ready",
    "dijit/form/ValidationTextBox",
    "dijit/form/ComboBox",
    "dijit/form/FilteringSelect",
    "dijit/form/Button",
    "dijit/form/CheckBox",
    "dijit/Dialog",
    "dijit/ProgressBar",
    "dojo/domReady!"],
        function(dom) {

        }
);

function Cerrar() {
    dijit.byId(myDialog).hide();
}

function Valida() {
    var xnombre = dijit.byId('nombre');
    var xhide_ap_paterno = dijit.byId('hide_ap_paterno');
    var xap_paterno = dijit.byId('ap_paterno');
    var xhide_ap_materno = dijit.byId('hide_ap_materno');
    var xap_materno = dijit.byId('ap_materno');
    var xsexo = dijit.byId('sexo');
    var xcorreo = dijit.byId('correo');
    var xid_pais = dijit.byId('id_pais');
    var xid_perfil = dijit.byId('id_perfil');
    var xid_institucion = dijit.byId('id_institucion');
    var xcursos = dojo.doc.getElementsByTagName("input");
    var xterminos = dijit.byId('terminos');
    var xcapta = dijit.byId('capta');
    

    var flagValido = 0;
    var cadError = '';

    if (!xnombre.isValid()) {
        flagValido = 1;
        xnombre._hasBeenBlurred = true;
        xnombre.validate();
        cadError += '<li>Nombre</li>';
    }
    if (!xhide_ap_paterno.checked) {
        if (!xap_paterno.isValid()) {
            flagValido = 1;
            xap_paterno._hasBeenBlurred = true;
            xap_paterno.validate();
            cadError += '<li>Apellido paterno</li>';
        }
    }
    if (!xhide_ap_materno.checked) {
        if (!xap_materno.isValid()) {
            flagValido = 1;
            xap_materno._hasBeenBlurred = true;
            xap_materno.validate();
            cadError += '<li>Apellido materno</li>';
        }
    }
    if (xid_pais.get('value')=='') {
        flagValido = 1;
        xid_pais._hasBeenBlurred = true;        
        cadError += '<li>País</li>';
    }
    if (xid_perfil.get('value')=='') {
        flagValido = 1;
        xid_perfil._hasBeenBlurred = true;        
        cadError += '<li>Perfil</li>';
    }
    if (xid_institucion.get('value')=='') {
        flagValido = 1;
        xid_institucion._hasBeenBlurred = true;        
        cadError += '<li>Institución</li>';
    }
    if (xsexo.get('value')=='') {
        flagValido = 1;
        xsexo._hasBeenBlurred = true;        
        cadError += '<li>Sexo</li>';
    }
    if (!xcorreo.isValid()) {
        flagValido = 1;
        xcorreo._hasBeenBlurred = true;
        xcorreo.validate();
        cadError += '<li>Correo</li>';
    }
    
    /* Validación por lo menos un curso seleccionado */
    var contador = 0;
    for(var i=0; i<xcursos.length; i++) {
    	if(xcursos[i].id.indexOf("id_curso_") != -1) {
        	if(dijit.byId(xcursos[i].id).checked) {
        		contador++;
            }
        }
    }
    
    if(contador == 0) {
        flagValido = 1;
        xterminos._hasBeenBlurred = true;
        cadError += '<li>Debe seleccionar por lo menos un curso</li>';
    }
    /* Fin validacion cursos */
    if (!xterminos.checked) {
        flagValido = 1;
        xterminos._hasBeenBlurred = true;
        cadError += '<li>Acepte términos de uso</li>';
    }
    if (!xcapta.isValid()) {
        flagValido = 1;
        xcapta._hasBeenBlurred = true;
        xcapta.validate();
        cadError += '<li>Ingrese los caracteres de la imagen</li>';
    }
    
    

    if (flagValido === 1) {
        dijit.byId(myDialog).closeButtonNode.style.display = "none";
        document.getElementById('TextoDialogo').innerHTML = '<p>Existen errores de llenado en los siguientes campos:</p><ul>' + cadError + '</ul>';
        dijit.byId('btn_aceptar').domNode.style.display = "none";
        dijit.byId('btn_cancelar').set("label", "Cerrar");
        myDialog.show();

        /*var boton = new dijit.form.Button({label: 'Aceptar', action:
         function Cerrar() {
         dijit.byId('MiDialog2').hide();
         }/*});*/

        return false;
    } else {
        return true;
    }
}

function Confirma() {
    var xnombre = dijit.byId('nombre').get('value');
    var xap_paterno = dijit.byId('ap_paterno').get('value');
    var xap_materno = dijit.byId('ap_materno').get('value');
    var xsexo = dijit.byId('sexo').get('value');
    var xcorreo = dijit.byId('correo').get('value');
    var xidpais = dijit.byId('id_pais').get('value');
    var xpais = dijit.byId('id_pais').attr('displayedValue');
    var xentidad = dijit.byId('id_entidad').attr('displayedValue');
    var xperfil = dijit.byId('id_perfil').attr('displayedValue');
    var xotroperfil = dijit.byId('perfil').get('value');
    var xinstitucion = dijit.byId('id_institucion').attr('displayedValue');
    var xotrainstitucion = dijit.byId('institucion').get('value');
    var xcursos = dojo.doc.getElementsByTagName("input");
    
	cadInfo = '';
    if (Valida()) {
    	cadInfo += '<li>Nombre: '+xnombre+'</li>';
        cadInfo += '<li>Apellido paterno: '+xap_paterno+'</li>';
        cadInfo += '<li>Apellido materno: '+xap_materno+'</li>';
        cadInfo += '<li>Correo: '+xcorreo+'</li>';
        cadInfo += '<li>Sexo: '+xsexo+'</li>';
        cadInfo += '<li>País: '+xpais+'</li>';
        if(xidpais == '156') {
        	cadInfo += '<li>Entidad: '+xentidad+'</li>';
        }
        if(xperfil == 'Otro') {
        	cadInfo += '<li>Perfil: '+xotroperfil+'</li>';
        } else {
            cadInfo += '<li>Perfil: '+xperfil+'</li>';
        }
        if(xinstitucion == 'Otra') {
        	cadInfo += '<li>Institución: '+xotrainstitucion+'</li>';
        } else {
        	cadInfo += '<li>Institución: '+xinstitucion+'</li>';
        }
        
        cadInfo += '<li>Cursos seleccionados:<ul>';
        
        for(var i=0; i<xcursos.length; i++) {
            if(xcursos[i].id.indexOf("id_curso_") != -1) {
                if(dijit.byId(xcursos[i].id).checked) {
                    var idCurso = (dijit.byId(xcursos[i].id).etiqueta)
                    cadInfo += '<li>'+idCurso+'</li>';
                }
            }
        }
        
        cadInfo += '</ul></li>';
        
    	document.getElementById('TextoDialogo').innerHTML = '<p>La información que va a registrar es la siguiente:</p><ul>' + cadInfo + '</ul>';
        dijit.byId('btn_aceptar').domNode.style.display = "inline";
		dijit.byId('btn_cancelar').set("label", "Regresar");
        myDialog.show();
    }
}

function Registra() {
    if (Valida()) {
        dijit.byId(myDialog).closeButtonNode.style.display = "none";
        document.getElementById('TextoDialogo').innerHTML = 'Espere mientras se envía la información';
        myDialog.show();
        dojo.xhrPost({
            url: 'rpcRegistra.php',
            handleAs: 'json',
            content: {
                token: document.getElementById("token").value,
                nombre: dijit.byId('nombre').get('value'),
                ap_paterno: dijit.byId('ap_paterno').get('value'),
                ap_materno: dijit.byId('ap_materno').get('value'),
                sexo: dijit.byId('sexo').get('value'),
                correo: dijit.byId('correo').get('value'),
                id_pais: dijit.byId('id_pais').get('value'),
                id_entidad: dijit.byId('id_entidad').get('value'),
                id_perfil: dijit.byId('id_perfil').get('value'),
                perfil: dijit.byId('perfil').get('value'),
                id_institucion: dijit.byId('id_institucion').get('value'),
                institucion: dijit.byId('institucion').get('value'),
                <?php
                $comando3 = 'SELECT id_curso, curso FROM curso ORDER BY curso';
                $result3 = mysqli_query ($dbh, $comando3);
                while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)){
                    $id_curso = $row3['id_curso'];
                    echo "id_curso_".$id_curso.": (dijit.byId('id_curso_".$id_curso."') != null) ? (dijit.byId('id_curso_".$id_curso."').get('value')) : '',";
                }
                ?>
                capta: dijit.byId('capta').get('value')
            },
            timeout: 5000,
            handle: function(response, ioArgs) {
                if (response.Msg != 'OK' && response.Msg != null) {
                    myDialog.hide();
                    alert(response.Msg);
                } else {
                    myDialog.hide();
                    alert('La información se ha enviado con éxito');
                    window.location = "/cursos/limpiarLista";
                }
                return response;
            }
        });
    }
}

function verificapais() {
    var pais = dijit.byId('id_pais').get('value');
    if (pais == '156') {
        document.getElementById('estado').style.display = 'block';
    } else {
        document.getElementById('estado').style.display = 'none';
    }
}

function Revisaperfil() {
    var perfil = dijit.byId('id_perfil').get('value');
    if (perfil == '10') {
        document.getElementById('operfil').style.display = 'block';
    } else {
        document.getElementById('operfil').style.display = 'none';
    }
}

function Revisainstitucion() {
    var institucion = dijit.byId('id_institucion').get('value');
    if (institucion == '484') {
        document.getElementById('oinstitucion').style.display = 'block';
    } else {
        document.getElementById('oinstitucion').style.display = 'none';
    }
}

function OcultarCampo(chk, id) {
    var checkbox = dijit.byId(chk);
    var campo = dijit.byId(id);
    if(checkbox.checked) {
        campo.style.display = 'none';
        if(chk.name == 'hide_ap_paterno') {
    		dijit.byId('hide_ap_materno').disabled = true;
        } else if(chk.name == 'hide_ap_materno') {
        	dijit.byId('hide_ap_paterno').disabled = true;
        }
    } else {
        campo.style.display = 'inline';
        if(chk.name == 'hide_ap_paterno') {
    		dijit.byId('hide_ap_materno').disabled = false;
        } else if(chk.name == 'hide_ap_materno') {
        	dijit.byId('hide_ap_paterno').disabled = false;
        }
    }
}

