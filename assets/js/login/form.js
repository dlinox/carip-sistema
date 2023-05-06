var iden = true; 
$(document).ready(function(){
  $('form').submit(function(e){
    e.preventDefault();
    $.ajax({
      dataType: 'JSON',
      url: $(this).attr('action'),
      type: $(this).attr('method'),
      data: $(this).serialize(),
      success: function(resp){
        $respuesta = $('.error');
        if(!resp.exito){
          $respuesta.find('p').html(resp.mensaje);
          $respuesta.show();
        }else{
          window.location.href = resp.direccion;
        }
      }
    })
  })
});