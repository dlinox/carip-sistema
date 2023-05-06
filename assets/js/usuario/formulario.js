$(document).ready(function () {

	$('#change_pass').change(function () {
		if ($(this).prop('checked')) {
			$('input[name="pass"]').removeAttr("disabled");
		} else {
			$('input[name="pass"]').attr("disabled", "disabled");
		}
	})

})


var loadFile = function (event) {
	var output = document.getElementById('output');
	output.src = URL.createObjectURL(event.target.files[0]);
	output.onload = function () {
		URL.revokeObjectURL(output.src) // free memory
	}
};


function guardarUsuario() {

	let alerta = $('#alerta');

	alerta.addClass('esconder');

	let usua_id = $('#usua_id').val();
	var data = $('#fomrUsuario').serializeArray();
	let formData = new FormData();
	$.each(data, function (i, field) {
		formData.append(field.name, field.value);
	});

	if (usua_id != '') {

		console.log($('#user').val());
		formData.append('user', $('#user').val());
	}

	var files = $('#foto')[0].files[0];
	formData.append('foto', files);

	$.ajax({
		url: baseurl + 'usuario/guardar/' + usua_id,
		type: "post",
		data: formData,
		dataType: "json",
		processData: false,
		contentType: false,
		success: function (response) {

			if (response.exito) {
				console.log('Se tiene que cerra el Modal y recargar la tabla');

				//$('#mitabla').ajax.reload();
				//$('#fomrUsuario').trigger("reset");
				var table = $('#mitabla').DataTable();

				if (!response.file) {
					console.log(response.file_mensaje.error);
					alerta.removeClass('esconder');

					alerta.html(response.file_mensaje.error);
				}
				else{
					table.ajax.reload();
					$('.modal').modal('hide')
				}

			} else {
				alerta.removeClass('esconder');

				alerta.html(response.mensaje);
			}

			console.log(response);
		}
	});

}