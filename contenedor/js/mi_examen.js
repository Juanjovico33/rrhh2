var array_pr;
var preg_sinresp;

function iniciar_preguntas(){

    preguntas = document.getElementsByName('preg[]');
    cadena="";
    array_pr=[];
    cantidad =document.getElementsByName('preg[]').length;

    for(i=0; i<cantidad; i++){
        array_pr[i] = [preguntas[i].value, null];
    }
    // console.log(array_pr);
    document.getElementById("resul").value = JSON.stringify(array_pr);
}

function set_respuesta(pregunta, nro_pregunta, nb_campo){

    respuestas = document.getElementsByName(nb_campo);
    cantidad_op_resp = document.getElementsByName(nb_campo).length;
    indice=nro_pregunta-1;
    // console.log(pregunta+"-"+nro_pregunta+"-"+nb_campo);
    cantidad_resp=0; opciones=[];
    for (i=0; i<cantidad_op_resp; i++){

        if(respuestas[i].checked==true){
            // resp=respuestas[i].value;
            resp=respuestas[i].getAttribute('id');
            opciones.push(resp);
            cantidad_resp=cantidad_resp+1;
        }
        
    }
    // console.log(cantidad_resp);
    // console.log(indice);
    // console.log(pregunta);
    // console.log(opciones);
    
    if(cantidad_resp==0){
        array_pr[indice][1]=null;
    }else{
        array_pr[indice][1]=[];
        array_pr[indice][1]=opciones;
    }
    // console.log(array_pr);
    document.getElementById("resul").value = JSON.stringify(array_pr);
}

function validar_respuestas(){
    preg_sinresp=[];
    for(i=0; i<array_pr.length; i++){
        $preg = array_pr[i];
        if(!Array.isArray($preg[1])){
            preg_sinresp.push(i+1);
        }
    }
    return preg_sinresp.toString();
}