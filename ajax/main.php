<?php
require_once('config.php');
require_once(API_PATH.'/ajax/vk.php');

switch(@$_POST['op']) {
	case 'project_add':
		$name = _txt($_POST['name']);
		$about = _txt($_POST['about']);

		if(empty($name))
			jsonError();

		$sql = "INSERT INTO `project` (
					`owner_id`,
					`name`,
					`about`
				) VALUES (
					".VIEWER_ID.",
					'".addslashes($name)."',
					'".addslashes($about)."'
				)";
		query($sql);

		$send['html'] = utf8(project_spisok(array(), 'spisok'));
		jsonSuccess($send);
		break;
}

jsonError();