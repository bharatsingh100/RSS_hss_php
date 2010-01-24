function confirmSubmit(){
	return confirm("Are you sure you wish to continue?") ? true : false;
}

function MM_jumpMenuGo(objId,targ,restore){ //v9.0
  var selObj = null;  with (document) { 
  if (getElementById) selObj = getElementById(objId);
  if (selObj) eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0; }
}
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
function elistCheck() {
	var form = document.forms[0];
	//var chk = eval("form.members[]");
	var chk = ''
	var checked = false;
	for (var j = 0; j <= 7; j++) {
		var t = "checkbox" + j;
		chk = document.getElementById(t);
		if (chk.checked == true) checked = true;
   }
	if(form.address.value == '' || form.mod_pass.value == '' || !checked)
	{form.button.disabled=true;}
	else {form.button.disabled=false;}
}

/* Automatically Set Gana when entering date while adding a new contact */
function setGana() {
	var d = new Date();
	var currYear = d.getFullYear();

	var age =  parseInt(document.getElementById("birth_year").value);
	
	if(age >= currYear - 6) { //Shishu
		document.getElementById('gana').selectedIndex = 0; 
	} else if(age >= currYear - 12) { //Bala
		document.getElementById('gana').selectedIndex = 1;
	} else if(age >= currYear - 19) { //Kishor
		document.getElementById('gana').selectedIndex = 2;
	} else if(age >= currYear - 25) { //Yuva
		document.getElementById('gana').selectedIndex = 3;
	} else if(age >= currYear - 50) { //Tarun
		document.getElementById('gana').selectedIndex = 4;
	} else if(age < currYear - 50) { //Praudh
		document.getElementById('gana').selectedIndex = 5;
	}
}

/*Dynamically show updated sankhya when submitting sankhya */
function totalSankhya(){
	var a = parseInt(document.getElementById("shishu_m").value);
	var b = parseInt(document.getElementById("bala_m").value);
	var c = parseInt(document.getElementById("kishor_m").value);
	var d = parseInt(document.getElementById("yuva_m").value);
	var e = parseInt(document.getElementById("tarun_m").value);
	var f = parseInt(document.getElementById("praudh_m").value);
	var g = a + b + c + d + e + f;
	document.getElementById('subtt_m').innerHTML = g;
	
	var h = parseInt(document.getElementById("shishu_f").value);
	var i = parseInt(document.getElementById("bala_f").value);
	var j = parseInt(document.getElementById("kishor_f").value);
	var k = parseInt(document.getElementById("yuva_f").value);
	var l = parseInt(document.getElementById("tarun_f").value);
	var m = parseInt(document.getElementById("praudh_f").value);
	var n = h + i + j + k + l + m;
	document.getElementById('subtt_f').innerHTML = n;
	
	document.getElementById('total').innerHTML = n + g;
	
}

var mylib = 
{
	national :
	{
		statistics : function() 
		{
			var api = new jGCharts.Api(); 			
			$('<img>')
				.attr('src', api.make(opt)) 
				.appendTo("#shakhas_chart");
		}	
	},
	shakha :
	{
		sny_statistics: function()
		{
			$("#sny_statistics").tablesorter({sortList: [[1,1], [2,1]]});
		},
		sny_count : function()
		{
			var sny_form = $("#sny_count input[type=text]");
			sny_form.blur( 
				function() {
					var participants = 0;
					var par_male = 0;
					var par_female = 0;
					var counts = 0;
					var counts_ss = 0;
					var counts_s = 0;
					sny_form.each(function(i){
						if(i < 12){
							participants += parseInt($(this).val());
							if((i % 2) == 0) par_male += parseInt($(this).val());
							if((i % 2) == 1) par_female += parseInt($(this).val());
						} 
						else if(i > 12){
							counts += parseInt($(this).val());
							if((i % 2) == 1) counts_ss += parseInt($(this).val());
							if((i % 2) == 0) counts_s += parseInt($(this).val());
						}
					});
					//console.log(participants);
					$("#subtt_m").html(par_male);
					$("#subtt_f").html(par_female);
					$("#total").html(participants);
					$("#counts_ss").html(counts_ss);
					$("#counts_s").html(counts_s);
					$("#counts").html(counts);
					//console.log(counts);
			});
		}
	}
}