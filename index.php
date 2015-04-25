<?php
require_once('config.php');

_hashRead();
_header();
_mainLinks();

switch($_GET['p']) {
	case 'project':
		switch(@$_GET['d']) {
			case 'info': $html .= project_info(); break;
			case 'list':
			default: $html .= project();
		}
		break;
	default: $html .= project();
}

_footer();
mysql_close();
echo $html;
exit;