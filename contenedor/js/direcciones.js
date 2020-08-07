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
	var res = document.getElementById('cuerpo_modal');
	var titu=document.getElementById('myModalLabel');
	res.innerHTML = "<img src='contenedor/js/carga.gif' width='50%' class='img-fluid' alt='Responsive image'>";	
	titu.innerHTML="Primera Evaluación Online es de calificación inmediata por computadora";
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
	var resultado = document.getElementById('contenedor');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/ver_actividades_evaluacion.php?cod_act="+cod_act+"&cod_ban="+cod_ban+"&codest="+codest+"&cod_gru_aux="+cod_gru_aux+"&h_fin="+hora_fin+"",true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
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
	/*window.open("ver_actividades_evaluacion.php?cod_act="+cod_act+"&cod_ban="+cod_ban+"&codest="+codest+"&cod_gru_aux="+cod_gru_aux+"","_self");*/
}
function abrir_cerrar(codmat,codest,periodo,idgrupo){
	var res = document.getElementById('page-content-inner');
	//var titu=document.getElementById('myModalLabel');
	res.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";	
	//titu.innerHTML="BOLETIN DE NOTAS";
	otro = objetoAjax();
	otro.open("GET", "contenedor/ver_boletin.php?_codmat="+codmat+"&_codest="+codest+"&_per="+periodo+"&_idgrupo="+idgrupo+"", true);
	otro.onreadystatechange = function(){
		if (otro.readyState == 4) {
			res.innerHTML = otro.responseText;
		}
	}
	otro.send(null);
}
function ver_materias(codest){
	var resultado = document.getElementById('page-content-inner');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/panel.php?_codest="+codest, true);
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
function guardar_evaluacion(){
	var formdata = new FormData(document.getElementById('form_eval'));
	var res = document.getElementById('cuerpo_modal');
	var titu=document.getElementById('myModalLabel');
	res.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	titu.innerHTML="Examén Online Resultado";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_evaluacion.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			res.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
}

function ver_descmat(codmat,codest,periodo,idgrupo, nbmat){
	var resultado = document.getElementById('page-content-inner');
	var datos = new FormData();

	datos.append("_codmat", codmat);
	datos.append("_codest", codest);
	datos.append("_periodo", periodo);
	datos.append("_idgrupo", idgrupo);
	datos.append("_nbmat", nbmat);

	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_materia.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}
function ver_plamat(idgrupo){
	var resultado = document.getElementById('page-content-inner');

	var datos = new FormData();
	datos.append("_idgrupo", idgrupo);

	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/ver_planificacion_grupo.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function registrar_asistencias_meet(grupo,codest){
	var resultado = document.getElementById('div_googlemeet_link');
	var datos = new FormData();
	datos.append("_idgrupo", grupo);
	datos.append("_codest", codest);
	resultado.innerHTML="Cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/set_asistencia_enlacemeet.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML=ajax.responseText;
		}
	}
	ajax.send(datos);
}