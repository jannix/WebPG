var	timer;
var	timer_refresh;

var	map = {
	
	show_move_perimeter : function () {
		var distance = character['agility'] / CFG_GAME_AGI_BY_CASE;
		for (var y = 0; y <= distance; y++)
		{
			for (var x = 0; x <= distance; x++)
			{
				$('map_' + x + '_' + y).setStyles({
					border: '1px solid white'
				});
				$('map_' + x + '_' + -y).setStyles({
					border: '1px solid white'
				});
				$('map_' + -x + '_' + y).setStyles({
					border: '1px solid white'
				});
				$('map_' + -x + '_' + -y).setStyles({
					border: '1px solid white'
				});
			}
		}
	},
	
	move_to : function (x, y) {
		x = parseInt(x);
		y = parseInt(y);
		var x_min = x - CFG_GAME_MAPDISTANCE;
		var x_max = x + CFG_GAME_MAPDISTANCE;
		var y_min = y - CFG_GAME_MAPDISTANCE;
		var y_max = y + CFG_GAME_MAPDISTANCE;
		
		for (var y_pos = CFG_GAME_MAPDISTANCE; y_pos >= 0; y_pos--)
		{
			$('map_y' + y_pos).set('html', y + y_pos);
			$('map_y' + (-y_pos)).set('html', y - y_pos);
			for (var x_pos = -CFG_GAME_MAPDISTANCE; x_pos <= 0; x_pos++)
			{
				$('map_' + (x_pos) + '_' + (y_pos)).empty();
				$('map_' + (x_pos) + '_' + (-y_pos)).empty();
				$('map_' + (-x_pos) + '_' + (y_pos)).empty();
				$('map_' + (-x_pos) + '_' + (-y_pos)).empty();
				if (map_cases[y - y_pos] && map_cases[y - y_pos][x - x_pos])
					$('map_' + (-x_pos) + '_' + (-y_pos)).setStyle('background-image',
											'url(style/images/' + images[map_cases[y - y_pos][x - x_pos]] + ')');
				else
					$('map_' + (-x_pos) + '_' + (-y_pos)).setStyle('background-image', 'none');
				if (map_cases[y - y_pos] && map_cases[y - y_pos][x + x_pos])
					$('map_' + (x_pos) + '_' + (-y_pos)).setStyle('background-image',
											'url(style/images/' + images[map_cases[y - y_pos][x + x_pos]] + ')');
				else
					$('map_' + (x_pos) + '_' + (-y_pos)).setStyle('background-image', 'none');
				if (map_cases[y + y_pos] && map_cases[y + y_pos][x - x_pos])
					$('map_' + (-x_pos) + '_' + (y_pos)).setStyle('background-image',
											'url(style/images/' + images[map_cases[y + y_pos][x - x_pos]] + ')');
				else
					$('map_' + (-x_pos) + '_' + (y_pos)).setStyle('background-image', 'none');
				if (map_cases[y + y_pos] && map_cases[y + y_pos][x + x_pos])
					$('map_' + (x_pos) + '_' + (y_pos)).setStyle('background-image',
											'url(style/images/' + images[map_cases[y + y_pos][x + x_pos]] + ')');
				else
					$('map_' + (x_pos) + '_' + (y_pos)).setStyle('background-image', 'none');
			}
		}
		for (var x_pos = CFG_GAME_MAPDISTANCE; x_pos >= 0; x_pos--)
		{
			$('map_x' + x_pos).set('html', x + parseInt(x_pos));
			$('map_x' + (-x_pos)).set('html', x - x_pos);
		}
		for (var i = 0; chars[i]; i++)
		{
			if (chars[i]['id_user'] == config['id_user'])
			{
				chars[i]['x'] = x;
				chars[i]['y'] = y;
			}
			if (chars[i]['x'] >= x_min && chars[i]['x'] <= x_max &&
			    chars[i]['y'] >= y_min && chars[i]['y'] <= y_max)
			{
				$('map_' + (chars[i]['x'] - x) + '_' + (chars[i]['y'] - y)).grab(
					new Element('img', {
						src : 'style/images/' + images[chars[i]['id_image']],
						alt : ''
					})
				);
			}
		}
		for (var i = 0; buildings[i]; i++)
		{
			if (buildings[i]['x'] >= x_min && buildings[i]['x'] <= x_max &&
			    buildings[i]['y'] >= y_min && buildings[i]['y'] <= y_max)
			{
				$('map_' + (buildings[i]['x'] - x) + '_' + (buildings[i]['y'] - y)).grab(
					new Element('img', {
						src : 'style/images/' + images[buildings[i]['id_image']],
						alt : ''
					})
				);
			}
		}
	}
};

var	building = {

	construct : function () {
		var div;
		for (var i = 0; buildings[i]; i++)
		{
			div = new Element('div', {
				id	: 'building_' + buildings[i]['id'],
				'class'	: 'shop'
			});
			$('shop').grab(div);
			
			for (var j = 0; buildings[i]['objects'][j]; j++)
			{
				div.adopt(
					new Element('span', {
						html: buildings[i]['objects'][j]['name'] + ' (' + buildings[i]['objects'][j]['price'] + ' PO) - '
					}),
					new Element('span', {
						html:	'Buy',
						id:	buildings[i]['id'] + '_' + buildings[i]['objects'][j]['id'],
						events: {
							'click' : function () {
								new Request.JSON({
									url: 'shop.php',
									method: 'post',
									onSuccess: function(responseJSON) {
										if (responseJSON == true)
										{
											
										}
										else if (responseJSON == false)
										{
										}
									},
									onError: function(text, error) {
									},
									data : {
										id: this.id
									}
								}).send();
							}
						}
					}),
					new Element('br'),
					new Element('span', {
						html: buildings[i]['objects'][j]['description']
					}),
					new Element('br')
				);
			}
		}
		$('shop').fade('hide');
	},

	open : function (id_building) {
		$('shop').fade('show');
		for (var i = 0; buildings[i]; i++)
		{
			if (buildings[i]['id'] == id_building)
			{
				$('building_' + id_building).fade('show');
			}
			else
				$('building_' + buildings[i]['id']).fade('hide');
		}
	},

	load_objects : function () {
	}
};

function	action_refresher(id)
{
	new Request.JSON({
		url: 'action.php',
		method: 'post',
		onSuccess: function(responseJSON, responseText) {
			$('response').set('text', responseText);
			if (responseJSON['chars'])
			{
				for (var i = 0; responseJSON['chars'][i]; i++)
				{
					for (var j = 0; chars[j]; j++)
					{
						if (chars[j]['id_user'] == responseJSON['chars'][i]['id_user'])
						{
							if (chars[j]['x'] != responseJSON['chars'][i]['x'] ||
							    chars[j]['y'] != responseJSON['chars'][i]['y'])
							{
								//$('map_' + (chars[j]['x'] - character['x']) + '_' + (chars[j]['y'] - character['y'])).empty();
								chars[j]['x'] = responseJSON['chars'][i]['x'];
								chars[j]['y'] = responseJSON['chars'][i]['y'];
								/*$('map_' + (chars[j]['x'] - character['x']) + '_' + (chars[j]['y'] - character['y'])).grab(
									new Element('img', {
										src : 'style/images/' + images[chars[j]['id_image']],
										alt : ''
									})
								);*/
							}
						}
					}
				}
				if (!responseJSON['move'])
					map.move_to(responseJSON['user']['x'], responseJSON['user']['y']);
			}
			if (responseJSON['move'])
			{
				$('response').set('html', responseJSON['move'] - $time());
				countdown_cs('countdown_move', responseJSON['move'] - $time());
				map.move_to(responseJSON['user']['x'], responseJSON['user']['y']);
			}
			if (responseJSON['attack'])
			{
				$('response').set('html', responseJSON['attack'] - $time());
				countdown_cs('countdown_attack', responseJSON['attack'] - $time());
			}
			if (responseJSON['chat'])
			{
				chat.parse_message(responseJSON['chat']);
			}
			if (responseJSON['building'])
			{
				building.open(responseJSON['building']);
			}
			if (responseJSON['user'])
			{
				//building.open(responseJSON['building']);
				$('char_lvl').set('text', responseJSON['user']['lvl']);
				$('char_xp').set('text', responseJSON['user']['xp']);
				$('char_x').set('text', responseJSON['user']['x']);
				$('char_y').set('text', responseJSON['user']['y']);
				$('char_hp').set('text', responseJSON['user']['hp']);
				$('char_mp').set('text', responseJSON['user']['mp']);
				$('char_hpmax').set('text', responseJSON['user']['hpmax']);
				$('char_mpmax').set('text', responseJSON['user']['mpmax']);
				$('char_kills').set('text', responseJSON['user']['kills']);
				$('char_deaths').set('text', responseJSON['user']['deaths']);
				$('char_po').set('text', responseJSON['user']['po']);
				$('char_atk_phy').set('text', responseJSON['user']['atk_phy']);
				$('char_atk_mag').set('text', responseJSON['user']['atk_mag']);
				$('char_def_phy').set('text', responseJSON['user']['def_phy']);
				$('char_def_mag').set('text', responseJSON['user']['def_mag']);
				$('char_agility').set('text', responseJSON['user']['agility']);
				$('char_luck').set('text', responseJSON['user']['luck']);
			}
			if (responseJSON == true)
			{
				document.location = "gameUI.php";
			}
			else if (responseJSON == false)
			{
			}
		},
		onError: function(text, error) {
		},
		data : {
			id: id
		}
	}).send();
	$clear(timer_refresh);
	timer_refresh = action_refresher.periodical(1500);
}

window.addEvent('resize', function(){
	$clear(timer);
	timer = (function(){
		$('chat_table').setStyle('width', document.body.getSize().x - $('mini_map').getSize().x - $('character_status').getSize().x - $('map').getSize().x);
		$('chat_textbox').setStyle('height', document.body.getSize().y - $('menu').getSize().y - $('character_skills').getSize().y - 24);
		//$('chat_input').setStyle('width', $('typingbox').getSize().x - $('chat_button').getSize().x - $('chat_target').getSize().x - 7 + 'px');
	}).delay(50);
});

window.addEvent('domready', function(){

	var menu_buttons = new Array(
		new Array('Home', '', function () {document.location = "home.php";}),
		new Array('Option', 'option', function () {menu.switch_center('option');}),
		new Array('Game', 'game', function () {menu.switch_center('game');})
	);

	menu_buttons.each(function (el) {
		menu.add_button(el);
	});

	building.construct();
	
	chat.in_out_refresh = IN_GAME;

	var myKeyboardEvents = new Keyboard({
		defaultEventType: 'keyup',
		events: {
			/*'shift+h': fn1,
			'ctrl+shift+h': fn2,
			'shift+ctrl+h': fn3,
			'keydown:shift+d': fn5*/
			'enter': chat.enter
		}
	});

	$('chat_input').addEvents({
		'keydown' : function (event) {
			if (event.key == "tab" && !event.shift)
			{
				if ($('chat_target').get('value') == INGAME_CHAN)
					$('chat_target').set('value', INGAME_TEAM_CHAN);
				else
					$('chat_target').set('value', INGAME_CHAN);
				window.chat.focus_tab = $('chat_target').get('value');
				return false;
			}
		},
		'click' : function () {
			window.chat.focus_tab = $('chat_target').get('value');
			myKeyboardEvents.activate();
		},
		'blur' : function () {
			myKeyboardEvents.deactivate();
		}
	});
	$('chat_button').addEvents({
		'click' : function () {
			window.chat.focus_tab = $('chat_target').get('value');
			chat.enter();
		}
	});
	
	$('chat_table').setStyle('width', document.body.getSize().x - $('mini_map').getSize().x - $('character_status').getSize().x - $('map').getSize().x);
	$('chat_textbox').setStyle('height', document.body.getSize().y - $('menu').getSize().y - $('character_skills').getSize().y - 24);	
	//$('chat_input').setStyle('width', $('typingbox').getSize().x - $('chat_button').getSize().x - $('chat_target').getSize().x - 7 + 'px');

	$$('.map_cell').addEvent('click', function () {action_refresher(this.id)});

	map.show_move_perimeter();

	menu.switch_center('game');

	timer_refresh = action_refresher.periodical(1500);
});