$(function() {

  /* Tornar Senha Visível com Click */
  $("#senha_visivel").on('click', function(event) {
    event.preventDefault();
    if ($('#senha').attr("type") == "text"){

        $('#senha').attr('type', 'password');
        $('#senha_visivel i').removeClass( "fa-eye-slash" );
        $('#senha_visivel i').addClass( "fa-eye" );

    } else if($('#senha').attr("type") == "password"){

        $('#senha').attr('type', 'text');
        $('#senha_visivel i').addClass( "fa-eye-slash" );
        $('#senha_visivel i').removeClass( "fa-eye" );

    }
  });


  /* Autocomplete UF */
  $("#funcao").autocomplete({ 

    source: function( request, response) {
      $.ajax({
        url: "/usuario/autocomplete_funcao",
        dataType: "json",
        data: {
          term: $("#funcao").val(),
        },
        success: function( data ) {
          response( data );
        }
      });
    },

    minLength: 1,
    select: function(event, uf) {

      $('#funcao').val(uf.item.value);
      $('#id_funcao').val(uf.item.id);

    }

  });

});

function validar_forca_senha(){
	var senha = document.getElementById('senha').value;
	var forca = 0;

  $('#div_forca_senha').show();

  if ($("#senha").val() != '') {

    $('#senha').attr("required", "req");
    $('#div_enviar_senha').show();

    if((senha.length >= 12) && (senha.length <= 15)){
      forca += 10;
    }else if(senha.length > 7){
      forca += 25;
    }

    if((senha.length >= 5) && (senha.match(/[a-z]+/))){
      forca += 10;
    }

    if((senha.length >= 6) && (senha.match(/[A-Z]+/))){
      forca += 20;
    }

    if((senha.length >= 7) && (senha.match(/[@#$%&;*]/))){
      forca += 25;
    }

    if(senha.match(/([1-9]+)\1{1,}/)){
      forca += -25;
    }

    mostrar_forca(forca);

  } else {

    $('#div_forca_senha').hide();
    $('#senha').val('');
    $('#senha').removeAttr('required');
    $('#div_enviar_senha').hide();

  }

  if ($("#id").val() == '') {

    $('#senha').attr("required", "req");

  } else {

    $('#senha').removeAttr('required');

  }

}

function mostrar_forca(forca){

	if(forca < 30 ){
		document.getElementById("div_forca_senha").innerHTML = "Força: " + "<span style='color: #dc3545'>Fraca</span>";
    $('#senha').attr("required", "req");
	}else if((forca >= 30) && (forca < 50)){
		document.getElementById("div_forca_senha").innerHTML = "Força: " + "<span style='color: #fd7e14'>Média</span>";    
    $('#senha').attr("required", "req");
	}else if((forca >= 50) && (forca < 70)){
		document.getElementById("div_forca_senha").innerHTML = "Força: " + "<span style='color: #198754'>Forte</span>";
    $('#senha').attr("required", "req");
	}else if((forca >= 70) && (forca < 100)){
		document.getElementById("div_forca_senha").innerHTML = "Força: " + "<span style='color: #0d6efd'>Excelente</span>";
    $('#senha').attr("required", "req");
	}
}

//Apresenta um formulário em branco para novo registro, e lista os Dados Auxiliares do Registro nos Campos do Formulário
function create_usuario() {

  clear_form();

  $('#button_modal').attr('onclick','save_usuario();');
  $('#titulo_modal').html('Criar Usuário');
  $('#senha').attr('type', 'password');
  $('#div_forca_senha').hide();
  $('#senha').attr("required", "req");


}


// Carrega usuario nos campos do formulário
function show_usuario(id_usuario) {

  clear_form();

  $.ajax({
    url: '/usuario/show/'+id_usuario,
    type: 'get',
    dataType: 'JSON',
    beforeSend: function () {
      
      $('#div_carregamento').show();
      
    },       
    success: function(response){

      $('#senha').removeAttr('required');

      $('#div_carregamento').hide();      

      $('#id').val(response.usuario[0].id);
      $('#nome').val(response.usuario[0].nome);
      $('#email').val(response.usuario[0].email);
      $('#usuario').val(response.usuario[0].usuario);
      $('#funcao').val(response.usuario[0].funcao);
      $('#id_funcao').val(response.usuario[0].id_funcao);
      $('#senha').val('');
      $('#senha').attr('type', 'password');
      $('#div_forca_senha').hide();
      $('#div_enviar_senha').hide();
      $('#senha').removeAttr('required'); 

      $('#button_modal').attr('onclick','change_usuario();');
      $('#titulo_modal').html(response.titulo);
    
    }

  });

}

function save_usuario() {

  var count = 0
  var forms = document.querySelectorAll("[required]");

  $.each(forms, function(index, form) {
    if (form.value === '') {
      count = count + 1;
    }
  });

  if (count > 0) {

    alert('Todos os campos devem ser preenchidos antes de salvar o registro!');
    return false;

  } else {

    var _token = $('meta[name="_token"]').attr('content');
    const formData = new FormData(formulario_usuario);

    $.ajax({
        headers: { 'X-CSRF-TOKEN': _token },
        url: '/usuario',
        method: 'POST',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        // contentType: 'multipart/form-data',
        processData: false,
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress",
                uploadProgressHandler,
                false
            );
            // Funções de saída do XHR
            xhr.addEventListener("load", loadHandler, false);
            xhr.addEventListener("error", errorHandler, false);
            xhr.addEventListener("abort", abortHandler, false);

            return xhr;
        }
    });    

  }

}

function uploadProgressHandler(event) {

  $('#div_carregamento').show();

}

function loadHandler(event) {

  $('#div_carregamento').hide();
  
  var retorno = JSON.parse(event.currentTarget.status);

  if (retorno == 403) {

  alert("Você não possui permissão para essa ação!");
  
  } else {

    var retorno = JSON.parse(event.currentTarget.response);

    $("#titulo_modal").html("Alterar Usuário");

    alert(retorno.resposta_mensagem);



    }

}

function errorHandler(event) {
  // $("#label_barra_progresso").html("Carregamento Falhou");
}

function abortHandler(event) {
  // $("#label_barra_progresso").html("Carregamnto Abortado");
}


function change_usuario() {

  var count = 0
  var forms = document.querySelectorAll("[required]");

  $.each(forms, function(index, form) {
    if (form.value === '') {
      count = count + 1;
    }
  })

  if (count > 0) {

    alert('Todos os campos devem ser preenchidos antes de salvar o registro!');

    return false;

  } else {

    var _token = $('meta[name="_token"]').attr('content');

    const formData = new FormData(formulario_usuario);

    $.ajax({
        headers: { 'X-CSRF-TOKEN': _token },
        url: '/usuario/update',
        method: 'POST',
        type: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        // contentType: 'multipart/form-data',
        processData: false,
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress",
                uploadProgressHandler,
                false
            );
            xhr.addEventListener("load", loadHandler, false);
            xhr.addEventListener("error", errorHandler, false);
            xhr.addEventListener("abort", abortHandler, false);

            return xhr;
        }
    });    

  }

}

function delete_usuario(id_usuario) {

  var resposta = confirm("Deseja remover esse usuário?");
  var _token = $('meta[name="_token"]').attr('content');

  if (resposta == true) {

    $.ajax({
      headers: { 'X-CSRF-TOKEN': _token },
      url: '/usuario/delete',
      method: 'POST',
      type: 'POST',
      data: {

        id_usuario: id_usuario,

      },
      dataType: 'JSON',
      beforeSend: function () {
        
        $('#div_carregamento').show();
        
      },       
      success: function(response){

        $('#div_carregamento').hide();

        if (response === 1) {

          $("#usuario_" + id_usuario).closest('tr').remove();

          var contador = $('#contador_resultado').html();
          var contador = contador.split("").filter(n => (Number(n) || n == 0)).join("");
          var contador = (parseInt(contador) - 1);

          $('#contador_resultado').html("Resultado(s): " + contador);

          alert("Registro excluído com sucesso!");
      
        } else {

          alert("Registro não excluído! Houve um erro durante a exclusão.");

        }

      }

    })

  }

}


function clear_form()
{
    $("#formulario_usuario").each (function(){
      this.reset();
    });
}