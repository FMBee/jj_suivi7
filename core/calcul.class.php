<?php

class calcul {
	
	public function pertePoids($pd1,$pd2) {
		if (!isset($pd1,$pd2)) $result=0;
		else {
			$result=$pd1-$pd2;
			return round($result,1);
		}
	}

	public function pertePoidsPrc($pd1,$pd2) {
		if (!isset($pd1,$pd2)) $result=0;
		else {
			$result=($pd1-$pd2)/$pd1*100;
			return round($result,1);
		}
	}
	public function perteGrss($pd1,$grss1,$pd2,$grss2) {
		if (!isset($pd1,$grss1,$pd2,$grss2)) $result=0;
		else {
			$result=(($pd1*$grss1)-($pd2*$grss2))/100;
			return round($result,1);
		}
	}

	public function h2o($pd1,$h2o1,$pd2,$h2o2) {
		if (!isset($pd1,$h2o1,$pd2,$h2o2)) $result=0;
		else {
			$result=($pd1*$h2o1 - $pd2*$h2o2)/100;
			return round($result,1);
		}
	}

	public function mscl_prc($mscl_kg,$poids) {
		if (!isset($mscl_kg,$poids)) $result=0;
		else {
			$result=$mscl_kg/$poids*100;
			return round($result,1);
		}
	}

	public function gain_prc($mscl_kg1,$poids1,$mscl_kg2,$poids2) {
		if (!isset($mscl_kg1,$poids1,$mscl_kg2,$poids2)) $result=0;
		else {
			$result=(($mscl_kg2/$poids2)-($mscl_kg1/$poids1))*100;
			return round($result,1);
		}
	}

	public function imc($poids,$taille) {
		if (!isset($poids,$taille)) $result=0;
		else {
			$result=$poids/($taille*$taille);
			return round($result,1);
		}
	}

	public function meta_base($mscl_kg) {
		if (!isset($mscl_kg)) $result=0;
		else {
			$result=370+21.6*$mscl_kg;
			return round($result,1);
		}
	}
}

?>
