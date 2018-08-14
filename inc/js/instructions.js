$(document).ready(function() {
	$('.instructions').hide();
	
	$( ".instructionsTitle" ).click(function() {
		instructions = $(this).parent().find('.instructions')
		
		if(instructions.css('display') =='none' ){
			instructions.show();
		}else{
			instructions.hide();
		}
	});
	
});

