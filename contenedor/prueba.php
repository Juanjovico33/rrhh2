<?php	
include "../includes/conexion.php";
	echo $codest = $_GET['_codest'];
	echo "<br>";
	echo $codmat = $_GET['_codmat'];
	echo "<br>";
	echo $periodo = $_GET['_per'];
	echo "<br>";
	$obtener_idgrup= $bdcon->prepare("SELECT idgrupo from aca_registroestmat where periodo='$periodo' and codest='$codest' and codmateria='$codmat'");
    $obtener_idgrup->execute();

    while ($row = $obtener_idgrup->fetch(PDO::FETCH_ASSOC)) {
                echo $idgrup=$row['idgrupo'];               
            }	   

     $query_nota= $bdcon->prepare("SELECT nota,t_examen from aca_notas where id_grupo='$idgrup' and codest='$codest' ORDER BY t_examen");
    $query_nota->execute();

    while ($row2 = $query_nota->fetch(PDO::FETCH_ASSOC)) {
                $idgrup=$row2['idgrupo']; 
                $nota=$row2['nota'];
				$parcial=$row2['t_examen'];
				if ($parcial=='1') {
					echo $pp=$pp;
				}else{
					if ($parcial=='2') {
						echo $sp=$sp;
					}else{
						echo $ef=$ef;
					}
				}              
           }	

?>
	<table class="table table-hover">
  <thead>
    <tr>      
      <th scope="col">1° Parcial</th>
      <th scope="col">2° Parcial</th>
      <th scope="col">Examen Final</th>
      <th scope="col">Nota Practica</th>
      <th scope="col">Nota Final</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"></th>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>    
  </tbody>
</table>
