<html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<link href="./logs_files/jquery-ui.css" rel="stylesheet" type="text/css">
		<script src="./logs_files/jquery.min.js"></script>
		<script src="./logs_files/jquery-ui.min.js"></script>
		<script type="text/javascript" src="./logs_files/date.js"></script>
		<script type="text/javascript" src="./logs_files/jquery.jqplot.js"></script><script type="text/javascript" src="./logs_files/jqplot.core.js"></script><script type="text/javascript" src="./logs_files/jqplot.linearTickGenerator.js"></script><script type="text/javascript" src="./logs_files/jqplot.linearAxisRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.axisTickRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.axisLabelRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.tableLegendRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.lineRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.markerRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.divTitleRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.canvasGridRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.shadowRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.shapeRenderer.js"></script><script type="text/javascript" src="./logs_files/jqplot.sprintf.js"></script><script type="text/javascript" src="./logs_files/jsdate.js"></script><script type="text/javascript" src="./logs_files/jqplot.themeEngine.js"></script>
		<script type="text/javascript" src="./logs_files/jqplot.categoryAxisRenderer.min.js"></script>
		<script type="text/javascript" src="./logs_files/jqplot.barRenderer.js"></script>
		<script type="text/javascript" src="./logs_files/jquery.datePicker.js"></script>
		<script type="text/javascript" src="./logs_files/date.f.js"></script>
		<style type="text/css">
			@import "scripts/datePicker.css";
		</style>
		<script>
		var log_idle = [];
		var log_active = [];

		function urlencode(str)
		{
			return escape(str).replace('+', '%2B').replace('%20', '+').replace('*', '%2A').replace('/', '%2F').replace('@', '%40');
		}

		$(document).ready(function()
		{
			$.ajaxSetup({cache:false,});
			$.jqplot.config.enablePlugins = true;
			$("#datepicker").datePicker({ startDate:'01/01/1970',firstDayOfWeek:1, dateFormat: 'yyyy-mm-dd', selectWeek:true, inline:true,}).bind('dateSelected',
			function(e, selectedDate, $td, status)
			{
				var day = selectedDate.getFullYear() + "/" + (selectedDate.getMonth() + 1) + "/" + selectedDate.getDate();
				log_idle = [];
				log_active = [];
				renderedDays = 0;
				var login = document.getElementById("login").value;
				if (login.length == 0 || login == null)
					return;
				var page;
				var active = 0;
				var idle = 0;
				for (var i = 0; i < 7; i++)
				{
					page = "http://life/logs_by_date.php?";
					page = page.concat("login=", login, "&date=", day, "&activity=1");
					page = urlencode(page);
					page = "get.php?url=" + page;
					log_idle[i] = $.ajax({url: page, async : false}).responseText;
					page = "http://life/logs_by_date.php?";
					page = page.concat("login=", login, "&date=", day, "&activity=0");
					page = urlencode(page);
					page = "get.php?url=" + page;
					log_active[i] = $.ajax({url: page, async : false}).responseText;
					selectedDate.setDate(selectedDate.getDate() + 1);
					day = selectedDate.getFullYear() + "/" + (selectedDate.getMonth() + 1) + "/" + selectedDate.getDate();
				}
				create_chart();
			}
			);
		});

		function create_chart()
		{
			plot = $.jqplot('log_chart', [log_idle, log_active], {
			    legend:{show:true, location:'ne'},title:'Bar Chart',
			    series:[
			        {label:'Active in orange', renderer:$.jqplot.BarRenderer},
			        {
					label:'Idle in blue',
					renderer:$.jqplot.BarRenderer
				}
			    ],
			    axes:{
			        xaxis:{
					renderer:$.jqplot.CategoryAxisRenderer
				},
			        yaxis:{min:0, max:24,  tickOptions:{formatString:'%dh'}}
			    }
			});
		}
		</script>
	</head>
	<body>
		<h2>Activity Log</h2>
		<div type="text" id="form">
			<h3>Login:</h3>
			<!--<input type="text" id="login" name="Login" ></input>-->
			<select id="login" name="Login">
				<option>nguyen_m</option>
				<option>arquem_l</option>
				<option>benny</option>
				<option>boulli_s</option>
				<option>ceneda_m</option>
				<option>eschen_a</option>
				<option>gourla_a</option>
				<option>hallio_b</option>
				<option>kri5</option>
				<option>lorimi_a</option>
				<option>marcha_c</option>
				<option>martin_p</option>
				<option>monmir_c</option>
				<option>puzena_p</option>
				<option>schmid_c</option>
				<option>strazi_f</option>
				<option>trebea_j</option>
			</select>
			<div type="text" id="datepicker"></div>
		</div>
		<div id="log">
			<div id="log_chart"></div>
		</div>
	</body>
</html>
