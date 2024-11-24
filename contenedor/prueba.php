<?


if ($parc=='9') {
	if ($fecha_inv_aux=='') {
		//echo "fecha aunx inv vacio ".$fech;
		$fecha_inv_aux=$fech;
		$cont_fec_inv++;
		$ar_fec[]=$fech;
	}else{
		if ($fecha_inv_aux!=$fech) {
			//echo "<br>";
			//echo $fecha_inv_aux." ".$fech;
			foreach ($ar_fec as $key => $value) {
				if ($value==$fech) {
					break;
				}else{
					$ar_fec[]=$fech;
					$cont_fec_inv++;
					$fecha_inv_aux=$fech;		
				}
			}
			//echo "<br>";
		}
	}
	if ($cad_inve=='') {
		$cad_inve=$codest."|".$nota;
	}else{
		$cad_inve=$cad_inve.",".$codest."|".$nota;
	}
}

	$sum_aux=0;
	$prom_inv=0;
	$valorinv=explode(",", $cad_inve);
	foreach ($valorinv as $keyinv => $valueinv) {
		$datiinv=explode("|", $valueinv);
		if ($datiinv[0]==$key) {
			$sum_inv=$datiinv[1];
			$sum_aux=$sum_aux+$sum_inv;
		}
	}
	//echo "=".($sum_aux/$cont_fec_inv);
	@$prom_inv=($sum_aux/$cont_fec_inv);
	echo number_format($prom_inv,2);