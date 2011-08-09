<?php defined('BASEPATH') OR exit('No direct script access allowed');

// labels
$lang['header']			=	'Шаг 2: Проверка требований';
$lang['intro_text']		= 	'В первую очередь установщик проверяет - есть-ли возможность запустить PyroCMS на вашем сервере. Подавляющее большинство серверов должно быть способно на это.';
$lang['mandatory']		= 	'Необходимые требования';
$lang['recommended']	= 	'Рекомендуется';

$lang['server_settings']= 	'Настройки HTTP-сервера';
$lang['server_version']	=	'ПО вашего сервера:';
$lang['server_fail']	=	'ПО вашего сервера не поддерживается, PyroCMS может работать, а может и не работать на нём. Пока с PHP и MySQL всё в порядке - PyroCMS должен работать без проблем, только без ЧПУ.';

$lang['php_settings']	=	'Настройки PHP';
$lang['php_required']	=	'PyroCMS для работы требуется PHP версии %s или выше.';
$lang['php_version']	=	'На вашем сервере установлена версия';
$lang['php_fail']		=	'Версия PHP на вашем сервере не поддерживается. Для корректной работы PyroCMS требуется PHP версии %s или выше.';

$lang['mysql_settings']	=	'Настройки MySQL';
$lang['mysql_required']	=	'PyroCMS необходим доступ к базе данных MySQL версии 5.0 или выше.';
$lang['mysql_version1']	=	'На вашем сервере установлена версия';
$lang['mysql_version2']	=	'На вашем клиенте установлена версия';
$lang['mysql_fail']		=	'Версия MySQL на вашем сервере не поддерживается. Для корректной работы PyroCMS требуется MySQL версии 5.0 или выше.';

$lang['gd_settings']	=	'Настройки GD';
$lang['gd_required']	= 	'PyroCMS необходима библиотека GD версии 1.0 или выше, для обработки изображений.';
$lang['gd_version']		= 	'На вашем сервере установлена версия';
$lang['gd_fail']		=	'Мы не смогли определить версию библиотеки GD на вашем сервере. Обычно это значит, что библиотека не установлена. PyroCMS всё равно будет работать правильно, но некоторые функции по обработке фотографий будут недоступны. Настоятельно рекомендуется включить эту библиотеку.';

$lang['summary']		=	'Заключение';

$lang['zlib']			=	'Zlib';
$lang['zlib_required']	= 	'PyroCMS необходима библиотека Zlib для распаковки и установки тем.';
$lang['zlib_fail']		=	'Zlib не найдена. Обычно это значит, что библиотека не установлена. PyroCMS всё равно будет работать правильно, но установка тем будет недоступна. Настоятельно рекомендуется установить эту библиотеку.';

$lang['curl']			=	'Curl';
$lang['curl_required']	=	'PyroCMS необходима библиотека Curl для подключения к другим сайтам.';
$lang['curl_fail']		=	'Curl не найден. Обычно это значит, что библиотека не установлена. PyroCMS всё равно будет работать правильно, но некоторые функции будут недоступны. Настоятельно рекомендуется включить эту библиотеку.';

$lang['summary_success']	=	'Ваш сервер удовлетворяет всем требованиям, PyroCMS будет работать без проблем, вы можете переходить к следующему шагу.';
$lang['summary_partial']	=	'Ваш сервер удовлетворяет <em>большинству</em> требований PyroCMS. Это значит, что PyroCMS должна работать правильно, но есть возможность, что у вас могут возникнуть проблемы с изменением размера изображений и созданием миниатюр.';
$lang['summary_failure']	=	'Похоже, что ваш сервер неспособен на работу с PyroCMS. Пожалуйста, свяжитесь с вашим системным администратором или хостинговой компанией для решения этих проблем.';
$lang['next_step']		=	'Перейти к следующему шагу';
$lang['step3']			=	'Шаг 3';
$lang['retry']			=	'Попробовать ещё раз';

// messages
$lang['step1_failure']	=	'Пожалуйста, укажите данные, необходимые для подключения к базе данных в форме ниже.';

/* End of file step_2_lang.php */
/* Location: ./installer/language/english/step_2_lang.php */