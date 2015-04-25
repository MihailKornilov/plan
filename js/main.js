var	hashLoc,
	hashSet = function(hash) {
		if(!hash && !hash.p)
			return;
		hashLoc = hash.p;
		var s = true;
		switch(hash.p) {
			default:
				if(hash.d) {
					hashLoc += '_' + hash.d;
					if(hash.d1)
						hashLoc += '_' + hash.d1;
				}
		}
		if(s)
			VK.callMethod('setLocation', hashLoc);
	};


$(document)
	.on('click', '#project .unit', function() {
		location.href = URL + '&p=project&d=info&id=' + $(this).attr('val');
	})

	.on('click', '#proect-edit', function() {
		var html = '<table id="proect-edit-tab">' +
				'<tr><td class="label r">��������:<td><input id="name" type="text" value="' + PROJECT.name + '" />' +
				'<tr><td class="label r top">��������:<td><textarea id="about">' + PROJECT.about + '</textarea>' +
				'</table>',
			dialog = _dialog({
				head:'�������������� �������',
				content:html,
				butSubmit:'���������',
				submit:submit
			});
		$('#name').focus().keyEnter(submit);
		$('#about').autosize();
		function submit() {
			var send = {
				op:'project_edit',
				id:PROJECT.id,
				name:$('#name').val(),
				about:$('#about').val()
			};
			if(!send.name) {
				dialog.err('�� ������� ��������');
				$('#name').focus();
			} else {
				dialog.process();
				$.post(AJAX_MAIN, send, function(res) {
					if(res.success) {
						dialog.close();
						_msg('��������');
						document.location.reload();
					} else
						dialog.abort();
				}, 'json');
			}
		}
	})

	.on('click', '.task-edit', function() {
		var t = $(this),
			p = t.parent().parent(),
			task_id = p.attr('val'),
			task_name = p.find('.name').html(),
			html = '<table id="task-edit-tab">' +
				'<tr><td class="label r">������:<td><b>' + PROJECT.name + '</b>' +
				'<tr><td class="label r">������:<td><input id="name" type="text" value="' + task_name + '" />' +
				'</table>',
			dialog = _dialog({
				width:430,
				head:'�������������� ������',
				content:html,
				butSubmit:'���������',
				submit:submit
			});
		$('#name').focus().keyEnter(submit);
		function submit() {
			var send = {
				op:'task_edit',
				task_id:task_id,
				name:$('#name').val()
			};
			if(!send.name) {
				dialog.err('�� ������� ��������');
				$('#name').focus();
			} else {
				dialog.process();
				$.post(AJAX_MAIN, send, function(res) {
					if(res.success) {
						$('#spisok').html(res.html);
						dialog.close();
						_msg('��������');
					} else
						dialog.abort();
				}, 'json');
			}
		}
	})
	.on('click', '.task-del', function() {
		var t = $(this),
			p = t.parent().parent(),
			task_id = p.attr('val'),
			task_name = p.find('.name').html(),
			html =
				'<center>' +
					'<br />' +
					'����������� �������� ������<br />' +
					'<b>' + task_name + '</b>.' +
					'<br />' +
					'<br />' +
				'</center>',
			dialog = _dialog({
				width:300,
				head:'�������� ������',
				content:html,
				butSubmit:'�������',
				submit:submit
			});
		function submit() {
			var send = {
				op:'task_del',
				task_id:task_id
			};
			dialog.process();
			$.post(AJAX_MAIN, send, function(res) {
				if(res.success) {
					$('#spisok').html(res.html);
					dialog.close();
					_msg('�������');
				} else
					dialog.abort();
			}, 'json');
		}
	})

	.on('click', '#project-info .action-add', function() {//���������� ����������� ��������
		var t = $(this),
			p = t.parent().parent(),
			head = p.find('.name').html(),
			task_id = p.attr('val'),
			html = '<table id="action-add-tab">' +
				'<tr><td class="label r">������:<td><b>' + PROJECT.name + '</b>' +
				'<tr><td class="label r">������:<td><u>' + head + '</u>' +
				'<tr><td class="label r">��������:<td><input id="name" type="text" />' +
				'</table>',
			dialog = _dialog({
				head:'���������� �������� � ������',
				content:html,
				submit:submit
			});
		$('#name').focus().keyEnter(submit);
		$('#about').autosize();
		function submit() {
			var send = {
				op:'action_add',
				task_id:task_id,
				name:$('#name').val()
			};
			if(!send.name) {
				dialog.err('�� ������� ��������');
				$('#name').focus();
			} else {
				dialog.process();
				$.post(AJAX_MAIN, send, function(res) {
					if(res.success) {
						$('#spisok').html(res.html);
						dialog.close();
						_msg('����� �������� ���������');
					} else
						dialog.abort();
				}, 'json');
			}
		}
	})

	.ready(function() {
		if($('#project').length) {
			$('.add').click(function() {
				var t = $(this),
					html = '<table id="project-tab-add">' +
						'<tr><td class="label r">��������:<td><input id="name" type="text" />' +
						'<tr><td class="label r top">��������:<td><textarea id="about"></textarea>' +
						'</table>',
					dialog = _dialog({
						head:'���������� ������ �������',
						content:html,
						submit:submit
					});
				$('#name').focus().keyEnter(submit);
				$('#about').autosize();
				function submit() {
					var send = {
						op:'project_add',
						name:$('#name').val(),
						about:$('#about').val()
					};
					if(!send.name) {
						dialog.err('�� ������� ��������');
						$('#name').focus();
					} else {
						dialog.process();
						$.post(AJAX_MAIN, send, function(res) {
							if(res.success) {
								$('#spisok').html(res.html);
								dialog.close();
								_msg('����� ������ ��������');
							} else
								dialog.abort();
						}, 'json');
					}
				}
			});
		}
		if($('#project-info').length) {
			$('.add').click(function() {
				var t = $(this),
					html = '<table id="task-tab-add">' +
						'<tr><td class="label r">������:<td><input id="name" type="text" />' +
						'</table>',
					dialog = _dialog({
						top:30,
						width:420,
						head:'���������� ����� ������',
						butSubmit:'��������',
						content:html,
						submit:submit
					});
				$('#name').focus().keyEnter(submit);
				function submit() {
					var send = {
						op:'task_add',
						project_id:PROJECT.id,
						name:$('#name').val()
					};
					if(!send.name) {
						dialog.err('�� ������� �������� ������');
						$('#name').focus();
					} else {
						dialog.process();
						$.post(AJAX_MAIN, send, function(res) {
							if(res.success) {
								$('#spisok').html(res.html);
								dialog.close();
								_msg('����� ������ ���������');
							} else
								dialog.abort();
						}, 'json');
					}
				}
			});
		}
	});
