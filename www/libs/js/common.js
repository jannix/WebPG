const OUT_GAME = 0;
const IN_GAME = 1;

const MAIN_ROOM_CHAN = 0;
const GAME_ROOM_CHAN = 1;
const INGAME_CHAN = 2;
const INGAME_TEAM_CHAN = 3;

function countElements(parent){
	var realKids = 0;
	var kids = parent.childNodes.length;
        var i = 0;
	while(i < kids){
		if(parent.childNodes[i].nodeType != 3){
			realKids += 1 + countElements(parent.childNodes[i]);
		}
		i++;
	}
	return realKids;
}

function	countdown(id, count, end_fct)
{
	if (count <= 0)
	{
		end_fct.call();
		return true;
	}
	$(id).set("html", parseInt(count / 1000) + " seconds...");
	count = count - 1000;
	setTimeout(function(){countdown(id, count, end_fct)}, 1000);
}

function	countdown_cs(id, count, end_fct)
{
	if (count <= 0)
	{
		$(id).set('html', "Ok");
		if (end_fct != null)
			end_fct.call();
		return true;
	}
	$(id).set("html", parseInt(count) + " ms");
	count = count - 10;
	setTimeout(function(){countdown_cs(id, count, end_fct)}, 10);
}

window.addEvent('domready', function(){

	var myKeyboardEvents = new Keyboard({
		defaultEventType: 'keyup',
		events: {
			'ctrl+q': function () {alert(countElements(document.body) + " elements\n\
id_party: " + JSON.encode(config['id_party']) + "\n\
id_creator: " + JSON.encode(config['id_party_creator']) + "\n\
id_user: " + JSON.encode(config['id_user']) + "\n\
id_character: " + JSON.encode(config['id_character']) + "\n\
party_is_started: " + JSON.encode(config['party_is_started']) + "\n");}
		}
	}).activate();

});