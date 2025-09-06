async function onSubmit(event) {
    event.preventDefault();
    
    $('#card-error').hide();
    $('#card-success').hide();
    const url = base_url(['forgot_password']);
    
    const data = {
      email: $('#email').val(),
    }
  
    if(data.email == ''){
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
        $('#btn-send').html('Recuperar Contraseña');
      }else{
        $('#card-success h5').html(respond.title);
        $('#card-success p').html(respond.message);
        $('#card-success').show();
        $('#btn-send').html('Recuperar Contraseña');
        setTimeout(() => {
          window.location.href = respond.url;
        }, 3000)
      }
    })
  
  }