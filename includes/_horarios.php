<?php
    class horarios{
        private $en_hora;
        private $fecha_actual;
        private $hora_actual;
        private $hora_reg_est;
        private $ido_moments;
        private $minuto_tolerancia;

        private $error;

        function __contruct(){
            $this->en_hora=false;
            $this->fecha_actual = "0000-00-00";
            $this->hora_actual = "00:00:00";
            $this->minuto_tolerancia=10;
            $this->hora_reg_est="null";
            $this->ido_moments=0;
        }

        public function getIdoMoments(){
            return $this->ido_moments;
        }
        public function setIdoMoments($_puedemomentos){
            $this->ido_moments=$_puedemomentos;
        }

        public function getHoraRegistrada(){
            return $this->hora_reg_est;
        }
        public function setHoraRegistrada($_horaRegistrada){
            $this->hora_reg_est=$_horaRegistrada;
        }

        public function getError(){
            return $this->error;
        }
        public function setError($_error){
            $this->error=$_error;
        }

        // OK
        function horario_de_hoy_teorico($_codgrupo){
            include "conexion.php";

            $dias=array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $this->fecha_actual = $fec = $fecha->format('Y-m-d');
            $this->hora_actual = $hor = $fecha->format('H:i:s');
            $dia = $fecha->format('N');
            $txt_dia=$dias[$dia];
            // setlocale(LC_ALL, 'es_ES.UTF-8');
            // $txt_dia = strftime("%A",$fecha->getTimestamp());
            // echo $txt_dia."- $dia<br>";
            $txt_horarios="";
            $color="";

            $q_horariohoy="SELECT h.hr_entrada, h.hr_salida, tc.opcion as des, tc.id FROM grupos_horario h LEFT JOIN grupos_tipoclase tc ON h.tipo_clase=tc.id WHERE h.cod_gru=$_codgrupo and h.dia=$dia and h.cod_subgru=0 and h.cod_gru<>0;";

            try {
                $ob_horariohoy = $bdcon->prepare($q_horariohoy);
                $ob_horariohoy->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener horarios ha fallado' . $e->getMessage().'<br>';
            }
            
            if($ob_horariohoy->rowCount()>0){
                while ($row = $ob_horariohoy->fetch(PDO::FETCH_ASSOC)) {
                    $entrada=$row['hr_entrada'];
                    $salida=$row['hr_salida'];
                    $descripcion=$row['des'];
                    $tipo_clase=$row['id'];
                    $color="#000000";
                    if($tipo_clase==5 || $tipo_clase==6){
                        $color="#008000";
                    }else{
                        $color="#000000";
                        $txt_horarios.=$txt_dia." ($entrada - $salida) - <font color='$color'>".$descripcion."</font><br>";
                    }
                    // $txt_horarios.=$txt_dia." ($entrada - $salida) - <font color='$color'>".$descripcion."</font><br>";
                }
                if($txt_horarios==""){
                    $txt_horarios="No existe horario para hoy.";
                }
            }else{
                $txt_horarios="No existe horario para hoy.";
            }
            return $txt_horarios;
        }

        function horario_de_hoy_subgrupos($_codsubgrupo){
            include "conexion.php";

            $dias=array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $this->fecha_actual = $fec = $fecha->format('Y-m-d');
            $this->hora_actual = $hor = $fecha->format('H:i:s');
            $dia = $fecha->format('N');
            $txt_dia=$dias[$dia];
            // setlocale(LC_ALL, 'es_ES.UTF-8');
            // $txt_dia = strftime("%A",$fecha->getTimestamp());
            // echo $txt_dia."- $dia<br>";
            $txt_horarios="";

            $q_horariohoy="SELECT hr_entrada, hr_salida FROM grupos_horario WHERE dia=$dia and cod_subgru=$_codsubgrupo";

            try {
                $ob_horariohoy = $bdcon->prepare($q_horariohoy);
                $ob_horariohoy->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener horarios ha fallado' . $e->getMessage().'<br>';
            }
            
            if($ob_horariohoy->rowCount()>0){
                while ($row = $ob_horariohoy->fetch(PDO::FETCH_ASSOC)) {
                    $entrada=$row['hr_entrada'];
                    $salida=$row['hr_salida'];
                    $txt_horarios.=$txt_dia." ($entrada - $salida)<br>";
                }
            }else{
                $txt_horarios="No existe horario para hoy.";
            }
            return $txt_horarios;
        }

        // OK
        function enHora_to_Asistencia($_codgrupo){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            
            $this->fecha_actual = $fec = $fecha->format('Y-m-d');
            $this->hora_actual = $hor = $fecha->format('H:i:s');
            $dia = $fecha->format('N');

            $q_enhora="SELECT * FROM grupos_horario WHERE cod_gru=$_codgrupo and dia=$dia and ('$hor'>=hr_entrada - INTERVAL 10 MINUTE) and '$hor'<=hr_salida";
            // $this->error.= $q_enhora;
            try {
                $ob_enhora = $bdcon->prepare($q_enhora);
                $ob_enhora->execute();
            } catch (PDOException $e) {   
                $this->error .= 'La conexión para obtener horarios ha fallado' . $e->getMessage().'<br>';
                return false;
            }
            
            if($ob_enhora->rowCount()>0){
                $this->en_hora=true;
            }else{
                $this->en_hora=false;
            }
            return $this->en_hora;
        }

        function enHora_to_Asistencia_subGrupos($_codsubgrupo){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            
            $this->fecha_actual = $fec = $fecha->format('Y-m-d');
            $this->hora_actual = $hor = $fecha->format('H:i:s');
            $dia = $fecha->format('N');

            $q_enhora="SELECT * FROM grupos_horario WHERE cod_subgru=$_codsubgrupo and dia=$dia and ('$hor'>=hr_entrada - INTERVAL 10 MINUTE) and '$hor'<=hr_salida";
            // $this->error.= $q_enhora;
            try {
                $ob_enhora = $bdcon->prepare($q_enhora);
                $ob_enhora->execute();
            } catch (PDOException $e) {   
                $this->error .= 'La conexión para obtener horarios ha fallado' . $e->getMessage().'<br>';
                return false;
            }
            
            if($ob_enhora->rowCount()>0){
                $this->en_hora=true;
            }else{
                $this->en_hora=false;
            }
            return $this->en_hora;
        }

        // ok
        function enable_to_Moments($grupo_raiz, $codest, $id_grupo){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $_fec = $fecha->format('Y-m-d');
            $_hor = $fecha->format('H:i:s');
            $_dia = $fecha->format('N');
            $entrada="";
            $salida="";
            $id_switch=0;

            try{
                $q="SELECT cod, flag FROM aa_clases_virtuales_momentos_flag WHERE fecha='$_fec' AND codest=$codest AND id_grupo=$id_grupo AND (id_subgru is null or id_subgru=0)";
                $resul = $bdcon->prepare($q);
                $resul->execute();
                $count_resul=$resul->rowCount();
                if($count_resul==1){
                    while ($row2 = $resul->fetch(PDO::FETCH_ASSOC)) {
                        $id_switch=$row2['cod'];
                        $this->setIdoMoments($row2['flag']);
                    }
                }else if($count_resul==0){ 
                    $id_switch=0;
                }else{
                    $q2="SELECT cod FROM aa_clases_virtuales_momentos_flag WHERE fecha='$_fec' AND codest=$codest AND id_grupo=$id_grupo AND (flag=1 or flag=0) order by flag desc limit 1";
                    $resul2 = $bdcon->prepare($q2);
                    $resul2->execute();
                    if($resul2->rowCount()==1){
                        while ($row3 = $resul2->fetch(PDO::FETCH_ASSOC)) {
                            $id_switch=$row3['cod'];
                        }
                    }else{
                        $id_switch=0;
                    }
                }

            }catch(PDOException $e){
                $id_switch=0;
                $this->error .= 'La conexión para obtener asistencias ha fallado' . $e->getMessage().'<br>';
            }
            return $id_switch;
        }

        function enable_to_Moments_sub($grupo_raiz, $codest, $id_grupo, $id_subgrupo){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $_fec = $fecha->format('Y-m-d');
            $id_switch=0;

            try{
                $q="SELECT cod, flag FROM aa_clases_virtuales_momentos_flag WHERE fecha='$_fec' AND codest=$codest AND id_grupo=$id_grupo AND id_subgru=$id_subgrupo";
                $resul = $bdcon->prepare($q);
                $resul->execute();
                $count_resul=$resul->rowCount();
                if($count_resul==1){
                    while ($row2 = $resul->fetch(PDO::FETCH_ASSOC)) {
                        $id_switch=$row2['cod'];
                        $this->setIdoMoments($row2['flag']);
                    }
                }else if($count_resul==0){ 
                    $id_switch=0;
                }else{
                    $q2="SELECT cod FROM aa_clases_virtuales_momentos_flag WHERE fecha='$_fec' AND codest=$codest AND id_grupo=$id_grupo AND (flag=1 or flag=0) AND id_subgru=$id_subgrupo order by flag desc limit 1";
                    $resul2 = $bdcon->prepare($q2);
                    $resul2->execute();
                    if($resul2->rowCount()==1){
                        while ($row3 = $resul2->fetch(PDO::FETCH_ASSOC)) {
                            $id_switch=$row3['cod'];
                        }
                    }else{
                        $id_switch=0;
                    }
                }

            }catch(PDOException $e){
                $id_switch=0;
                $this->error .= 'La conexión para obtener asistencias ha fallado' . $e->getMessage().'<br>';
            }
            return $id_switch;
        }

        //ok
        function puede_to_M2($id_switch, $id_clase){
            include "conexion.php";
            $switch=0;
            if($this->enFecha_to_Momentos($id_clase)){
                $fecha = new DateTime();
                $fecha->setTimezone(new DateTimeZone('America/La_Paz'));

                $fec = $fecha->format('Y-m-d');
                $_fecha="";
                $_flag=3;
                
                $q_flags="SELECT flag, fecha FROM aa_clases_virtuales_momentos_flag WHERE cod=$id_switch and estado=1";
                try {
                    $ob_flags = $bdcon->prepare($q_flags);
                    $ob_flags->execute();
                    while ($rows = $ob_flags->fetch(PDO::FETCH_ASSOC)) {
                        $_fecha=$rows['fecha'];
                        $_flag=$rows['flag'];
                    }
                } catch (PDOException $e) {
                    $switch=0;
                    $this->error .= 'La conexión para obtener switch momentos ha fallado.' . $e->getMessage().'<br>';
                }
                if($_fecha==$fec){
                    if($_flag==1){
                        $switch=1; // puede
                    }else if($_flag==0){
                        $switch=2; // no puede porque ingreso muy tarde
                    }else{
                        $switch=0; // no se puede realizar conexion de datos
                    }
                }else if($_fecha==""){
                    $switch=0; // no se puede realizar conexion de datos
                }else{
                    $switch=3; // no esta en fecha
                }
            }else{
                $switch=3;
            }
            
            return $switch;
        }

        //ok
        function puede_to_M4($id_switch){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));

            $fec = $fecha->format('Y-m-d');
            $_fecha="";
            $_flag=3;
            $switch=0;

            $q_flags="SELECT flag, fecha FROM aa_clases_virtuales_momentos_flag WHERE cod=$id_switch and estado=1";
            try {
                $ob_flags = $bdcon->prepare($q_flags);
                $ob_flags->execute();
                while ($rows = $ob_flags->fetch(PDO::FETCH_ASSOC)) {
                    $_fecha=$rows['fecha'];
                    $_flag=$rows['flag'];
                }
            } catch (PDOException $e) {
                $switch=0;
                $this->error .= 'La conexión para obtener switch momentos ha fallado.' . $e->getMessage().'<br>';
            }

            if($_flag==1){
                $switch=1; // puede
            }else if($_flag==0){
                $switch=2; // no puede porque ingreso muy tarde
            }else{
                $switch=0; // no se puede realizar conexion de datos
            }

            return $switch;
        }

        //codigo igual a to_M4
        function puede_to_M5($id_switch){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));

            $fec = $fecha->format('Y-m-d');
            $_fecha="";
            $_flag=3;
            $switch=0;

            $q_flags="SELECT flag, fecha FROM aa_clases_virtuales_momentos_flag WHERE cod=$id_switch and estado=1";
            
            try {
                $ob_flags = $bdcon->prepare($q_flags);
                $ob_flags->execute();
                while ($rows = $ob_flags->fetch(PDO::FETCH_ASSOC)) {
                    $_fecha=$rows['fecha'];
                    $_flag=$rows['flag'];
                }
            } catch (PDOException $e) {
                $switch=0;
                $this->error .= 'La conexión para obtener switch momentos ha fallado.' . $e->getMessage().'<br>';
            }

            if($_flag==1){
                $switch=1; // puede
            }else if($_flag==0){
                $switch=2; // no puede porque ingreso muy tarde
            }else{
                $switch=0; // no se puede realizar conexion de datos
            }

            return $switch;
        }

        // ok
        function enFecha_to_Momentos($_codclase){
            include "conexion.php";

            $en_fecha=false;
            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));

            $this->fecha_actual = $fec = $fecha->format('Y-m-d');
            $this->hora_actual = $hor = $fecha->format('H:i:s');

            $q_enfecha="SELECT * FROM aa_clases_virtuales WHERE cod_clase=$_codclase and fecha_pub='$fec'";
            try {
                $ob_enfecha = $bdcon->prepare($q_enfecha);
                $ob_enfecha->execute();
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener horarios ha fallado' . $e->getMessage().'<br>';
            }
            
            if($ob_enfecha->rowCount()>0){
                $en_fecha=true;
            }else{
                $en_fecha=false;
            }
            return $en_fecha;
        }

        // OK
        function tiene_asistencia_est($grupo_main, $codest, $id_grupo){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $_fec = $fecha->format('Y-m-d');
            $_hor = $fecha->format('H:i:s');
            $_dia = $fecha->format('N');
            $entrada="";
            $salida="";
            $tiene=false;

            $q_horario_moment="SELECT hr_entrada, hr_salida FROM grupos_horario WHERE cod_gru=$grupo_main and dia=$_dia and ('$_hor'>=hr_entrada - INTERVAL 10 MINUTE) and '$_hor'<=hr_salida";
            try {
                $ob_horario = $bdcon->prepare($q_horario_moment);
                $ob_horario->execute();
                while ($row = $ob_horario->fetch(PDO::FETCH_ASSOC)) {
                    $entrada=$row['hr_entrada'];
                    $salida=$row['hr_salida'];
                }
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener horarios ha fallado' . $e->getMessage().'<br>';
            }

            try{
                $q="SELECT * FROM aca_asistencia WHERE fecha='$_fec' AND codest=$codest AND codgrupo=$id_grupo AND (hora + INTERVAL 10 MINUTE>='$entrada') AND hora<='$salida'";
                $resul = $bdcon->prepare($q);
                $resul->execute();
                if($resul->rowCount()>0){
                    $row = $resul->fetch(PDO::FETCH_ASSOC);
                    $this->setHoraRegistrada($row['hora']);
                    $tiene=true;
                }else{ 
                    $tiene=false;
                }

            }catch(PDOException $e){
                $tiene=false;
                $this->error .= 'La conexión para obtener asistencias ha fallado' . $e->getMessage().'<br>';
            }
            return $tiene;
        }

        // OK
        function registrar_emomentos_est($grupo_raiz, $codest, $id_grupo){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $_fec = $fecha->format('Y-m-d');
            $_hor = $fecha->format('H:i:s');
            $_dia = $fecha->format('N');
            $enabled_moment=0;

            $q_horario_moment="SELECT hr_entrada FROM grupos_horario WHERE cod_gru=$grupo_raiz and dia=$_dia and ('$_hor'>=hr_entrada - INTERVAL 10 MINUTE) and ('$_hor'<=hr_entrada + INTERVAL 10 MINUTE)";
            try {
                // $this->error.=$q_horario_moment;
                $ob_horario = $bdcon->prepare($q_horario_moment);
                $ob_horario->execute();
                while ($row = $ob_horario->fetch(PDO::FETCH_ASSOC)) {
                    $entrada=$row['hr_entrada'];
                }
            } catch (PDOException $e) {
                $this->error .= 'La conexión para obtener horarios ha fallado. Enableds' . $e->getMessage().'<br>';
            }
            if($ob_horario->rowCount()>0){
                $enabled_moment=1;
            }else{
                $enabled_moment=0;
            }
            if($grupo_raiz==$id_grupo){
                $grupo_raiz=0;
            }

            try{
                $q="INSERT INTO `aa_clases_virtuales_momentos_flag`(`cod`,`id_grupo`,`codest`,`id_gruporaiz`,`fecha`,`hora`,`flag`,`registro`,`estado`) VALUES(0,$id_grupo,$codest,$grupo_raiz,'$_fec','$_hor',$enabled_moment,now(),1)";
                $resul = $bdcon->prepare($q);
                $resul->execute();

            }catch(PDOException $e){
                $this->error .= 'La conexión para obtener asistencias ha fallado' . $e->getMessage().'<br>';
            }
        }

        function registrar_emomentos_est_inv($grupo_raiz, $codest, $id_grupo, $subgrupo){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $_fec = $fecha->format('Y-m-d');
            $_hor = $fecha->format('H:i:s');
            $enabled_moment=1;
            
            if($grupo_raiz==$id_grupo){
                $grupo_raiz=0;
            }

            try{
                $q="INSERT INTO `aa_clases_virtuales_momentos_flag`(`cod`,`id_grupo`,`codest`,`id_gruporaiz`,`fecha`,`hora`,`flag`,`id_subgru`,`registro`,`estado`) VALUES(0,$id_grupo,$codest,$grupo_raiz,'$_fec','$_hor',$enabled_moment,$subgrupo,now(),1)";
                $resul = $bdcon->prepare($q);
                $resul->execute();

            }catch(PDOException $e){
                $this->error .= 'La conexión para obtener asistencias ha fallado' . $e->getMessage().'<br>';
            }
        }

        function tiene_asistencia_est_prac($grupo_main, $codest, $id_grupo, $subgrupo){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $_fec = $fecha->format('Y-m-d');
            $_hor = $fecha->format('H:i:s');
            $_dia = $fecha->format('N');

            $tiene=false;

            try{
                $q="SELECT * FROM aca_asistencia_practica WHERE fecha='$_fec' AND codest=$codest AND codgrupo=$id_grupo AND cod_subgru=$subgrupo";
                $resul = $bdcon->prepare($q);
                $resul->execute();
                if($resul->rowCount()>0){
                    $tiene=true;
                }else{ 
                    $tiene=false;
                }

            }catch(PDOException $e){
                $tiene=false;
                $this->error .= 'La conexión para obtener asistencias ha fallado' . $e->getMessage().'<br>';
            }
            return $tiene;
        }

        // Verificar si tiene asistencia en la tabla aca_asistencia
        function tiene_asistencia_est_invertida($grupo_main, $codest, $id_grupo, $subgrupo){
            include "conexion.php";

            $fecha = new DateTime();
            $fecha->setTimezone(new DateTimeZone('America/La_Paz'));
            $_fec = $fecha->format('Y-m-d');
            $_hor = $fecha->format('H:i:s');
            $_dia = $fecha->format('N');

            $tiene=false;

            try{
                $q="SELECT * FROM aca_asistencia WHERE fecha='$_fec' AND codest=$codest AND codgrupo=$id_grupo AND cod_subgru=$subgrupo";
                $resul = $bdcon->prepare($q);
                $resul->execute();
                if($resul->rowCount()>0){
                    $tiene=true;
                }else{ 
                    $tiene=false;
                }

            }catch(PDOException $e){
                $tiene=false;
                $this->error .= 'La conexión para obtener asistencias ha fallado' . $e->getMessage().'<br>';
            }
            return $tiene;
        }
    }
?>