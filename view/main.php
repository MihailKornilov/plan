<?php
function _hashRead() {
	$_GET['p'] = isset($_GET['p']) ? $_GET['p'] : 'main';
	if(empty($_GET['hash'])) {
		define('HASH_VALUES', false);
		if(APP_START) {// восстановление последней посещённой страницы
			$_GET['p'] = isset($_COOKIE['p']) ? $_COOKIE['p'] : $_GET['p'];
			$_GET['d'] = isset($_COOKIE['d']) ? $_COOKIE['d'] : '';
			$_GET['d1'] = isset($_COOKIE['d1']) ? $_COOKIE['d1'] : '';
			$_GET['id'] = isset($_COOKIE['id']) ? $_COOKIE['id'] : '';
		} else
			_hashCookieSet();
		return;
	}
	$ex = explode('.', $_GET['hash']);
	$r = explode('_', $ex[0]);
	unset($ex[0]);
	define('HASH_VALUES', empty($ex) ? false : implode('.', $ex));
	$_GET['p'] = $r[0];
	unset($_GET['d']);
	unset($_GET['d1']);
	unset($_GET['id']);
	switch($_GET['p']) {
		case 'client':
			if(isset($r[1]))
				if(preg_match(REGEXP_NUMERIC, $r[1])) {
					$_GET['d'] = 'info';
					$_GET['id'] = intval($r[1]);
				}
			break;
		case 'zayav':
			if(isset($r[1]))
				if(preg_match(REGEXP_NUMERIC, $r[1])) {
					$_GET['d'] = 'info';
					$_GET['id'] = intval($r[1]);
				} else {
					$_GET['d'] = $r[1];
					if(isset($r[2]))
						$_GET['id'] = intval($r[2]);
				}
			break;
		case 'zp':
			if(isset($r[1]))
				if(preg_match(REGEXP_NUMERIC, $r[1])) {
					$_GET['d'] = 'info';
					$_GET['id'] = intval($r[1]);
				}
			break;
		default:
			if(isset($r[1])) {
				$_GET['d'] = $r[1];
				if(isset($r[2]))
					$_GET['d1'] = $r[2];
			}
	}
	_hashCookieSet();
}//_hashRead()
function _hashCookieSet() {
	setcookie('p', $_GET['p'], time() + 2592000, '/');
	setcookie('d', isset($_GET['d']) ? $_GET['d'] : '', time() + 2592000, '/');
	setcookie('d1', isset($_GET['d1']) ? $_GET['d1'] : '', time() + 2592000, '/');
	setcookie('id', isset($_GET['id']) ? $_GET['id'] : '', time() + 2592000, '/');
}//_hashCookieSet()
function _cacheClear() {}//_cacheClear()

function _header() {
	global $html;
	$html =
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.
		'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">'.

		'<head>'.
		'<meta http-equiv="content-type" content="text/html; charset=windows-1251" />'.
		'<title>Plan - Приложение '.APP_ID.'</title>'.

		_api_scripts().

		'<link rel="stylesheet" type="text/css" href="'.APP_HTML.'/css/main'.(DEBUG ? '' : '.min').'.css?'.VERSION.'" />'.
		'<script type="text/javascript" src="'.APP_HTML.'/js/main'.(DEBUG ? '' : '.min').'.js?'.VERSION.'"></script>'.

		'</head>'.
		'<body>'.
		'<div id="frameBody">'.
			'<iframe id="frameHidden" name="frameHidden"></iframe>';
}//_header()

function _mainLinks() {
	global $html;
	$links = array(
		array(
			'name' => 'Главная',
			'page' => 'main',
			'show' => 1
		)
	);

	$send = '<div id="mainLinks">';
	foreach($links as $l)
		if($l['show']) {
			$sel = $l['page'] == $_GET['p'] ? ' class="sel"' : '';
			$send .=
				'<a href="'.URL.'&p='.$l['page'].'"'.$sel.'>'.
					$l['name'].
				'</a>';
		}
	$send .= '</div>';
	$html .= $send;
}//_mainLinks()


function mainPageDop() {
	return
		'<div id="dopLinks">'.
			'<a class="link sel" href="'.URL.'&p=main&d=project">Проекты</a>'.
			'<a class="link">Люди</a>'.
			'<a class="link">Места</a>'.
			'<a class="link">Деньги</a>'.
		'</div>';
}

function project() {
	$data = project_spisok();
	return
		mainPageDop().
		'<div id="project">'.
			'<div class="headName">Мои проекты<a class="add">Добавить</a></div>'.
			'<div id="spisok">'.$data['spisok'].'</div>'.
		'</div>';
}//project()
function project_spisok($v=array(), $i='all') {
	$cond = "`owner_id`=".VIEWER_ID;

	$all = query_value("SELECT COUNT(*) FROM `project` WHERE ".$cond);

	if(!$all)
		return array(
			'all' => 0,
			'spisok' => '<div class="_empty">Проектов нет</div>'
		);

	$send = array(
		'all' => $all,
		'spisok' => ''
	);

	$sql = "SELECT *
			FROM `project`
			WHERE ".$cond."
			ORDER BY `id`";
	$q = query($sql);
	$project = array();
	while($r = mysql_fetch_assoc($q)) {
		$project[$r['id']] = $r;
	}

	foreach($project as $id => $r) {
		$send['spisok'] .=
			'<div class="unit" id="u'.$id.'" val="'.$id.'">'.
				'<h1>'.$r['name'].'</h1>'.
				'<h2>'.$r['about'].'</h2>'.
			'</div>';
	}

	switch($i) {
		case 'spisok': return $send['spisok'];
		default: return $send;
	}
}//project_spisok()




function project_info() {
	if(!$id = _num(@$_GET['id']))
		return 'Ошибочный id.';

	$sql = "SELECT * FROM `project` WHERE `owner_id`=".VIEWER_ID." AND `id`=".$id;
	if(!$r = query_assoc($sql))
		return 'Проекта не существует.';

	return
		mainPageDop().
		'<div id="project-info">'.
			'<div class="headName">'.$r['name'].'<a class="add">Новая задача</a></div>'.
		'</div>';
}//project()

