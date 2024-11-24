function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
 
	try {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
		xmlhttp = false;
	}
}
 
if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	  xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function terminos_condiciones(cod_act, cod_ban, codest, cod_gru_aux,hora_fin){		
	var res = document.getElementById('page-content-inner');	
	res.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";	
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/terminos_condiciones.php?cod_act="+cod_act+"&cod_ban="+cod_ban+"&codest="+codest+"&cod_gru_aux="+cod_gru_aux+"&hora_fin="+hora_fin+"", true);
	//window.open("ver_actividades_eval.php?cod_eval="+cod_eval+"&cod_ban="+cod_ban+"&codest="+codest+"",'_blank');
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			res.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function iniciar_evaluacion(cod_act, cod_ban, codest, cod_gru_aux,horacompleta,hora_fin){
	
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/ver_actividades_evaluacion.php?cod_act="+cod_act+"&cod_ban="+cod_ban+"&codest="+codest+"&cod_gru_aux="+cod_gru_aux+"&h_fin="+hora_fin+"",true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
			var myScripts = resultado.getElementsByTagName("script");
			if (myScripts.length > 0) {
				eval(myScripts[0].innerHTML);
			}
		}
	}
	var end = new Date(horacompleta);
    var _second = 1000;
    var _minute = _second * 60;
    var _hour = _minute * 60;
    var _day = _hour * 24;
    var timer;
    function showRemaining() {
        var now = new Date();
        var distance = end - now;
        if (distance < 0) {
            clearInterval(timer);
            document.getElementById('countdown').innerHTML = 'Tiempo Expirado !';
            return;
        }
        var days = Math.floor(distance / _day);
        var hours = Math.floor((distance % _day) / _hour);
        var minutes = Math.floor((distance % _hour) / _minute);
        var seconds = Math.floor((distance % _minute) / _second);
        //document.getElementById('countdown').innerHTML = days + ' dias, ';
        document.getElementById('countdown').innerHTML = hours + ' horas, ';
        document.getElementById('countdown').innerHTML += minutes + ' minutos y ';
        document.getElementById('countdown').innerHTML += seconds + ' segundos';
    }
    timer = setInterval(showRemaining, 1000);
	ajax.send(null);
	//window.open("ver_actividades_evaluacion.php?cod_act="+cod_act+"&cod_ban="+cod_ban+"&codest="+codest+"&cod_gru_aux="+cod_gru_aux+"","_self");
}
function abrir_cerrar(codmat,codest,periodo,idgrupo,subgrupo){
	var res = document.getElementById('page-content-inner');	
	res.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";		
	otro = objetoAjax();
	otro.open("GET", "contenedor/ver_boletin.php?_codmat="+codmat+"&_codest="+codest+"&_per="+periodo+"&_idgrupo="+idgrupo+"&_subgrupo="+subgrupo+"", true);
	otro.onreadystatechange = function(){
		if (otro.readyState == 4) {
			res.innerHTML = otro.responseText;
		}
	}
	otro.send(null);
}
function r_personal(codest){
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_persona.php?_codest="+codest, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);

}
function r_personalmod(codest){
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_persona_mod.php?_codest="+codest, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);

}
function ver_materias_examenes(codest){
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/materias_examenes.php?_codest="+codest, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);

}
function plataforma_atencion(codest, semestre, carrera){
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/plataforma/frm_principal.php?_codest="+codest+"&_semestre="+semestre+"&_carrera="+carrera+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

function nuevo_tramite(codest, tramite){
	if (confirm("Esta seguro de iniciar este trámite?")) {
		var resultado = document.getElementById('content_plataforma');
		resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
		ajax = objetoAjax();
		ajax.open("GET", "contenedor/plataforma/nuevo_tramite.php?codest="+codest+"&tramite="+tramite, true);
		ajax.onreadystatechange = function(){
			if (ajax.readyState == 4) {
				resultado.innerHTML = ajax.responseText;
			}
		}
		ajax.send(null);
	}else{
		return false;
	}
}
function guardar_evaluacion(){
	var formdata = new FormData(document.getElementById('form_eval'));	
	var res = document.getElementById('page-content-inner');
	formdata.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";	
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_evaluacion.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			res.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
}
function guardar_evaluacion2(){
	var formdata = new FormData(document.getElementById('form_eval'));
	var resultado = document.getElementById('mos_Eval');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_evaluacion.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
 
function guardar_datos(){
	var formdata = new FormData(document.getElementById('form_datos'));
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_datos.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
function modificar_datos(){
	var formdata = new FormData(document.getElementById('form_datos'));
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_modifica_datos.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
function registrar_horario(){
	var formdata = new FormData(document.getElementById('form_horarios'));
	var resultado = document.getElementById('recargo_horario');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_horario.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
function registrar_horario_cambio(){
	var formdata = new FormData(document.getElementById('form_horario_cambio'));
	var resultado = document.getElementById('recargo_horario_cambio');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_horario_cambio.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
function recargar_cambiohorames(idmes,idper){
	var formdata = new FormData(document.getElementById('form_horario_cambio'));
	var resultado = document.getElementById('recargo_horario_cambio');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/panel_horarios_ver_cambio_mes.php?_mes="+idmes+"&_persona="+idper+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
function eliminar_cambiohorario(idhora,idper,mes){
	var formdata = new FormData(document.getElementById('form_horario_cambio'));
	var resultado = document.getElementById('recargo_horario_cambio');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_elimina_horario_cambio.php?_idhora="+idhora+"&_persona="+idper+"&_mes="+mes+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
function recargar(idper){	
	var resultado = document.getElementById('recargo');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_horarios_ver.php?_persona="+idper, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function recargar_horario(idper){	
	var resultado = document.getElementById('recargo_horario');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_horarios_ver2.php?_persona="+idper, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function recargar_datos(idper){	
	var resultado = document.getElementById('recargo_datos');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_persona_mod2.php?_persona="+idper, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function recargar_cambiohora(idper){	
	var resultado = document.getElementById('recargo_cambio');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_horarios_ver_cambio.php?_persona="+idper, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function horarios(codest,semestre,carrera){	
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_horarios.php?_codest="+codest+"&_semestre="+semestre+"&_carrera="+carrera+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function horarios_cambio(codest,semestre,carrera){	
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_horarios_cambio.php?_codest="+codest+"&_semestre="+semestre+"&_carrera="+carrera+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function reportes(){	
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_reportes.php?", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

function reportesadm(){	
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel_reportes_adm.php?", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function guardar_evaluacion2(){
	var formdata = new FormData(document.getElementById('form_eval'));
	var resultado = document.getElementById('mos_Eval');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_evaluacion.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}

function verificar_uno(nbp, idr){
param=0;
var cuales = param; 
var suma = 0;
var marcando;
var checks = document.getElementsByName(nbp+'[]');
for (var i = 0, j = checks.length; i < j; i++) { 
    // sumar los marcados
   if(checks[i].checked == true){
    suma++;
    }    
 	//marcar desmarcar todos (la función recibe un parámetro   
    if(cuales == 'todos'){
    checks[i].checked = true;
    marcando = 1; // ponemos marcando en 1 para saber que no nos interesa procesar el form por ahora
    }    
    if(cuales == 'nada'){
    checks[i].checked = false;
    marcando = 1;
    }
} 
    if(suma == 0){
	    //alert('Debes seleccionar un registro');
	   // return false;
    }else{
    //Confirmación para uno o varios    
        if(suma >= 3){
        	var texto = 'Solo puede seleccionar dos opciones';
        	document.getElementById(idr).checked=false;
        	var pregunta = alert(texto);
        }else{
        	var texto = '';
        }
    }  	
}
function edit_pass(codest){
	var resultado = document.getElementById('modals');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/edit_pass.php?_codest="+codest+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function editar_contrass(){
	var formdata = new FormData(document.getElementById('pass'));
	var resultado = document.getElementById('modals');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/modificar_contrasenia.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
function mostrarPassword(pass){
	var cambio = document.getElementById(pass);
	if(cambio.type == "password"){
		cambio.type = "text";
		$('.span').removeClass('icon-feather-eye-off').addClass('icon-feather-eye-off');
	}else{
		cambio.type = "password";
		$('.span').removeClass('icon-feather-eye').addClass('icon-feather-eye');
	}
} 
function abrir_cerrar_inv(codmat,codest,periodo,idgrupo,subgrupo){
	var res = document.getElementById('page-content-inner');	
	res.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";		
	otro = objetoAjax();
	otro.open("GET", "contenedor/ver_boletin_inv.php?_codmat="+codmat+"&_codest="+codest+"&_per="+periodo+"&_idgrupo="+idgrupo+"&_subgrupo="+subgrupo+"", true);
	otro.onreadystatechange = function(){
		if (otro.readyState == 4) {
			res.innerHTML = otro.responseText;
		}
	}
	otro.send(null);
}

function ver_descmat_inv(codest,idgruporaiz,idgrupo,nbmat,subgrupo){
	var resultado = document.getElementById('page-content-inner');
	var datos = new FormData();
	datos.append("_codest", codest);
	datos.append("_idgrupo_raiz", idgruporaiz);	
	datos.append("_idgrupo", idgrupo);
	datos.append("_nbmat", nbmat);
	datos.append("_sub_grupo", subgrupo);
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_materia_inv.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function abrir_cerrar_sub(codmat,codest,periodo,idgrupo,subgrupo){
	var res = document.getElementById('page-content-inner');	
	res.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";		
	otro = objetoAjax();
	otro.open("GET", "contenedor/ver_boletin_practica.php?_codmat="+codmat+"&_codest="+codest+"&_per="+periodo+"&_idgrupo="+idgrupo+"&_subgrupo="+subgrupo+"", true);
	otro.onreadystatechange = function(){
		if (otro.readyState == 4) {
			res.innerHTML = otro.responseText;
		}
	}
	otro.send(null);
}

function abrir_cerrar_invertida(codmat,codest,periodo,idgrupo,subgrupo){
	var res = document.getElementById('page-content-inner');	
	res.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";		
	otro = objetoAjax();
	otro.open("GET", "contenedor/ver_boletin_invertida.php?_codmat="+codmat+"&_codest="+codest+"&_per="+periodo+"&_idgrupo="+idgrupo+"&_subgrupo="+subgrupo+"", true);
	otro.onreadystatechange = function(){
		if (otro.readyState == 4) {
			res.innerHTML = otro.responseText;
		}
	}
	otro.send(null);
}

function ver_descmat_sub(codmat,codest,periodo,idgrupo,subgrupo,nbmat,doc,subgru){
	var resultado = document.getElementById('page-content-inner');
	var datos = new FormData();
	datos.append("_codmat", codmat);
	datos.append("_codest", codest);
	datos.append("_periodo", periodo);
	datos.append("_idgrupo", idgrupo);
	datos.append("_subgrupo", subgrupo);
	datos.append("_nbmat", nbmat);
	datos.append("_doc", doc);
	datos.append("_subgru", subgru);
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_materia_sub_grupo.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function ver_mat_subinvertida(codmat,codest,periodo,idgrupo,subgrupo,nbmat,doc,subgru){
	var resultado = document.getElementById('page-content-inner');
	var datos = new FormData();
	datos.append("_codmat", codmat);
	datos.append("_codest", codest);
	datos.append("_periodo", periodo);
	datos.append("_idgrupo", idgrupo);
	datos.append("_subgrupo", subgrupo);
	datos.append("_nbmat", nbmat);
	datos.append("_doc", doc);
	datos.append("_subgru", subgru);
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_materia_claseinvertida.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function registrar_asistencias_meet_sub(grupo,raiz,codest,subgrupo){
	var resultado = document.getElementById('div_googlemeet_link');
	var datos = new FormData();
	datos.append("_idgrupo", grupo);
	datos.append("_idgruporaiz", raiz);
	datos.append("_codest", codest);
	datos.append("_subgrupo", subgrupo);
	resultado.innerHTML="Cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/set_asistencia_enlacemeet_sub.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML=ajax.responseText;
		}
	}
	ajax.send(datos);
}
function registrar_subgrupo(codest,per,id_grupo,codmat){
	var res = document.getElementById('page-content-inner');	
	res.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";		
	otro = objetoAjax();
	otro.open("GET", "contenedor/inscripcion_subgrupo.php?_codest="+codest+"&_periodo="+per+"&_idgrupo="+id_grupo+"&_codmat="+codmat+"", true);
	otro.onreadystatechange = function(){
		if (otro.readyState == 4) {
			res.innerHTML = otro.responseText;
		}
	}
	otro.send(null);
}
function guardar_subgrupo(){
	var formdata = new FormData(document.getElementById('form_reg'));
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_subgrupo.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
function registrar_estsubgrupo(codest,per,id_grupo,subgrupo,mat){
	var res = document.getElementById('page-content-inner');	
	res.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";		
	otro = objetoAjax();
	otro.open("GET", "contenedor/inscripcion_estsubgrupo.php?_codest="+codest+"&_periodo="+per+"&_idgrupo="+id_grupo+"&_subgrupo="+subgrupo+"&_mat="+mat+"", true);
	otro.onreadystatechange = function(){
		if (otro.readyState == 4) {
			res.innerHTML = otro.responseText;
		}
	}
	otro.send(null);
}

function verificar_qr2(seguro){	
	var resultado = document.getElementById('mos_qr');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/estado_cuenta_qr_seguro.php?_seguro="+seguro, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
	return true;
}

function estado_pago(codest,semestre,carrera){
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/estado_cuenta.php?_codest="+codest+"&_semestre="+semestre+"&_carrera="+carrera+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

function verificar_qr(codest,semestre,carrera,cuota){	
	var resultado = document.getElementById('mos_qr');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/estado_cuenta_qr.php?_codest="+codest+"&_semestre="+semestre+"&_carrera="+carrera+"&_cuota="+cuota+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
	return true;
}

function verificar_qr_bcp(codest,semestre,carrera,cuota, monto, tipo_cuota, periodo){	
	var resultado = document.getElementById('mos_qr');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/estado_cuenta_qr.php?_codest="+codest+"&_semestre="+semestre+"&_carrera="+carrera+"&_cuota="+cuota+"&_monto="+monto+"&_tipoCuota="+tipo_cuota+"&_periodo="+periodo+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
	return true;
}

function verificar_qr_bcp_repro(codest,semestre,carrera,cuota, monto, tipo_cuota, mSigla, materia, periodo){	
	var resultado = document.getElementById('mos_qr');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/estado_cuenta_qr.php?_codest="+codest+"&_semestre="+semestre+"&_carrera="+carrera+"&_cuota="+cuota+"&_monto="+monto+"&_tipoCuota="+tipo_cuota+"&_msigla="+mSigla+"&_materia="+materia+"&_periodo="+periodo+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
	return true;
}

function subir_recibo(codest, periodo, tipodeuda, cuota){	
	var resultado = document.getElementById('mos_qr');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/estado_cuenta_recibo.php?_codest="+codest+"&periodo="+periodo+"&tipodeuda="+tipodeuda+"&cuota="+cuota+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
	return true;
}

function set_archivo_recibo_cloud(){
	var formdata = new FormData(document.getElementById('envio_recibo'));
	var resultado = document.getElementById('msj');
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/epagos/frm_guarda_recibo_bucketgoogle.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	// ajax.setRequestHeader("Content-Type","multipart/form-data");
	ajax.send(formdata);
	return true;
}

function set_archivo_recibo(){
	var formdata = new FormData(document.getElementById('envio_recibo'));
	var resultado = document.getElementById('msj');
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/epagos/frm_guarda_recibo.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	// ajax.setRequestHeader("Content-Type","multipart/form-data");
	ajax.send(formdata);
	return true;
}

function quitar_recibo(id_recibo){
	var resultado = document.getElementById('content_recibo');
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/epagos/quitar_recibo.php?idrecibo="+id_recibo+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function guardar_encuesta(){
	var formdata = new FormData(document.getElementById('frm_encuesta'));
	var resultado = document.getElementById('resultado');
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_encuesta.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	// ajax.setRequestHeader("Content-Type","multipart/form-data");
	ajax.send(formdata);
	return true;
}
function guardar_encuesta_prac(){
	var formdata = new FormData(document.getElementById('frm_encuesta_prac'));
	var resultado = document.getElementById('resultado');
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_encuesta_prac.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	// ajax.setRequestHeader("Content-Type","multipart/form-data");
	ajax.send(formdata);
	return true;
}
function ver_pagos(codest,periodo,parcial){
	var resultado = document.getElementById('mos_pagos');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/form_verpagos.php?_codest="+codest+"&_periodo="+periodo+"&_parcial="+parcial, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

function reglamentos(){
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/reglamentos.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

function biblioteca(codest,carrera){
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/biblioteca_virtual.php?_codest="+codest+"&_carrera="+carrera, true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);

}
function verlibros(busca,codest,carrera){	
	var resultado = document.getElementById('libros');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/libros_ver.php?_codest="+codest+"&_buscador="+busca+"&_carrera="+carrera+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

function ver_biblio(codest, idgrupo, idgrupo_raiz){

	try{
		regresarA_bandejaprincipal();
	} catch (E) {

	}
	
	var resultado = document.getElementById('page-content-inner');

	var datos = new FormData();
	datos.append("_codest", codest);
	datos.append("_idgrupo", idgrupo);
	datos.append("_idgruporaiz", idgrupo_raiz);

	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/ver_bibliografia_basica.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function libro_server(enlace){
	var resultado = document.getElementById('libros');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/libros_server_ver.php?_enlace="+enlace+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function practicas(codest,semestre,carrera){	
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/reg_practicas_all.php?_codest="+codest+"&_semestre="+semestre+"&_carrera="+carrera+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}
function guardar_subgrupo_all(){
	var formdata = new FormData(document.getElementById('form_reg'));
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/reg_practicas_all_registra.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}
function guardar_subgrupo_all_niv(){
	var formdata = new FormData(document.getElementById('form_reg_niv'));
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/reg_practicas_all_registra_niv.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}

function ver_mi_examen(banco, codest, ponderacion){
	var resultado=document.getElementById('mod_evaluacion');

	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	datos = new FormData();
	datos.append("cod_banco", banco);
	datos.append("codest", codest);
	datos.append("ponde", ponderacion);

	ajax = objetoAjax();
	ajax.open("POST", "contenedor/ver_mi_examen.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}
function guardar_solicitud(){
	var op1=document.getElementById('op1').value;
	var op2=document.getElementById('op2').value;
	var op3=document.getElementById('op3').value;
	if (op1=='0') {
		alert("Tiene que seleccionar un hospital en la opcion 1");
		return false;
	}else{
		if (op2=='0') {
			alert("Tiene que seleccionar un hospital en la opcion 2");
			return false;	
		}else{
			if (op3=='0') {
				alert("Tiene que seleccionar un hospital en la opcion 3");
				return false;
			}else{
				var formdata = new FormData(document.getElementById('sol_int'));
				var resultado = document.getElementById('resul_sol');
				resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
				ajax = objetoAjax();
				ajax.open("POST", "contenedor/frm_guarda_sol_int.php", true);
				ajax.onreadystatechange = function(){
					if (ajax.readyState == 4) {
						resultado.innerHTML = ajax.responseText;
					}
				}
				ajax.send(formdata);
				return true;
			}
		}
	}
}

function registrar_evento_recurso(url, t_evento){
	var resultado=document.getElementById('mod_test');
	datos = new FormData();
	datos.append("url", url);
	datos.append("tipo_evento", t_evento);

	ajax = objetoAjax();
	ajax.open("POST", "contenedor/reg_evento_recurso.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}