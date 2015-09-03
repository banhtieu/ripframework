$(document).ready(function(){
	$(".text-field>input").focus(function(){
		$(this).parent(".text-field").addClass("focus");
	});
	
	$(".text-field>input").blur(function(){
		$(this).parent(".text-field").removeClass("focus");
	});
});