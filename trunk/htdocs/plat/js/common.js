function ajax_get(url, did){
    $.ajax({
        type: "GET",
        url: url,
        success: function(msg){
            $('#' + did).html(msg);
        }
    });
}

function ajax_post(url, formid, did){
    var queryString = $.trim($('#' + formid).formSerialize());
    $.ajax({
        type: "POST",
        url: url,
        data: queryString,
        success: function(msg){
			
			$('#' + did).html(msg);
			//$(document).height(document.body.scrollHeight);
			
        }
    });
}

function set_height()
{
	var hh = $(document).height();
	alert('Now is :'+hh);
	var hh2 = $(window).height();
	alert('Now 2 is :'+hh2);
	
	//document.getElementById("main").height=1000;
	document.height = document.body.scrollHeight;
	//$(document).height(800);
}