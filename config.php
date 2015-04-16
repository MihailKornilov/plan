<?php

define('DOCUMENT_ROOT', dirname(__FILE__));
require_once(DOCUMENT_ROOT.'/syncro.php');

require_once(API_PATH.'/vk.php');

_appAuth();

require_once(DOCUMENT_ROOT.'/view/main.php');


_getVkUser();

function _getVkUser() {//Получение данных о пользователе
	define('VERSION', 1);
	return;
	$u = _viewer();
	define('VIEWER_NAME', $u['name']);
	define('VIEWER_COUNTRY_ID', $u['country_id']);
	define('VIEWER_CITY_ID', $u['city_id']);
	define('VIEWER_ADMIN', $u['admin']);
}//_getVkUser()
