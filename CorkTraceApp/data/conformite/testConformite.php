<?php

	function isLongueurConforme($echantillon,$lgMax,$lgMin,$nbTolerance){
		$nbRebus=0;
		foreach ($echantillon as $value) {
			if($value>$lgMax || $value<$lgMin){
				$nbRebus++;
			}
		}

		if($nbRebus<=$nbTolerance){
			return 1;
		}else{
			return 0;
		}
	}
	
	function isDiametreConforme($echantillon,$dmMax,$dmMin,$nbTolerance){
		$nbRebus=0;
		foreach ($echantillon as $value) {
			if($value>$dmMax || $value<$dmMin){
				$nbRebus++;
			}
		}
		if($nbRebus<=$nbTolerance){
			return 1;
		}else{
			return 0;
		}
	}
	
	function isOvalisationConforme($echantillon,$ovMax,$nbTolerance){
		$nbRebus=0;
		foreach ($echantillon as $value) {
			if($value>$ovMax){
				$nbRebus++;
			}
		}

		if($nbRebus<=$nbTolerance){
			return 1;
		}else{
			return 0;
		}
	}
	
	function isHumiditeConforme($echantillon,$hmMax,$hmMin,$nbToleranceMin, $tailleEchantillon){
		$nbRebus=0;
		$i=0;
		while($i<$tailleEchantillon) {
			$value = $echantillon[$i];
			if($value>$hmMax || $value<$hmMin){
				$nbRebus++;
			}
			$i++;
		}
		if($nbRebus <= $nbToleranceMin){
			return 1;
		}else{
			return 0;
		}
	}
	
	function isDiamComprConforme($echantillon,$dmCprMin,$nbToleranceMin, $tailleEchantillon){
		$nbRebus=0;
		$i=0;
		while($i<$tailleEchantillon) {
			$value = $echantillon[$i];
			if($value<$dmCprMin){
				$nbRebus++;
			}
			$i++;
		}
		if($nbRebus <= $nbToleranceMin){
			return 1;
		}else{
			return 0;
		}
	}
	
	function sourcesNonConformite($echantillonLg,$lgMax,$lgMin,$nbToleranceLg,
								   $echantillonDm,$dmMax,$dmMin,$nbToleranceDm,
								   $echantillonOv,$ovMax,$nbToleranceOv,
								   $gout,$goutAcceptation,
								   $tcaFou, $toleranceTcaFou,
								   $tcaInt, $toleranceTcaInt,
								   $capilarite,
								   $echantillonHm,$hmMax,$hmMin,$nbToleranceHmMin, $tailleEchantillonHm,
								   $echantillonDiamCompr, $dmCprMin, $toleranceDiamComprMin, $tailleEchantillonCpr){

		$tabConfo['Longueur']=isLongueurConforme($echantillonLg,$lgMax,$lgMin,$nbToleranceLg);
		$tabConfo['Diametre']=isDiametreConforme($echantillonDm,$dmMax,$dmMin,$nbToleranceDm);
		$tabConfo['Ovalisation']=isOvalisationConforme($echantillonOv,$ovMax,$nbToleranceOv);
		$tabConfo['Gout']=($gout==$goutAcceptation)?1:0;
		$tabConfo['TCAFournisseur']=($tcaFou<$toleranceTcaFou)?1:0;
		$tabConfo['TCAInterne']=($tcaInt<$toleranceTcaInt)?1:0;
		$tabConfo['Capilarite']=($capilarite==1)?0:1;
		$tabConfo['Humidite']=isHumiditeConforme($echantillonHm,$hmMax,$hmMin,$nbToleranceHmMin,  $tailleEchantillonHm);
		$tabConfo['DiametreCompression']=isDiamComprConforme($echantillonDiamCompr,$dmCprMin,$toleranceDiamComprMin, $tailleEchantillonCpr);
						   
		return $tabConfo;
	}

	function isEchantillonConforme($echantillonLg,$lgMax,$lgMin,$nbToleranceLg,
								   $echantillonDm,$dmMax,$dmMin,$nbToleranceDm,
								   $echantillonOv,$ovMax,$nbToleranceOv,
								   $gout,$goutAcceptation,
								   $tcaFou, $toleranceTcaFou,
								   $tcaInt, $toleranceTcaInt,
								   $capilarite,
								   $echantillonHm,$hmMax,$hmMin,$nbToleranceHmMin, $tailleEchantillonHm,
								   $echantillonDiamCompr,$dmCprMin,$toleranceDiamComprMin, $tailleEchantillonCpr){
			
		$tabSourcesNonConformite=sourcesNonConformite($echantillonLg,$lgMax,$lgMin,$nbToleranceLg,
								   $echantillonDm,$dmMax,$dmMin,$nbToleranceDm,
								   $echantillonOv,$ovMax,$nbToleranceOv,
								   $gout,$goutAcceptation,
								   $tcaFou,$toleranceTcaFou,
								   $tcaInt,$toleranceTcaInt,
								   $capilarite,
								   $echantillonHm,$hmMax,$hmMin,$nbToleranceHmMin, $tailleEchantillonHm,
								   $echantillonDiamCompr,$dmCprMin,$toleranceDiamComprMin, $tailleEchantillonCpr);
		//print_r($tabSourcesNonConformite);

		$conforme=1;					   
		foreach($tabSourcesNonConformite as $value){
			if($value == 0){
				$conforme=0;
			}
		}
		return $conforme;
	}
?>