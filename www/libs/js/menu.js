var menu = {

	buttons : new Array(),

	add_button : function (element)
	{
		var td = new Element('td', {
			html : element[0],
			id : 'menu_' + element[1],
			events : {
				click : element[2]
			}
		});
		if (element[1] != '')
			this.buttons.push(element[1]);
		$('menu').getFirst().getFirst().getFirst().grab(td);
	},
	
	switch_center : function (id)
	{
		var max = this.buttons.length;
		for (var i = 0; i < max; i++)
		{
			if (this.buttons[i] == id)
			{
				$('center_' + id).fade('show');
				$('menu_' + id).addClass('menu_focus');
			}
			else
			{
				$('center_' + this.buttons[i]).fade('hide');
				$('menu_' + this.buttons[i]).removeClass('menu_focus');
			}
		}
	}
};