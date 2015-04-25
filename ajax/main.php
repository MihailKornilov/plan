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
	case 'project_edit':
		if(!$project_id = _num($_POST['id']))
			jsonError();

		$name = _txt($_POST['name']);
		$about = _txt($_POST['about']);

		if(empty($name))
			jsonError();

		$sql = "SELECT * FROM `project` WHERE `owner_id`=".VIEWER_ID." AND `id`=".$project_id;
		if(!$r = query_assoc($sql))
			jsonError();

		$sql = "UPDATE `project`
		        SET `name`='".addslashes($name)."',
		            `about`='".addslashes($about)."'
				WHERE `id`=".$project_id;
		query($sql);

		jsonSuccess();
		break;

	case 'task_add':
		if(!$project_id = _num($_POST['project_id']))
			jsonError();

		$name = _txt($_POST['name']);

		if(empty($name))
			jsonError();

		$sql = "SELECT * FROM `project` WHERE `owner_id`=".VIEWER_ID." AND `id`=".$project_id;
		if(!$r = query_assoc($sql))
			jsonError();

		$sql = "INSERT INTO `task` (
					`owner_id`,
					`project_id`,
					`name`
				) VALUES (
					".VIEWER_ID.",
					".$project_id.",
					'".addslashes($name)."'
				)";
		query($sql);

		$v = array(
			'project_id' => $project_id
		);
		$send['html'] = utf8(task_spisok($v, 'spisok'));
		jsonSuccess($send);
		break;
	case 'task_edit':
		if(!$task_id = _num($_POST['task_id']))
			jsonError();

		$name = _txt($_POST['name']);

		if(empty($name))
			jsonError();

		$sql = "SELECT * FROM `task` WHERE `owner_id`=".VIEWER_ID." AND `id`=".$task_id;
		if(!$r = query_assoc($sql))
			jsonError();

		$sql = "UPDATE `task`
		        SET `name`='".addslashes($name)."'
				WHERE `id`=".$task_id;
		query($sql);

		$v = array(
			'project_id' => $r['project_id']
		);
		$send['html'] = utf8(task_spisok($v, 'spisok'));
		jsonSuccess($send);
		break;
	case 'task_del':
		if(!$task_id = _num($_POST['task_id']))
			jsonError();

		$sql = "SELECT * FROM `task` WHERE `owner_id`=".VIEWER_ID." AND `id`=".$task_id;
		if(!$r = query_assoc($sql))
			jsonError();

		$sql = "DELETE FROM `task` WHERE `id`=".$task_id;
		query($sql);

		$sql = "DELETE FROM `action` WHERE `task_id`=".$task_id;
		query($sql);

		$data = task_spisok(array(
			'project_id' => $r['project_id']
		));

		$send['html'] = utf8($data['spisok']);
		jsonSuccess($send);
		break;

	case 'action_add':
		if(!$task_id = _num($_POST['task_id']))
			jsonError();

		$name = _txt($_POST['name']);

		if(empty($name))
			jsonError();

		$sql = "SELECT * FROM `task` WHERE `owner_id`=".VIEWER_ID." AND `id`=".$task_id;
		if(!$r = query_assoc($sql))
			jsonError();

		$sql = "INSERT INTO `action` (
					`owner_id`,
					`task_id`,
					`name`
				) VALUES (
					".VIEWER_ID.",
					".$task_id.",
					'".addslashes($name)."'
				)";
		query($sql);

		$v = array(
			'project_id' => $r['project_id']
		);
		$send['html'] = utf8(task_spisok($v, 'spisok'));
		jsonSuccess($send);
		break;
}

jsonError();