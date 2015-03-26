$.fn.modal = function(){
	var th = $(this);
	
	$('button').on('click', function(e){
		e.preventDefault();
	
		
		$('body').append('<div class="lbox" ></div>');

		if($(this).attr("href") == ('#'+th.attr('id')) ){
			th.toggle();
			
			if($("#modal_window").is(":visible") == 0){
				$('.lbox').remove();
			}
		}
	});
};


$(document).ready(function(){
	$("#modal_window").modal();
});
