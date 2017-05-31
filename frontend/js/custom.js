$(document).ready(function(){

	var sitename = location.protocol + '//' + location.hostname;

	$("#jsMsgBlock").hide();

	$('#shortenform').submit(function(e) {
		
		e.preventDefault();
		
		var inputUrl = $('#inputUrl').val();
		var radioTime = $('input[name=optionsRadios]:checked').val();

		$.ajax({
			async: false,
			url : sitename + "/backend/ajax/short.php",
			type : 'POST',
			data : {inputUrl: inputUrl, radioTime: radioTime},
			success : function(data)
			{
				var res = JSON.parse(data);

				if(res.type == 'success')
				{
					$('#inputUrl').val('');

					$("#jsMsg").text('Your URL was added successfully');
					$("#jsMsgBlock").addClass('alert-success').fadeIn().delay(3000).fadeOut();

					$('#tableLinks > tbody:last-child').append('<tr>'
						+ '<td class="al">'+res.id+'</td>'
						+ '<td class="al"><a href="'+inputUrl+'" target="_blank">'+inputUrl+'</a></td>'
						+ '<td class="ar">'+res.created+'</td>'
						+ '<td class="al"><a href="'+res.shorturl+'" data-link="'+res.id+'" target="_blank" id="shortLinkGo">'+res.shorturl+'</a></td>'
						+ '<td class="ar">'+res.ttl+'</td>'
						+ '<td class="ar" id="click'+res.id+'" data-click="0">0</td>'
						+ '</tr>'
					);
				}
                else if(res.type == 'error')
                {
                    $("#jsMsg").text(res.message);
					$("#jsMsgBlock").addClass('alert-danger').fadeIn().delay(3000).fadeOut();
                }
			},
			error: function()
			{
                $("#jsMsg").text('SYSTEM ERROR. TRY LATTER');
				$("#jsMsgBlock").addClass('alert-danger').fadeIn().delay(3000).fadeOut();
			}
		});
	});

    $('body').on('click', '#shortLinkGo', function(e){
        e.preventDefault();

		var linkId = $(this).data('link');

		curClicks = $('#click'+linkId).data('click');
		newClicks = curClicks + 1;

        $.ajax({
			async: true,
			url : sitename + "/backend/ajax/click.php",
            type : 'POST',
            data: {linkId: linkId},
            success : function(data){
            	var res = JSON.parse(data);
            	if(res.type == 'success')
            	{
            		$('#click'+linkId).text(newClicks);
            		$('#click'+linkId).attr('data-click', newClicks);
					
					var win = window.open(res.link, '_blank');
            		if(win){
					    win.focus();
					} else {
		                $("#jsMsg").text('Please allow popups for this website');
						$("#jsMsgBlock").addClass('alert-danger').fadeIn().delay(5000).fadeOut();
					}
            	}
            },
            error: function(){
                $("#jsMsg").text('SYSTEM ERROR. TRY LATTER');
				$("#jsMsgBlock").addClass('alert-danger').fadeIn().delay(3000).fadeOut();
            }
        });
    });

});
