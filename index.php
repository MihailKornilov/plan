<?php
require_once('config.php');

_hashRead();
_header();
_mainLinks();

switch($_GET['p']) {
	case 'main':
		switch(@$_GET['d']) {
			case 'project':
				if (@$_GET['d1'] == 'info') {
					$html .= project_info();
					break;
				}
				$html .= project();
				break;
			default: $html .= project();
		}
		break;
	default: $html .= project();
}

_footer();
mysql_close();
echo $html;
exit;