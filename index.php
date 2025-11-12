<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
    [
        'fullname' => "Иван Колосов Иванович",
        'job' => "boss"
    ],
];

// $example_persons_array = [
//     [
//         'fullname' => 'Иванов Иван Иванович',
//         'job' => 'tester',
//     ]
// ];

// Function to get full name from parts
function getFullnameFromParts($surname, $name, $patronymic) {
	return $surname . ' ' . $name . ' ' . $patronymic;
}

// Function to get parts from raw full name
function getPartsFromFullname($fullname) {
	$parts = explode(' ', $fullname);
	$newParts = [
		'surname' => $parts[0],
		'name' => $parts[1],
		'patronymic' => $parts[2],
	];

	return $newParts;
}

// Short full name function
function getShortName($fullname) {
	$parts = getPartsFromFullname($fullname);
	// here is two substitutions of surname first letter because the Russian letters require two Unicode characters to work
	return $parts['name'] . ' ' . $parts['surname'][0].$parts['surname'][1] . '.';
}

// Get gender from full name function
function getGenderFromName($fullname) {
	$probability = 0;
	$parts = getPartsFromFullname($fullname);

	// Signs of female gender
	if (str_ends_with($parts["patronymic"], 'вна')) {
		$probability -= 1;
	}

	if (str_ends_with($parts["name"], 'а')) {
		$probability -= 1;
	}

	if (str_ends_with($parts["surname"], 'ва')) {
		$probability -= 1;
	}

	// Signs of male gender
	if (str_ends_with($parts["patronymic"], 'ич')) {
		$probability += 1;
	}

	if (str_ends_with($parts["name"], 'й') ||
		str_ends_with($parts["name"], 'н')) {
		$probability += 1;
	}

	if (str_ends_with($parts["surname"], 'в')) {
		$probability += 1;
	}

	if ($probability > 0) {
		return 1;
	}

	if ($probability < 0) {
		return -1;
	}

	if ($probability === 0) {
		return 0;
	}
}

// A function to get compoung of males & females in percentage
function getGenderFromNameDescription($persons) {
	$male = 0;
	$female = 0;
	$unknown = 0;

	foreach ($persons as $person) {
		if (getGenderFromName($person["fullname"]) === 1) {
			$male++;
		} else if (getGenderFromName($person["fullname"]) === -1) {
			$female++;
		} else {
			$unknown++;
		}
	}

	// result = (percentage * 100) / startNumber
	$malePercent = ($male * 100) / count($persons);
	$femalePercent = ($female * 100) / count($persons);
	$unknownPercent = ($unknown * 100) / count($persons);

	// Convert to a string format using following formula:
	// $somePercent = number_format($somePercent, 1, '.', "");
	$malePercent = number_format($malePercent, 1, '.', "");
	$femalePercent = number_format($femalePercent, 1, '.', "");
	$unknownPercent = number_format($unknownPercent, 1, '.', "");

	echo $malePercent;
	echo "\n";
	echo $femalePercent;
	echo "\n";
	echo $unknownPercent;
	echo "\n";
	echo "\n";

	$output = "";
	$output .= "Гендерный состав аудитории: \n";
	$output .= "----------------------------\n";
	$output .= "Мужчины: " . $malePercent . "%\n";
	$output .= "Женщины: " . $femalePercent . "%\n";
	$output .= "Не удалось определить: " . $unknownPercent . "%\n";

	return $output;
}

function getPerfectPartner($surname, $name, $patronymic, $persons) {
	$surname = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
	$name = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
	$patronymic = mb_convert_case($patronymic, MB_CASE_TITLE_SIMPLE);
	$fullname = getFullnameFromParts($surname, $name, $patronymic);

	$initialGender = getGenderFromName($fullname);

	if ($initialGender === 0) {
		return "Невозможно подобрать пару, так как пол $name неизвестен.";
	}

	$partner = $persons[array_rand($persons)];
	$partnerGender = getGenderFromName($partner["fullname"]);

	while (($partnerGender === $initialGender) || ($partnerGender === 0)) {
		$partner = $persons[array_rand($persons)];
		$partnerGender = getGenderFromName($partner["fullname"]);
	}


	// Select a random percent of ideal partner
	$perfectPercent = strval(rand(50, 100)).'.'
					  .strval(rand(0, 9))
					  .strval(rand(0, 9)).'%';

	$initShortName = getShortName($fullname);
	$partnerShortName = getShortName($partner["fullname"]);

	return <<<PERSON
	$initShortName + $partnerShortName =
	♡ Идеально на $perfectPercent ♡
	PERSON;
}









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
