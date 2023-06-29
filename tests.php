<?php
//////////////////////////////////////////////////////////////
// Это код, который использовался при тестировании функций. //
//////////////////////////////////////////////////////////////

// testing functions with example_persons_array
getPerfectPartner('ИвАнОв', 'ИвАн', 'ИвАнОвИч', $example_persons_array);

foreach ($example_persons_array as $person) {
	$parts = getPartsFromFullname($person["fullname"]);
	echo "ФИО: " . $person["fullname"] . "\n";
	// print_r($parts);

	$fullname = getFullnameFromParts($parts["surname"], $parts["name"], $parts["patronymic"]);
	print_r($fullname);

	// print_r(getPartsFromFullname(getFullnameFromParts($parts["surname"], $parts["name"], $parts["patronymic"])));

	// Short name function test
	echo "\n";
	echo getShortName($fullname);

	// Gender function test
	echo "\n";
	$gender_output = "Не удалось определить";
	$gender_probability = getGenderFromName($fullname);
	if ($gender_probability === 1) {
		$gender_output = "Мужчина";
	}
	if ($gender_probability === -1) {
		$gender_output = "Женщина";
	}
	echo $gender_output;
	echo "\n\n";
	print_r(getPerfectPartner($parts["surname"], $parts["name"], $parts["patronymic"], $example_persons_array));

	echo <<<SPACER


	
	---------------------------------------------------------------



	SPACER;
}


print_r(getGenderFromNameDescription($example_persons_array));

var_dump((int)(number_format(4.3423, 3, '.', "")));

// result = (percentage * 100) / startNumber