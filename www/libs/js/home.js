var	timer_resize;
var	timer_chat;
var	timer_refresh_parties;
var	timer_refresh_players;
var	timer_is_started;

var	party = {

	create : function ()
	{
		new MooDialog.Prompt('What is the name of your party?', function (ret) {
			var request = new Request.JSON({
				url: 'party.php',
				method: 'post',
				onComplete: function(responseJSON) {
					if (responseJSON[0] > 0 && responseJSON[1] > 0)
					{
						$('prelaunch_party').fade('show');
						$('div_parties').fade('hide');
						config['id_party'] = responseJSON[0];
						config['id_character'] = responseJSON[1];
						config['id_party_creator'] = config['id_user'];
						$('button_start_party').set('disabled', true);
						$('button_start_party').fade('show');
						$('party_title').set('html', ret);
						$clear(timer_refresh_parties);
						timer_is_started = party.is_started.periodical(5000);
						timer_refresh_players = party.refresh_players.periodical(3000);
						party.place_my_char('{"t":0,"p":0}');
						party.refresh_players();
					}
					else if (responseJSON == false)
					{
						new MooDialog.Alert("Can't create a party.");
					}
				},
				data: {
					mode : 'add_party',
					title : ret
				}
			}).send();
		});
	},

	rejoin : function (id_party, name_party)
	{
		var request = new Request.JSON({
			url: 'party.php',
			method: 'post',
			onComplete: function(responseJSON) {
				if (responseJSON > 0)
				{
					$('prelaunch_party').fade('show');
					$('div_parties').fade('hide');
					$('button_start_party').fade('hide');
					$('party_title').set('html', name_party);
					config['id_party'] = id_party;
					config['id_character'] = responseJSON;
					$clear(timer_refresh_parties);
					timer_is_started = party.is_started.periodical(5000);
					timer_refresh_players = party.refresh_players.periodical(3000);
					party.refresh_players();
				}
				else if (responseJSON == false)
				{
					new MooDialog.Alert("Can't rejoin the party.");
				}
			},
			data: {
				mode : 'add_player',
				id_party : id_party
			}
		}).send();
	},

	leave : function ()
	{
		new MooDialog.Confirm('Are you sure to leave your party?', function (ret) {
			var request = new Request.JSON({
				url: 'party.php',
				method: 'post',
				onComplete: function(responseJSON) {
					if (responseJSON == true)
					{
						$('prelaunch_party').fade('hide');
						$('div_parties').fade('show');
						config['id_party'] = 0;
						config['id_character'] = 0;
						$clear(timer_refresh_players);
						$clear(timer_is_started);
						timer_refresh_parties = party.refresh_parties.periodical(3000);
						party.refresh_parties();
					}
					else if (responseJSON == false)
					{
						new MooDialog.Alert("Can't leave the party.");
					}
				},
				data: {
					mode : 'leave_party'
				}
			}).send();
		});
	},

	start : function ()
	{
		var request = new Request.JSON({
			url: 'party.php',
			method: 'post',
			onComplete: function(responseJSON) {
				if (responseJSON == true)
				{
					$clear(timer_is_started);
					party.is_started();
				}
				else if (responseJSON == false)
					new MooDialog.Alert("Can't start the party.");
			},
			data: {
				mode : 'start_party',
				id_party : config['id_party']
			}
		}).send();
	},

	init_event_swap_position : function ()
	{
		$$('#players div.my_char').addEvent('mousedown', function(event) {
			event.stop();

			var from = this;

			var clone = from.clone().setStyles(from.getCoordinates()).setStyles({
				opacity: 0.7,
				position: 'absolute'
			}).inject(document.body);

			var drag = new Drag.Move(clone, {

				droppables: $$('#players div'),

				onDrop: function(dragging, to) {

					dragging.destroy();

					if (to != null && to.get('html') == '')
					{
						var target = JSON.decode(to.getProperties('rel', 'alt', 'id')['id']);
						var request = new Request.JSON({
							url: 'party.php',
							method: 'post',
							onComplete: function(responseJSON) {
								if (responseJSON == true)
								{
									var tParent = to.getParent();
									to.dispose();
									var fParent = from.getParent();
									from.dispose();
									from.inject(tParent);
									to.inject(fParent);
									var tmp = to.id;
									to.id = from.id;
									from.id = tmp;
								}
								else if (responseJSON == false)
								{
									new MooDialog.Alert("Can't change your position and your team.");
								}
							},
							data: {
								mode : 'swap_position',
								id_party : config['id_party'],
								team : target['t'],
								position : target['p']
							}
						}).send();
					}
				},
				onEnter: function(dragging, to){
					if (to.get('html') == '')
						to.highlight('grey', 'red');
				},
				onLeave: function(dragging, to){
					to.tween('background-color', 'grey');
				},
				onCancel: function(dragging){
					dragging.destroy();
				}
			});
			drag.start(event);
		});
	},

	refresh_players : function ()
	{
		new Request.JSON({
			url: 'party.php',
			method: 'post',
			onSuccess: function(responseJSON) {
				if (responseJSON.length > 1 && config['id_party_creator'] == config['id_user'])
					$('button_start_party').set('disabled', false);
				else if (responseJSON.length == 1)
				{
					$('button_start_party').set('disabled', true);
				}
				else if (responseJSON.length == 0)
				{
					$('prelaunch_party').fade('hide');
					$('div_parties').fade('show');
					config['id_party'] = 0;
					$clear(timer_refresh_players);
					timer_refresh_parties = party.refresh_parties.periodical(3000);
					new MooDialog.Alert("The party has been closed.");
				}
				for (var i = 0; i < LIMIT_PLAYERS / 2; i++)
				{
					$('{"t":0,"p":' + i + '}').set('html', '');
					$('{"t":1,"p":' + i + '}').set('html', '');
				}

				var toPos = '';
				responseJSON.each(function (ele) {
					$('{"t":' + ele['team'] + ',"p":' + ele['position'] + '}').set('html', ele['name']);
					if (ele['id_user'] == config['id_user'])
						toPos = '{"t":' + ele['team'] + ',"p":' + ele['position'] + '}';
				});
				party.place_my_char(toPos);
			},
			onError: function(text, error) {
			},
			data : {
				mode: 'get_players',
				id_party : config['id_party']
			}
		}).send();
	},

	refresh_parties : function ()
	{
		new Request.JSON({
			url: 'party.php',
			method: 'post',
			onSuccess: function(responseJSON) {
				$('parties').empty();
				responseJSON.each(function (ele) {
					$('parties').grab(new Element('tr', {
						title: ele['timer_gamecreate'],
					}).adopt(
						new Element('td', {
							html: ele['count_players']
						}),
						new Element('td', {
							html: ele['title']
						}),
						new Element('td').grab(
							new Element('button', {
								text: 'Rejoin',
								id: ele['id'],
								name: ele['title'],
								events : {
									'click' : function () {
										party.rejoin(this.id, this.name);
									}
								}
							})
						)
					)
					);
				});
			},
			onError: function(text, error) {
			},
			data : {
				mode: 'get_parties'
			}
		}).send();
	},

	is_started : function ()
	{
		new Request.JSON({
			url: 'party.php',
			method: 'post',
			onSuccess: function(responseJSON) {
				if (responseJSON != false)
				{
					$clear(timer_is_started);
					countdown("party_countdown",
						  responseJSON - $time(),
						  function () {
							$('characters_selection').fade("in");
						  });
				}
			},
			onError: function(text, error) {
			},
			data : {
				mode: 'is_started',
				id_party: config['id_party']
			}
		}).send();
	},

	place_my_char : function (toPos)
	{
		var from = false;
		for (var i = 0; i < LIMIT_PLAYERS / 2; i++)
		{
			if (from == false && $('{"t":0,"p":' + i + '}').hasClass('my_char'))
				from = $('{"t":0,"p":' + i + '}');
			else if (from == false && $('{"t":1,"p":' + i + '}').hasClass('my_char'))
				from = $('{"t":1,"p":' + i + '}');
		}
		if (from == false)
		{
			$(toPos).addClass('my_char');
			party.init_event_swap_position();
		}
		else if (from != $(toPos))
		{
			var tmp = $(toPos).clone();
			var fParent = from.getParent();
			var tmp_id = from.id;
			var tmp_html = from.get('html');
			from.id = toPos;
			from.set('html', tmp.get('html'));
			from.inject($(toPos).getParent());
			tmp.id = tmp_id;
			tmp.set('html', tmp_html);
			tmp.inject(fParent);
			$(toPos).destroy();
		}
	}
};

function	resize_all_windows()
{
	//$('main_chat').setStyle('height', document.body.getSize().y - $('menu').getSize().y + 'px');
	//$('game_chat').setStyle('height', document.body.getSize().y - $('menu').getSize().y + 'px');
	$('main_room_textbox').setStyle('max-height', document.body.getSize().y - $('menu').getSize().y - 24 + 'px');
	$('game_room_textbox').setStyle('max-height', document.body.getSize().y - $('menu').getSize().y - 24 + 'px');
	//$('main_room_input').setStyle('width', $('main_room_typingbox').getSize().x - $('main_room_button').getSize().x - 2 +'px');
	//$('game_room_input').setStyle('width', $('game_room_typingbox').getSize().x - $('game_room_button').getSize().x - 2 + 'px');
}

window.addEvent('resize', function(){
	$clear(timer_resize);
	timer_resize = (function(){
		resize_all_windows();
	}).delay(50);
});

window.addEvent('domready', function(){

	// Create the menu
	var menu_buttons = new Array(
		new Array('Home', 'home', function () {document.location = "home.php";}),
		new Array('Option', 'option', function () {menu.switch_center('option')}),
		new Array('Main Room', 'main_room', function () {menu.switch_center('main_room')}),
		new Array('Game Room', 'game_room', function () {menu.switch_center('game_room')}),
		new Array('Disco', '', function () {document.location = "login.php";})
	);

	menu_buttons.each(function (el) {
		menu.add_button(el);
	});

	$('menu_home').addClass('menu_focus');

	// Resize content div at the good size
	resize_all_windows();

	// Events
	var myKeyboardEvents = new Keyboard({
		defaultEventType: 'keyup',
		events: {
			'enter': chat.enter
		}
	});

	chat.in_out_refresh = OUT_GAME;

	$('main_room_input').addEvents({
		'click' : function () {
			window.chat.focus_tab = MAIN_ROOM_CHAN;
			myKeyboardEvents.activate();
		},
		'blur' : function () {
			myKeyboardEvents.deactivate();
		}
	});
	$('main_room_button').addEvents({
		'click' : function () {
			window.chat.focus_tab = MAIN_ROOM_CHAN;
			chat.enter();
		}
	});
	$('game_room_input').addEvents({
		'click' : function () {
			window.chat.focus_tab = GAME_ROOM_CHAN;
			myKeyboardEvents.activate();
		},
		'blur' : function () {
			myKeyboardEvents.deactivate();
		}
	});
	$('game_room_button').addEvents({
		'click' : function () {
			window.chat.focus_tab = GAME_ROOM_CHAN;
			chat.enter();
		}
	});

	$('button_create_party').addEvent('click', function () {
		party.create();
	});
	$('button_leave_party').addEvent('click', function () {
		party.leave();
	});
	$('button_start_party').addEvent('click', function () {
		party.start();
	});
	$('button_choose_character').addEvent('click', function () {
		$('characters_selection').fade('toggle');
	});
	$('button_redirect_game').addEvent('click', function () {
		document.location = "gameUI.php";
	});

	$$('.char_pick_up').addEvent('click', function () {
		new Request.JSON({
			url: 'character.php',
			method: 'post',
			onSuccess: function(responseJSON) {
				if (responseJSON == true)
				{
					new MooDialog.Confirm("Your character has been chosen.\n\
Would you want to be redirected to the game?", function () {document.location = "gameUI.php";});
					$('button_redirect_game').fade('show');
				}
				else if (responseJSON == false)
				{
					$clear(timer_is_started);
					countdown("party_countdown",
						  responseJSON - $time(),
						  function () {
							$('characters_selection').fade("in");
						  });
				}
			},
			onError: function(text, error) {
			},
			data : {
				mode: 'select_char',
				id_character: config['id_character'],
				id_character_model: this.id
			}
		}).send();
	});

	// Disable selection in these DIV
	$('prelaunch_party').onmousedown = new Function("return false");
	$('div_parties').onmousedown = new Function("return false");
	$('menu').onmousedown = new Function("return false");

	party.init_event_swap_position();

	if (config['id_party'] != 0)
	{
		$('prelaunch_party').fade('show');
		$('div_parties').fade('hide');
	}
	if (config['id_party_creator'] == config['id_user'])
	{
		$('button_start_party').fade('show');
	}
	else
		$('button_start_party').fade('hide');

	// Refresh functions
	if (config['id_party'] == 0)
	{
		$('button_choose_character').fade('hide');
		$('button_redirect_game').fade('hide');
		timer_refresh_parties = party.refresh_parties.periodical(3000);
	}
	else
	{
		if (config['id_character'] == 0)
		{
			timer_refresh_players = party.refresh_players.periodical(3000);
		}
		if (config['party_is_started'] > 0)
		{
			$('button_choose_character').fade('show');
			if (config['id_character_model'] > 0)
				$('button_redirect_game').fade('show');
			else
				$('button_redirect_game').fade('hide');
		}
		else
		{
			timer_is_started = party.is_started.periodical(5000);
			$('button_redirect_game').fade('hide');
			$('button_choose_character').fade('hide');
		}
	}

	time_chat = chat.refresh.periodical(3000);
});