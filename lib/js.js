	$(document).ready(function () {
		$("#list tr").click(function () {
			$("#songs table").empty();
			$.getJSON('songs.php', {id: $(this).attr("id")}, function (data) {
				if (data.songs != 'undefined') {
					var st = '<tr>';
					$.each(data.columns, function (i, o) {
						st += '<th>' + o + '</th>';
					});
					st += '</tr>';

					$.each(data.songs, function (i, o) {
						st += '<tr>';
						$.each(o, function (ii, oo) {
							st += '<td>' + oo + '</td>';
						});
						st += '</tr>';
					});

					$("#songs table").append(st);
				}
			});
		})
	})
