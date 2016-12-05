	$(document).ready(function() {

		$.post('lib/handle.php?action=folders', function(data) {
    var $cont = $('select#folder');
				$(Object.keys(data)).each(function(i,channel){
					//console.log(channel);
					$('<optGroup/>').attr('label', channel).text(channel).appendTo($cont);
								$(data[channel]).each(function(i,periods){
										$('<option/>').attr('value', periods).text(periods.split('/').slice(-1)[0]).appendTo($cont);
								})
				})
		}, 'json');




	    $("button#saveForm").click(function(e) {
	        e.preventDefault();
	        var add = {};
	        $("#form_container").find("input, select").each(function(i) {
	            add[$(this).attr("name")] = $(this).val();
	        });
					if(add['name']&& add['folder']){
						console.dir(add);
		        $.post('lib/handle.php?action=add', {
		            add
		        }, function(data) {
		            console.log(data);
		        }, 'json');
				}
	    })



	    $("button#delete").click(function(e) {
	        e.preventDefault();
	        var removes = [];
	        $("input.delbox").each(function(o,i) {
	            if ($(i)[0].checked) {
	                removes.push($(i)[0].value);
	            }
	        });
	        $.post('lib/handle.php?action=remove', {
	            "del": removes
	        }, function(data) {
						$(data).each(function(o,i){
							if(i!="undefined"){
									$("#list tr#"+i+".config").fadeOut();
							}
						})
	        }, 'json');
	    })



	    $("#list tr.config td:not(.del)").click(function() {
	        $("#songs table").empty();

	            console.log($(this).parent().attr("id"));
	        $.post('lib/handle.php?action=show', {
	            id: $(this).parent().attr("id")
	        }, function(data) {
	            if (data.songs != 'undefined') {
	                var st = '<tr>';
	                $.each(data.columns, function(i, o) {
	                    st += '<th>' + o + '</th>';
	                });
	                st += '</tr>';

	                $.each(data.songs, function(i, o) {
	                    st += '<tr>';
	                    $.each(o, function(ii, oo) {
	                        st += '<td>' + oo + '</td>';
	                    });
	                    st += '</tr>';
	                });

	                $("#songs table").append(st);
	            }
	        }, 'json');
	    })
















	})
