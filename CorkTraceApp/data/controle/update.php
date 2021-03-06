<?php

	require_once("../orm/Conformite.php");
	require_once("../orm/Mesure.php");
	require_once("../conformite/testConformite.php");

	$data = json_decode($_POST['data']);

	//Vérif des données entrante
	$cfm_id	= isset ($data->{'cfm_id'}) ? $data->{'cfm_id'} : "undefined";
	$cfm_tca_fourni	= isset ($data->{'cfm_tca_fourni'}) ? $data->{'cfm_tca_fourni'} : "undefined";
	$cfm_tca_inter	= isset ($data->{'cfm_tca_inter'}) ? $data->{'cfm_tca_inter'} : "undefined";
	$cfm_gout 		= isset ($data->{'cfm_gout'}) ? $data->{'cfm_gout'} : "undefined";
	$cfm_capilarite = isset ($data->{'cfm_capilarite'}) ? $data->{'cfm_capilarite'} : "undefined";
	$cfm_decision 	= isset ($data->{'cfm_decision'}) ? $data->{'cfm_decision'} : "undefined";
	$hauteur 		= isset ($data->{'hauteur'}) ? $data->{'hauteur'} : "undefined";
	$details 		= isset ($data->{'details'}) ? $data->{'details'} : "undefined";

	if( $cfm_id == "undefined" ||
		$cfm_tca_fourni == "undefined" ||
		$cfm_tca_inter == "undefined" ||
		$cfm_gout == "undefined" ||	
		$cfm_capilarite == "undefined" ||	
		$cfm_decision == "undefined" ||
		$hauteur == "undefined"
	)
	{
		die("Valeurs manquantes");
	}

	$lgMax = $hauteur+0.5;
	$lgMin = $hauteur-0.5;
	$nbToleranceLg = 2;
	$dmMax = 24.5;
	$dmMin = 23.5;
	$nbToleranceDm = 2;
	$ovMax = 0.7;
	$nbToleranceOv = 2;
	$goutAcceptation = "oui";
	$toleranceTcaInt = 2;
	$toleranceTcaFou = 2;
	$hmMax = 8;
	$hmMin = 4;
	$tailleEchantillonHm = 0;
	$tailleEchantillonCpr = 5;
	$dmCprMin = 90;

	if( $data->{'module'} == "arrivage"){
		$tailleEchantillonHm = 10;
	}
	if( $data->{'module'} == "livraison"){
		$tailleEchantillonHm = 5;
	}

	$echantillonLg = array();
	$echantillonDm = array();
	$echantillonOv = array();
	$echantillonHm = array();
	$echantillonDiamCompr = array();

	foreach ($details as $detail) {

		array_push($echantillonLg, $detail->{'mes_longueur'});
		array_push($echantillonDm, $detail->{'mes_diam'});
		array_push($echantillonOv, abs($detail->{'mes_diam2'} - $detail->{'mes_diam'}) );
		array_push($echantillonHm, $detail->{'mes_humidite'} );
		array_push($echantillonDiamCompr, $detail->{'mes_compression'} );
	}

	$is_conforme = isEchantillonConforme(
	   $echantillonLg,$lgMax,$lgMin,$nbToleranceLg,
	   $echantillonDm,$dmMax,$dmMin,$nbToleranceDm,
	   $echantillonOv,$ovMax,$nbToleranceOv,
	   $cfm_gout,$goutAcceptation,
	   $cfm_tca_fourni, $toleranceTcaFou,
	   $cfm_tca_inter, $toleranceTcaInt,
	   $cfm_capilarite,
	   $echantillonHm,$hmMax,$hmMin, 0, $tailleEchantillonHm,
	   $echantillonDiamCompr,$dmCprMin, 0, $tailleEchantillonCpr
	);

	if( $is_conforme == 1 ){
		$cfm_decision = "Conforme";
	}else{
		$cfm_decision = "Non conforme";
	}

	$Conformite = new Conformite();
	$cond = array('CFM_ID' => $cfm_id);
	$newValue = array(
		'CFM_TCA_FOURNI' 	=>  '"'.$cfm_tca_fourni.'"',
		'CFM_TCA_INTER'		=>	'"'.$cfm_tca_inter.'"',
		'CFM_GOUT'			=>	'"'.$cfm_gout.'"',
		'CFM_CAPILARITE'	=>	'"'.$cfm_capilarite.'"',
		'CFM_HUMIDITE'		=>	"0",
		'CFM_DIAMCOMPR'		=>	"0",
		'CFM_DECISION'		=>	'"'.$cfm_decision.'"'
	);
	$Conformite->updateRow($newValue, $cond);

	$Mesure = new Mesure();
	foreach ($details as $detail) {

		$cond = array('MES_ID' => $detail->{'mes_id'});


		$newValue = array(
			'MES_LONGUEUR'		=>	'"'.$detail->{'mes_longueur'}.'"',
			'MES_DIAM'			=>	'"'.$detail->{'mes_diam'}.'"',
			'MES_DIAM2'			=>	'"'.$detail->{'mes_diam2'}.'"',
			'MES_OVAL'	=>	'"'.abs($detail->{'mes_diam2'} - $detail->{'mes_diam'}).'"',
			'MES_HUMIDITE'	=>	'"'.$detail->{'mes_humidite'}.'"',
			'MES_COMPRESSION'	=>	'"'.$detail->{'mes_compression'}.'"'
		);

		$Mesure->updateRow($newValue, $cond);
	}

	echo '{ "success": true, "cfm_decision" : "'.$cfm_decision.'" }';
?>