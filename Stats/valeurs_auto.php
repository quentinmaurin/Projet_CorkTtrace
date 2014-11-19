<?php

	function taux_humidite($pieces)
	{
		$valeurs_rand = array();
		$valeurs_return = array();

		for ($i=0; $i<$pieces; $i++)	$valeurs_rand[$i] = rand(390,810);

		$valeurs_return[0] = min($valeurs_rand) / 100;
		$valeurs_return[1] = max($valeurs_rand) / 100;
		return $valeurs_return;
	}

	function taux_compression($pieces)
	{
		$valeurs_rand = array();

		for ($i=0; $i<$pieces; $i++)	$valeurs_rand[$i] = rand(85,100);

		return min($valeurs_rand);
	}

	function TCA_prestataire($pieces)
	{
		$valeurs_rand = array();

		for ($i=0; $i<$pieces; $i++)
		{
			$random = rand(0,205);

			if ($random <= 200)			$valeurs_rand[$i] = $random;
			else
			{
				$random2 = rand(0,2);

				if ($random2 == 2)		$valeurs_rand[$i] = $random;
				else					$valeurs_rand[$i] = 100;
			}
		}

		return max($valeurs_rand) / 100;
	}

	function TCA_interne()
	{
		return rand(0,205) / 100;
	}

	function capillarite()
	{
		return rand(0,120) / 100;
	}

	function gout()
	{
		if (rand(0,100) < 90)		return true;
		else						return false;
	}

	function valeurs_arrivage()
	{
		$valeurs = array();
		$valeurs_h = array();

		$valeurs_h = taux_humidite(10);

		$valeurs[0] = $valeurs_h[0];
		$valeurs[1] = $valeurs_h[1];
		$valeurs[2] = taux_compression(5);
		$valeurs[3] = TCA_prestataire(50);
		$valeurs[4] = TCA_interne();
		$valeurs[5] = gout();
		
		return $valeurs;
	}

	function valeurs_commande()
	{
		$valeurs = array();
		$valeurs_h = array();

		$valeurs_h = taux_humidite(5);

		$valeurs[0] = $valeurs_h[0];
		$valeurs[1] = $valeurs_h[1];
		$valeurs[2] = taux_compression(5);
		$valeurs[3] = TCA_interne();
		$valeurs[4] = capillarite();
		$valeurs[5] = gout();
		
		return $valeurs;
	}

	function show_values($_array)
	{
		foreach ($_array as $value)
		{
			echo $value." ; ";
		}
		echo "<br>";
	}

	show_values(valeurs_arrivage());
	show_values(valeurs_commande());

?>