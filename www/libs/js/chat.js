var	chat = {

	focus_tab : 0,
	in_out_refresh : '',

	enter : function ()
	{
		var id_input = (chat.focus_tab == MAIN_ROOM_CHAN) ? 'main_room_input' :
				(chat.focus_tab == GAME_ROOM_CHAN ? 'game_room_input' : 'chat_input');
		if ($(id_input).get('value') == '' || (chat.focus_tab == GAME_ROOM_CHAN && config['id_party'] == 0))
			return false;
		var request = new Request({
			url: 'chat.php',
			method: 'post',
			onSuccess: function(responseJSON) {
			},
			data : {
				mode: 'add_message',
				message: $(id_input).get('value'),
				chan: chat.focus_tab
			}
		}).send();
		$(id_input).set('value', '');
	},

	refresh : function ()
	{
		new Request.JSON({
			url: 'chat.php',
			method: 'post',
			onSuccess: function(responseJSON) {
				chat.parse_message(responseJSON);
			},
			onError: function(text, error) {
			},
			data : {
				mode: 'get_messages',
				chan: chat.in_out_refresh
			}
		}).send();
	},
	
	parse_message : function (responseJSON)
	{
		if (chat.in_out_refresh == OUT_GAME)
		{
			responseJSON[0].each(function (ele) {
				$('main_room_textbox').appendText(ele['name'] + ' : ' + ele['text']);
				$('main_room_textbox').grab(new Element('br'));
			});
			responseJSON[1].each(function (ele) {
				$('game_room_textbox').appendText(ele['name'] + ' : ' + ele['text']);
				$('game_room_textbox').grab(new Element('br'));
			});
		}
		else if (chat.in_out_refresh == IN_GAME)
		{
			responseJSON.each(function (ele) {
				if (ele['chan'] == INGAME_CHAN)
					$('chat_textbox').appendText('[All] ' + ele['name'] + ' : ' + ele['text']);
				else if (ele['chan'] == INGAME_TEAM_CHAN)
					$('chat_textbox').appendText('[Team] ' + ele['name'] + ' : ' + ele['text']);
				
				$('chat_textbox').grab(new Element('br'));
			});
		}
	}
};