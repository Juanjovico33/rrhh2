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
function cargar_datos_clase(item, grupo, clase, estudiante, grupo_aux, sub_grupo){
	var resultado=document.getElementById('contenido_cv');

	datos = new FormData();
	datos.append("_item", item);
	datos.append("_idgrupo", grupo);
	datos.append("_idclase", clase);
	datos.append("_codest", estudiante);
	datos.append("_grupoAux", grupo_aux)
	datos.append("_subgrupo", sub_grupo)

	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cv_momentos_all.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function cargar_datos_clase_asincronica(item, grupo, clase, estudiante, grupo_aux, sub_grupo){
	var resultado=document.getElementById('contenido_cv');

	datos = new FormData();
	datos.append("_item", item);
	datos.append("_idgrupo", grupo);
	datos.append("_idclase", clase);
	datos.append("_codest", estudiante);
	datos.append("_grupoAux", grupo_aux)
	datos.append("_subgrupo", sub_grupo)

	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cv_momentos_asincronica.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function cargar_datos_clase_invertida(item, grupo, clase, estudiante, grupo_aux, sub_grupo){
	var resultado=document.getElementById('contenido_cv');

	datos = new FormData();
	datos.append("_item", item);
	datos.append("_idgrupo", grupo);
	datos.append("_idclase", clase);
	datos.append("_codest", estudiante);
	datos.append("_grupoAux", grupo_aux)
	datos.append("_subgrupo", sub_grupo)

	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cv_momentos_invertida.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

// function ver_clasesvirtuales(idgrupo, codest){
// 	var divmenus=document.getElementById('page_menu_inner');
// 	var menuprincipal = document.getElementById('menu_main');
// 	var menuclases = document.createElement('ul');
// 	menuclases.id = 'menu_clases';
// 	datos = new FormData();
// 	datos.append("_idgrupo", idgrupo);
// 	datos.append("_codest", codest);
// 	ajax = objetoAjax();
// 	ajax.open("POST", "contenedor/cv_menu_clases.php", true);
// 	ajax.onreadystatechange = function(){
// 		if (ajax.readyState == 4) {
// 			menuclases.innerHTML=ajax.responseText;
// 			// NewElement.innerHTML = ajax.responseText;
// 			// divmenus.appendChild(menuprincipal);
// 			divmenus.innerHTML = ajax.responseText;
// 		}
// 	}
// 	ajax.send(datos);
// }

function ver_clasesvirtuales(idgrupo, idgrupoRaiz, codest, subgrupo){
	var mainpage = document.getElementById('wrapper');
	var clasesvirtuales = document.getElementById('wrapper_cv');
	mainpage.style.display ="none";
	datos = new FormData();
	datos.append("_idgrupo", idgrupo);
	datos.append("_codest", codest);
	datos.append("_idgrupoRaiz", idgrupoRaiz);
	datos.append("_subgrupo", subgrupo);
	// datos.append("_codmat", mat);
	// datos.append("_per", per);
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cv_clasesvirtuales_page.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			clasesvirtuales.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}
function regresarA_bandejaprincipal(){
	var mainpage = document.getElementById('wrapper');
	var clasesvirtuales = document.getElementById('wrapper_cv');
	mainpage.style.display ="block";
	clasesvirtuales.innerHTML ='';
}

function get_CV_momento1(div, idgrupo, idclase){
	// var resultado = document.getElementById('div_cv');
	_div=document.getElementById(div);
	datos = new FormData();
	datos.append("_grupo", idgrupo);
	datos.append("_clase", idclase);
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/view_clase_momento1.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			_div.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}
function get_CV_momento2(div, idgrupo, idclase, codest){
	// var resultado = document.getElementById('div_cv');
	_div=document.getElementById(div);
	datos = new FormData();
	datos.append("_grupo", idgrupo);
	datos.append("_clase", idclase);
	datos.append("_estudiante", codest);
	datos.append("_div", div);
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/view_clase_momento2.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			_div.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}
function get_CV_momento3(div, idgrupo, idclase, codest){
	// var resultado = document.getElementById('div_cv');
	_div=document.getElementById(div);
	datos = new FormData();
	datos.append("_grupo", idgrupo);
	datos.append("_clase", idclase);
	datos.append("_codest", codest);
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/view_clase_momento3.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			_div.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}
function get_CV_momento4(div, idgrupo, idclase, codest){
	// var resultado = document.getElementById('div_cv');
	_div=document.getElementById(div);
	datos = new FormData();
	datos.append("_grupo", idgrupo);
	datos.append("_clase", idclase);
	datos.append("_codest", codest);
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/view_clase_momento4.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			_div.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}
function get_CV_momento5(div, idgrupo, idclase, codest){
	// var resultado = document.getElementById('div_cv');
	_div=document.getElementById(div);
	datos = new FormData();
	datos.append("_grupo", idgrupo);
	datos.append("_clase", idclase);
	datos.append("_codest", codest);
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/view_clase_momento5.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			_div.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function get_momentos(item, grupo, clase, estudiante){
	$m1="clase"+item+"_m"+"1";
	$m2="clase"+item+"_m"+"2";
	$m3="clase"+item+"_m"+"3";
	$m4="clase"+item+"_m"+"4";
	$m5="clase"+item+"_m"+"5";

	// alert(item+"-"+grupo+"-"+clase+"-"+estudiante);

	// get_CV_momento1($m1, grupo, clase);
	// get_CV_momento2($m2, grupo, clase, estudiante);
	// get_CV_momento3($m3, grupo, clase, estudiante);
	// get_CV_momento4($m4, grupo, clase);
	// get_CV_momento5($m5, grupo, clase);
	
}
// function set_M2preguntaforo(grupo, clase, estudiante, foro, div){
function set_M2preguntaforo(grupo, raiz, clase, estudiante, foro, nbgrupo){
	// var resultado = document.getElementById('div_cv');
	var resultado = document.getElementById(nb_msj='msjm2'+clase);
	var datos = new FormData();
	datos.append("_grupo", grupo);
	datos.append("_idraiz", raiz);
	datos.append("_clase", clase);
	datos.append("_estudiante", estudiante);
	datos.append("_foro", foro);
	datos.append("_nbgrupo", nbgrupo);
	datos.append("_pregunta", document.getElementById('text_pregunta_'+clase).value);
	
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/set_preguntaforo_m2.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function set_M2preguntaforo_snAsis(grupo, raiz, clase, estudiante, foro, nbgrupo){
	// var resultado = document.getElementById('div_cv');
	var resultado = document.getElementById(nb_msj='msjm2'+clase);
	var datos = new FormData();
	datos.append("_grupo", grupo);
	datos.append("_idraiz", raiz);
	datos.append("_clase", clase);
	datos.append("_estudiante", estudiante);
	datos.append("_foro", foro);
	datos.append("_nbgrupo", nbgrupo);
	datos.append("_pregunta", document.getElementById('text_pregunta_'+clase).value);
	
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/set_preguntaforo_m2_snAsis.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function set_resumen_m3(clase, grupo,estudiante, video){
	var resu=document.getElementById('resumen'+clase+video).value;
	var resultado = document.getElementById('msj'+clase);
	var datos = new FormData();
	datos.append("_clase", clase);
	datos.append("_grupo", grupo);
	datos.append("_estudiante", estudiante);
	datos.append("_resumen", resu);
	datos.append("_video", video);

	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/frm_guarda_resumen.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
	document.getElementById('resu'+cod_cla).disabled=true;
}

function set_resumen_m4(grupo, raiz, codtarea, codest, nbgrupo){
	var resumen=document.getElementById('tarea'+codtarea).value;
	var resultado = document.getElementById('msj_ta'+codtarea);

	var datos = new FormData();
	datos.append("_grupo", grupo);
	datos.append("_raiz", raiz);
	datos.append("_codtarea", codtarea);
	datos.append("_resumen", resumen);
	datos.append("_codest", codest);
	datos.append("_nbgrupo", nbgrupo);

	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/frm_guarda_resumen_m4.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function set_resumen_m4_snAsis(grupo, raiz, codtarea, codest, nbgrupo){
	var resumen=document.getElementById('tarea'+codtarea).value;
	var resultado = document.getElementById('msj_ta'+codtarea);

	var datos = new FormData();
	datos.append("_grupo", grupo);
	datos.append("_raiz", raiz);
	datos.append("_codtarea", codtarea);
	datos.append("_resumen", resumen);
	datos.append("_codest", codest);
	datos.append("_nbgrupo", nbgrupo);

	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/frm_guarda_resumen_m4_snAsis.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(datos);
}

function set_archivo_m4(cod_tarea){
	var formdata = new FormData(document.getElementById('envio_arch'+cod_tarea));
	var resultado = document.getElementById('msj_ta'+cod_tarea);
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/frm_guarda_archivo_m4.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}

function set_archivo_m4_snAsis(cod_tarea){
	var formdata = new FormData(document.getElementById('envio_arch'+cod_tarea));
	var resultado = document.getElementById('msj_ta'+cod_tarea);
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/frm_guarda_archivo_m4_snAsis.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}

function msj_error(cod_eval, cod_ban, codest){
	var resultado = document.getElementById('contenido_cv');
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/frm_error.php", true);
	//window.open("ver_actividades_eval.php?cod_eval="+cod_eval+"&cod_ban="+cod_ban+"&codest="+codest+"",'_blank');
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

function iniciar_eval(cod_eval, cod_ban, codest, codgrupo, idclase, codgrupom5, subgrupo,tipos){
	var resultado = document.getElementById('contenido_cv');
	resultado.innerHTML = "<img src='contenedor/js/carga.gif' class='img-fluid' alt='Responsive image'>";
	ajax = objetoAjax();
	ajax.open("GET", "contenedor/ver_actividades_eval.php?cod_eval="+cod_eval+"&cod_ban="+cod_ban+"&codest="+codest+"&id_grupo="+codgrupo+"&id_clase="+idclase+"&cod_grupom5="+codgrupom5+"&sub_grupo="+subgrupo+"&tipoeval="+tipos+"", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
			var myScripts = resultado.getElementsByTagName("script");
			if (myScripts.length > 0) {
				eval(myScripts[0].innerHTML);
			}
		}
	}
	ajax.send(null);
}

function guardar_eval(){
	var formdata = new FormData(document.getElementById('form_eval'));
	var resultado = document.getElementById('contenido_cv');
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_eval.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}

function set_archivo_m4_cloud(cod_tarea){
	var formdata = new FormData(document.getElementById('envio_arch'+cod_tarea));
	var resultado = document.getElementById('msj_ta'+cod_tarea);
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/frm_guarda_archivo_m4_bucketgoogle.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	// ajax.setRequestHeader("Content-Type","multipart/form-data");
	ajax.send(formdata);
	return true;
}

function set_archivo_m4_cloud_snAsis(cod_tarea){
	var formdata = new FormData(document.getElementById('envio_arch'+cod_tarea));
	var resultado = document.getElementById('msj_ta'+cod_tarea);
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/cvirtuales/frm_guarda_archivo_m4_bucketgoogle_snAsis.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	// ajax.setRequestHeader("Content-Type","multipart/form-data");
	ajax.send(formdata);
	return true;
}

function guardar_diagnostico(){
	var formdata = new FormData(document.getElementById('frm_diagnostico'));
	var resultado = document.getElementById('contenido_cv');
	resultado.innerHTML = "cargando...";
	ajax = objetoAjax();
	ajax.open("POST", "contenedor/frm_guarda_eval_daignostico.php", true);
	ajax.onreadystatechange = function(){
		if (ajax.readyState == 4) {
			resultado.innerHTML = ajax.responseText;
		}
	}
	ajax.send(formdata);
	return true;
}