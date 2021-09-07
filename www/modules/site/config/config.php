<?php

$phone = '8 (804) 333 000 2';
$aCities = array(
	"www.mcolmed.ru" => "Екатеринбург",
	"serov.mcolmed.ru" => "Серов",
	"ntagil.mcolmed.ru" => "Нижний Тагил",
	"kturinsk.mcolmed.ru" => "Краснотурьинск",
	"sevural.mcolmed.ru" => "Североуральск",
	"nizhnyaya-tura.mcolmed.ru" => "Нижняя Тура",
	"asbest.mcolmed.ru" => "Асбест",
);
$formId = 1; // Обратный звонок
$formId2 = 2; // Запись на консультацию
$formId3 = 14; // Записаться на прием
$informationsystemId4 = 66; // Лицензии
$informationsystemId5 = 67; // Отзывы
$informationsystemId6 = 68; // Преимущества

return array(
	// Екатеринбург
	1 => array(
		'phone' => '+7 (343) 287-88-88',
		'phone2' => $phone,
		'address' => 'ул. Фрунзе, д. 20',
		'address2' => 'ул. Чкалова, д. 124',
		'mode' => '<b>Пн-Пт</b> 08:00 - 20:00<br /><b>Сб</b> 09:00 - 17:00',
		'cities' => $aCities,
		'formId' => $formId, // Обратный звонок
		'formId2' => $formId2, // Запись на консультацию
		'formId3' => $formId3, // Записаться на прием
		'menuId' => 1, // Верхнее меню
		'menuId2' => 4, // Social button
		'propertyId' => 101, // Услуги -> Группы -> Доп. свойства -> Главное фото раздела
		'informationsystemId' => 5, // Пресса
		'informationsystemId2' => 6, // Услуги
		'informationsystemId3' => 19, // Акции
		'informationsystemId4' => $informationsystemId4, // Лицензии
		'informationsystemId5' => $informationsystemId5, // Отзывы
		'informationsystemId6' => $informationsystemId6 // Преимущества
	),
	// Нижний Тагил
	4 => array(
		'phone' => '+7 (3435) 47-54-18',
		'phone2' => $phone,
		'address' => 'ул. Октябрьской революции, д. 7а',
		'address2' => '',
		'mode' => '<b>Пн-Пт</b> 08:00 - 20:00<br /><b>Сб</b> 08:00 - 20:00<br /><b>Вс</b> 08:00 - 20:00',
		'cities' => $aCities,
		'formId' => 45, // Обратный звонок
		'formId2' => 47, // Запись на консультацию
		'formId3' => 46, // Записаться на прием
		'menuId' => 8, // Верхнее меню
		'menuId2' => 11, // Social button
		'propertyId' => 114, // Услуги -> Группы -> Доп. свойства -> Главное фото раздела
		'informationsystemId' => 26, // Пресса
		'informationsystemId2' => 31, // Услуги
		'informationsystemId3' => 29, // Акции
		'informationsystemId4' => 132, // Лицензии
		'informationsystemId5' => 133, // Отзывы
		'informationsystemId6' => $informationsystemId6 // Преимущества
	),
	// Серов
	5 => array(
		'phone' => '+7 (34385) 42-933',
		'phone2' => $phone,
		'address' => 'ул. Октябрьской революции, д. 7',
		'address2' => '',
		'mode' => '<b>Пн-Пт</b> 08:00 - 20:00<br /><b>Сб</b> 08:00 - 17:00<br /><b>Вс</b> 08:00 - 17:00',
		'cities' => $aCities,
		'formId' => 39, // Обратный звонок
		'formId2' => 41, // Запись на консультацию
		'formId3' => 40, // Записаться на прием
		'menuId' => 12, // Верхнее меню
		'menuId2' => 15, // Social button
		'propertyId' => 130, // Услуги -> Группы -> Доп. свойства -> Главное фото раздела
		'informationsystemId' => 44, // Пресса
		'informationsystemId2' => 40, // Услуги
		'informationsystemId3' => 38, // Акции
		'informationsystemId4' => 69, // Лицензии
		'informationsystemId5' => 131, // Отзывы
		'informationsystemId6' => $informationsystemId6 // Преимущества
	),
	// Краснотурьинск
	6 => array(
		'phone' => '+7 (34384) 98-998',
		'phone2' => $phone,
		'address' => 'ул. Ленина, д. 36',
		'address2' => '',
		'mode' => '<b>Пн-Пт</b> 08:00 - 20:00<br /><b>Сб</b> 08:00 - 17:00<br /><b>Вс</b> 09:00 - 17:00',
		'cities' => $aCities,
		'formId' => 48, // Обратный звонок
		'formId2' => 50, // Запись на консультацию
		'formId3' => 49, // Записаться на прием
		'menuId' => 16, // Верхнее меню
		'menuId2' => 19, // Social button
		'propertyId' => 148, // Услуги -> Группы -> Доп. свойства -> Главное фото раздела
		'informationsystemId' => 54, // Пресса
		'informationsystemId2' => 51, // Услуги
		'informationsystemId3' => 49, // Акции
		'informationsystemId4' => 129, // Лицензии
		'informationsystemId5' => 130, // Отзывы
		'informationsystemId6' => $informationsystemId6 // Преимущества
	),
	// Североуральск
	7 => array(
		'phone' => '+7 (34380) 33-448',
		'phone2' => $phone,
		'address' => 'ул. Ленина, д. 19/1',
		'address2' => '',
		'mode' => '<b>Пн-Пт</b> 08:00 - 20:00<br /><b>Сб</b> 08:00 - 17:00<br /><b>Вс</b> 09:00 - 17:00',
		'cities' => $aCities,
		'formId' => 42, // Обратный звонок
		'formId2' => 44, // Запись на консультацию
		'formId3' => 43, // Записаться на прием
		'menuId' => 20, // Верхнее меню
		'menuId2' => 23, // Social button
		'propertyId' => 164, // Услуги -> Группы -> Доп. свойства -> Главное фото раздела
		'informationsystemId' => 65, // Пресса
		'informationsystemId2' => 62, // Услуги
		'informationsystemId3' => 60, // Акции
		'informationsystemId4' => 127, // Лицензии
		'informationsystemId5' => 128, // Отзывы
		'informationsystemId6' => $informationsystemId6 // Преимущества
	),
	// Нижняя Тура
	8 => array(
		'phone' => ' +7 (34342) 963-43',
		'phone2' => $phone,
		'address' => 'ул. 40 лет Октября, дом 10',
		'address2' => '',
		'mode' => '<b>Пн-Пт</b> 08:00 - 20:00<br /><b>Сб</b> 08:00 - 17:00<br /><b>Вс</b> 09:00 - 17:00',
		'cities' => $aCities,
		'formId' => 15, // Обратный звонок
		'formId2' => 16, // Запись на консультацию
		'formId3' => 20, // Записаться на прием
		'menuId' => 24, // Верхнее меню
		'menuId2' => 26, // Social button
		'propertyId' => 101, // Услуги -> Группы -> Доп. свойства -> Главное фото раздела
		'informationsystemId' => 71, // Пресса
		'informationsystemId2' => 75, // Услуги
		'informationsystemId3' => 73, // Акции
		'informationsystemId4' => 80, // Лицензии
		'informationsystemId5' => 81, // Отзывы
		'informationsystemId6' => 82 // Преимущества
	),
       // Асбест
	13 => array(
		'phone' => ' +7 (34365) 3-55-45',
		'phone2' => $phone,
		'address' => 'ул. Ленинградская, д. 5',
		'address2' => '',
		'mode' => '<b>Пн-Пт</b> 08:00 - 20:00<br /><b>Сб</b> 09:00 - 17:00<br /><b>Вс</b>: Выходной',
		'cities' => $aCities,
		'formId' => 78, // Обратный звонок
		'formId2' => 73, // Запись на консультацию
	        'formId3' => 74, // Записаться на прием
		'menuId' => 36, // Верхнее меню
		'menuId2' => 38, // Social button
		'propertyId' => 434, // Услуги -> Группы -> Доп. свойства -> Главное фото раздела
		'informationsystemId' => 143, // Пресса
		'informationsystemId2' => 135, // Услуги
		'informationsystemId3' => 136, // Акции
		'informationsystemId4' => 137, // Лицензии
		'informationsystemId5' => 139, // Отзывы
		'informationsystemId6' => 82 // Преимущества
	),
);
