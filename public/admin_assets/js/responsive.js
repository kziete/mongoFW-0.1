$(document).ready(function(){
	$(".h100").each(function(){
		var alto = $(this).parent().height();
		$(this).height(alto);
		$(this).css('line-height',alto + 'px');
	});
});