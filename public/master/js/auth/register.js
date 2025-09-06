async function onSubmit(event) {
  event.preventDefault();
  
  $('#card-error').hide();
  const url = base_url(['create']);
  
  const data = {
    name: $('#name').val(),
    username: $('#username').val(),
    email: $('#email').val(),
    password: $('#password').val(),
    password_confirm: $('#confirm-password').val()
  }

  if(data.name == '' || data.username == '' || data.email == '' || data.password == '' || data.password_confirm == ''){
    $('#card-error h5').html('Campos obligatorios.');
    $('#card-error p').html('Por favor revisa que los campos no esten vacios.');
    return $('#card-error').show();
  }

  $('#btn-send').attr('disabled', true);
  $('#btn-send').html('Enviando... <i class="ri-restart-line ri-spin"></i>');

  await proceso_fetch(url, data).then(respond => {
    if(respond.status == 403){
      $('#card-error h5').html(respond.title);
      $('#card-error p').html(respond.message);
      $('#card-error').show();
      $('#btn-send').attr('disabled', false);
      $('#btn-send').html('Registrarme');
    }else{
      $('#card-success h5').html(respond.title);
      $('#card-success p').html(respond.message);
      $('#card-success').show();
      $('#btn-send').html('Registrarme');
      // setTimeout(() => {
      //   window.location.href = respond.url;
      // }, 3000)
    }
  })

}