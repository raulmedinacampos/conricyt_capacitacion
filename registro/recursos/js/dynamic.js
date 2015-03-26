/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
    var xap_paterno = dijit.byId('ap_paterno');
    var xlogin = dijit.byId('login');

    var flagValido = 0;
    var cadError = '';

    if (!xnombre.isValid()) {
        flagValido = 1;
        xnombre._hasBeenBlurred = true;
        xnombre.validate();
        cadError += '<li>Nombre</li>';
    }
    if (!xap_paterno.isValid()) {
        flagValido = 1;
        xap_paterno._hasBeenBlurred = true;
        xap_paterno.validate();
        cadError += '<li>Apellido paterno</li>';
    }
    if (!xlogin.isValid()) {
        flagValido = 1;
        xlogin._hasBeenBlurred = true;
        xlogin.validate();
        cadError += '<li>Usuario</li>';
    }

    if (flagValido === 1) {
        dijit.byId(myDialog).closeButtonNode.style.display = "none";
        document.getElementById('TextoDialogo').innerHTML = '<p>Existen errores de llenado en los siguientes campos:</p><ul>' + cadError + '</ul>';
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

function Registra() {
    if (Valida()) {
        dijit.byId(myDialog).closeButtonNode.style.display = "none";
        document.getElementById('TextoDialogo').innerHTML = 'Espere mientras se envía la información';
        myDialog.show();
        dojo.xhrPost({
            url: 'rpcRegistra.php',
            handleAs: 'json',
            content: {
                nombre: dijit.byId('nombre').get('value'),
                ap_paterno: dijit.byId('ap_paterno').get('value'),
                ap_materno: dijit.byId('ap_materno').get('value'),
                sexo: dijit.byId('sexo').get('value'),
                id_pais: dijit.byId('id_pais').get('value'),
                id_entidad: dijit.byId('id_entidad').get('value'),
                id_perfil: dijit.byId('id_perfil').get('value'),
                perfil: dijit.byId('perfil').get('value'),
                id_institucion: dijit.byId('id_institucion').get('value'),
                institucion: dijit.byId('institucion').get('value'),
                //id_curso: dijit.byId('id_curso').get('value'),
                login: dijit.byId('login').get('value'),
                password: dijit.byId('password').get('value'),
                //capta: document.getElementById('capta').get('value')
            },
            timeout: 5000,
            handle: function(response, ioArgs) {
                if (response.Msg != 'OK' && response.Msg != null) {
                    myDialog.hide();
                    alert(response.Msg);
                } else {
                    myDialog.hide();
                    alert('La información se ha enviado con éxito');
                    window.location = "index.php";
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



