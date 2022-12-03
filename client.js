var  Ivent =
{
	params : {},
	load : function()
	{
        $.ajax(
		{
            url : 'server.php',
            data : 
			{
                action : "getEvent"
            },
            type : "GET",
            dataType : "json",
            success : function(data)
			{
                Ivent.params = data;
				AppendToScreen(Ivent);
            },
            error : function(){}
        });
    },
	addComment: function(num,com)
	{
		$.ajax(
		{
            url : 'server.php',
            data : 
			{
                action : "addComment",
				file: Ivent.params[num]['comments'],
				comm: com
            },
            type : "GET",
            dataType : "text",
            success : function(data){},
            error : function(){}
        });
	},
	updateIvent: function()
	{
		$.ajax(
		{
            url : 'server.php',
            data : 
			{
                action : "update"
            },
            type : "GET",
            dataType : "json",
            success : function(data)
			{
                Ivent.params = data;
				reDraw(Ivent);
				//var p = $('<p>').text("update");
				//$(p).appendTo('body');
            },
            error : function(){}
        });
	}
};

function reDraw(Ivent)
{
	data = fsort(Ivent.params);
	var hr = $("[id='time']");
	var d = $("[id='descript']");
	var cm = $("[id='comment']");
	for (var i = 0; i<data['num']; i++)
	{	
		hr[i].innerHTML=data[i]['time'];
		d[i].innerHTML=data[i]['description'];
		cm[i].innerHTML=data[i]['comment_time']+' '+data[i]['comment'];
	}
}

function fsort(data)
{
	fl = 0;
	while (fl==0)
	{
		fl = 1;
		for (var i =0; i<data['num']-1; i++)
		{
			if (data[i]['comment_time']<data[i+1]['comment_time'])
			{
				buf = data[i];
				data[i] = data[i+1];
				data[i+1] = buf;
				fl = 0;
			}
		}
	}
	return data;
}

function AppendToScreen(Ivent)
{
	data = fsort(Ivent.params);
	for (var i = 0; i<data['num']; i++)
	{
		var panel = $('<div>').attr({'id':'panel', 'name':i}).css({'top':i*315+10 +'px'});
		$(panel).appendTo($('body'));
		var hr = $('<h4>').attr({'id':'time'}).text(data[i]['time']);
		$(hr).appendTo($(panel));
		var ta = $('<textarea>').attr({'id':'comment'+i});
		$(ta).appendTo($(panel));
		var d = $('<p>').attr({'id':'descript'}).text(data[i]['description']);
		$(d).appendTo($(panel));
		var b = $('<input>').attr({'type':'submit', 'name':i, 'id':'button_comment', 'value':'Отправить'});
		$(b).appendTo($(panel));
		var cm = $('<p>').attr({'id':'comment'}).text(data[i]['comment_time']+' '+data[i]['comment']);
		$(cm).appendTo($(panel));
	}
}



      
$(document).ready(function()
{  
    Ivent.updateIvent();  
    setInterval('Ivent.updateIvent()',1000);  
});  

$(function()
{
	Ivent.load();
	
	$("body").on('click','#button_comment',function(event)
	{	
		var buttn = $( event.target );
		var num = buttn.attr('name');
		var str = '#comment'+num;
		var comm = $(str);
		Ivent.addComment(num,comm.val());
		Ivent.updateIvent();
		comm.val('');
	});
});