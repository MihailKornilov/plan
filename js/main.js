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
		location.href = URL + '&p=main&d=project&d1=info&id=' + $(this).attr('val');
	})

	.ready(function() {
		if($('#project').length) {
			$('.add').click(function() {
				var t = $(this),
					html = '<table id="project-tab-add">' +
						'<tr><td class="label r">Название:<td><input id="name" type="text" />' +
						'<tr><td class="label r top">Описание:<td><textarea id="about"></textarea>' +
						'</table>',
					dialog = _dialog({
						head:'Добавление нового проекта',
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
						dialog.err('Не указано название');
						$('#name').focus();
					} else {
						dialog.process();
						$.post(AJAX_MAIN, send, function(res) {
							if(res.success) {
								$('#spisok').html(res.html);
								dialog.close();
								_msg('Новый проект добавлен');
							} else
								dialog.abort();
						}, 'json');
					}
				}
			});
		}
	});
