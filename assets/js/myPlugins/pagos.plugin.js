$('.num').keyup(function(){
	var total = 0;
	
	$('#total').val(total);
	console.log($('#monto').val());
	console.log($('#bono').val());
	total = parseFloat($('#monto').val()) + parseFloat($('#bono').val());
	$('#total').val(total);
	console.log(total);
	
});