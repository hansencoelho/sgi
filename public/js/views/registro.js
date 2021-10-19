$(function() {

  /* Habilita / Desabilita Campos com Base na Escolha */
  $('#tipo_registro').on('change', function(){

    var tipo_registro =  parseInt($('#tipo_registro').val());  

    switch (tipo_registro) {

      case 1:
        $('#div_estado_civil').hide();
        $('#div_religiao').hide();
        $('#div_nome_conjuge').hide();
        $('#div_sobrenome_conjuge').hide();
        
        $('#estado_civil').removeAttr('required');
        $('#religiao').removeAttr('required');
        $('#nome_conjuge').removeAttr('required');
        $('#sobrenome_conjuge').removeAttr('required');

        $('#div_declarante').show();
        $('#div_religiao').show();

        $('#declarante').attr("required", "req");
        $('#religiao').attr("required", "req");
        $('#id_religiao').attr("required", "req");
      break;

      case 2:
        $('#div_estado_civil').show();
        $('#div_nome_conjuge').show();
        $('#div_sobrenome_conjuge').show();
        $('#div_religiao').show();

        $('#estado_civil').attr("required", "req");
        $('#nome_conjuge').attr("required", "req");
        $('#sobrenome_conjuge').attr("required", "req");
        $('#id_religiao').attr("required", "req");
        $('#religiao').attr("required", "req");
        
        $('#div_declarante').hide();
        $('#div_declarante_terceiro').hide();

        $('#declarante').removeAttr('required');
        $('#declarante_terceiro').removeAttr('required');
      break;

      case 3:
        $('#div_estado_civil').show();
        $('#div_nome_conjuge').show();
        $('#div_sobrenome_conjuge').show();
        $('#div_religiao').show();
        $('#div_declarante').show();

        $('#estado_civil').attr("required", "req");
        $('#nome_conjuge').attr("required", "req");
        $('#sobrenome_conjuge').attr("required", "req");
        $('#id_religiao').attr("required", "req");
        $('#religiao').attr("required", "req");
        $('#declarante').attr("required", "req");
      break;
    
    }
    
  });

  /* Habilita / Desabilita Campo Declarante Externo com Base na Escolha */
  $('#declarante').on('change', function(){
    
    var declarante =  parseInt($('#declarante').val());

    console.log(declarante);

    switch (declarante) {

      case 1:
        $('#div_declarante_terceiro').hide();
        $('#declarante_terceiro').removeAttr('required');
      break;

      case 2:
        $('#div_declarante_terceiro').hide();
        $('#declarante_terceiro').removeAttr('required');
      break;

      case 3:
        $('#div_declarante_terceiro').hide();
        $('#declarante_terceiro').removeAttr('required');
      break;

      case 4:
        $('#div_declarante_terceiro').show();
        $('#declarante_terceiro').attr("required", "req");
      break;
      
    }
  });

  /* Habilita / Desabilita Campos de Avós com Base na Escolha */
  $('#avos_registrados').on('change', function(){

    var avos_registrados =  parseInt($('#avos_registrados').val());

    switch (avos_registrados) {

      case 0:
        $('#div_nome_avo_materno').hide();
        $('#div_sobrenome_avo_materno').hide();
        $('#div_nome_avo_materna').hide();
        $('#div_sobrenome_avo_materna').hide();
        $('#div_nome_avo_paterno').hide();
        $('#div_sobrenome_avo_paterno').hide();
        $('#div_nome_avo_paterna').hide();
        $('#div_sobrenome_avo_paterna').hide();

        $('#div_nome_avo_materno').removeAttr('required');
        $('#div_sobrenome_avo_materno').removeAttr('required');
        $('#div_nome_avo_materna').removeAttr('required');
        $('#div_sobrenome_avo_materna').removeAttr('required');
        $('#div_nome_avo_paterno').removeAttr('required');
        $('#div_sobrenome_avo_paterno').removeAttr('required');
        $('#div_nome_avo_paterna').removeAttr('required');
        $('#div_sobrenome_avo_paterna').removeAttr('required');
      break;

      case 1:
        $('#div_nome_avo_materno').show();
        $('#div_sobrenome_avo_materno').show();
        $('#div_nome_avo_materna').show();
        $('#div_sobrenome_avo_materna').show();
        $('#div_nome_avo_paterno').show();
        $('#div_sobrenome_avo_paterno').show();
        $('#div_nome_avo_paterna').show();
        $('#div_sobrenome_avo_paterna').show();

        $('#div_nome_avo_materno').attr("required", "req");
        $('#div_sobrenome_avo_materno').attr("required", "req");
        $('#div_nome_avo_materna').attr("required", "req");
        $('#div_sobrenome_avo_materna').attr("required", "req");
        $('#div_nome_avo_paterno').attr("required", "req");
        $('#div_sobrenome_avo_paterno').attr("required", "req");
        $('#div_nome_avo_paterna').attr("required", "req");
        $('#div_sobrenome_avo_paterna').attr("required", "req");
      break;

    }

  });

  /* Autocomplete UF */
  $("#uf").autocomplete({ 

    source: function( request, response) {
      $.ajax({
        url: "/registro/autocomplete_uf",
        dataType: "json",
        data: {
          term: $("#uf").val(),
        },
        success: function( data ) {
          response( data );
        }
      });
    },

    minLength: 1,
    select: function(event, uf) {

      $('#uf').val(uf.item.value);
      $('#id_uf').val(uf.item.id);

      $("#cidade").val('');
      $('#cidade').attr('disabled', false);

    }

  });

  /* Autocomplete Cidade */
  $("#cidade").autocomplete({ 

    source: function( request, response) {
      $.ajax({
        url: "/registro/autocomplete_cidade",
        dataType: "json",
        data: {
          term: $("#cidade").val(),
          id_uf : $("#id_uf").val(),
        },
        success: function( data ) {
          response( data );
        }
      });
    },

    minLength: 1,
    select: function(event, cidade) {

      $('#cidade').val(cidade.item.value);
      $('#id_cidade').val(cidade.item.id);
  
    }

  });

  /* Limpa Cidade caso UF esteja vazia */
  $('#uf').on('change', function(){

    if ( $('#uf').val() === "") {

      $('#id_uf').val('');
      $('#cidade').val('');
      $('#cidade').attr('disabled', true);

    }

  });

  /* Autocomplete Religiao */
  $("#religiao").autocomplete({ 

    source: function( request, response) {
      $.ajax({
        url: "/registro/autocomplete_religiao",
        dataType: "json",
        data: {
          term: $("#religiao").val(),
        },
        success: function( data ) {
          response( data );
        }
      });
    },

    minLength: 1,
    select: function(event, religiao) {

      $('#religiao').val(religiao.item.value);
      $('#id_religiao').val(religiao.item.id);

    }

  });

});

// Apresenta um formulário em branco para novo registro, e lista os Dados Auxiliares do Registro nos Campos do Formulário
function create_registro() {

  clearforms($("#formulario"));

  $.ajax({
    url: '/registro/create/',
    type: 'get',
    dataType: 'JSON',
    beforeSend: function () {
      
      $('#div_carregamento').show();
      
    },       
    success: function(response){

      $('#div_carregamento').hide();

      var titulo = response['titulo'];
      var tipo_registro = response['tipo_registro'];
      var tipo_local_registro = response['tipo_local_registro'];
      var declarante = response['declarante'];
      var nacionalidade_sobrenome = response['nacionalidade_sobrenome'];
      var estado_civil = response['estado_civil'];

      $('#cidade').attr('disabled', true);

      $('#avos_registrados').empty();
      $('#avos_registrados').append('<option value="0" selected="selected">Não</option>');
      $('#avos_registrados').append('<option value="1">Sim</option>');
      
      $('#tipo_registro').empty();
      $('#tipo_registro').append('<option value="" selected="selected"></option>');
      $.each(tipo_registro, function(index, obj) {
        $('#tipo_registro').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $('#tipo_local_registro').empty();
      $('#tipo_local_registro').append('<option value="" selected="selected"></option>');
      $.each(tipo_local_registro, function(index, obj) {
        $('#tipo_local_registro').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $('#declarante').empty();
      $('#declarante').append('<option value="" selected="selected"></option>');
      $.each(declarante, function(index, obj) {
        $('#declarante').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $('#nacionalidade_sobrenome').empty();
      $('#nacionalidade_sobrenome').append('<option value="" selected="selected"></option>');
      $.each(nacionalidade_sobrenome, function(index, obj) {
        $('#nacionalidade_sobrenome').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $('#estado_civil').empty();
      $('#estado_civil').append('<option value="" selected="selected"></option>');
      $.each(estado_civil, function(index, obj) {
        $('#estado_civil').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $("#div_lista_arquivos").hide();
    
      $("#card_arquivos").empty();

      $('#button_modal').attr('onclick','save_registro();');
      $('#titulo_modal').html(titulo);
    
    }

  })

}

// Carrga registro nos campos do formulário
function show_registro(id_registro) {

  clearforms($("#formulario"));

  $.ajax({
    url: '/registro/show/'+id_registro,
    type: 'get',
    dataType: 'JSON',
    beforeSend: function () {
      
      $('#div_carregamento').show();
      
    },       
    success: function(response){

      $('#div_carregamento').hide();

      var titulo = response['titulo'];
      var registro = response['registro'][0];
      var tipo_registro = response['tipo_registro'];
      var tipo_local_registro = response['tipo_local_registro'];
      var declarante = response['declarante'];
      var nacionalidade_sobrenome = response['nacionalidade_sobrenome'];
      var estado_civil = response['estado_civil'];
      var arquivos = response['arquivos'];

      console.log(arquivos);

      $('#id').val(registro.id);
      $('#livro').val(registro.livro);
      $('#folha').val(registro.folha);
      $('#termo').val(registro.termo);
      $('#local_registro').val(registro.local_registro);
      $('#data_registro').val(registro.data_registro);
      $('#data_fato').val(registro.data_fato);
      $('#id_uf').val(registro.id_uf);
      $('#uf').val(registro.ds_uf);
      $('#id_cidade').val(registro.id_cidade);
      $('#cidade').val(registro.ds_cidade);
      $('#id_religiao').val(registro.id_religiao);
      $('#religiao').val(registro.ds_religiao);
      $('#nome').val(registro.nome);
      $('#sobrenome').val(registro.sobrenome);

      $('#cidade').attr('disabled', true);

      // Verifica o tipo de registro e faz o input de informações somente do necessário
      switch (registro.fk_tipo_registro) {
        case 1:
          $('#div_estado_civil').hide();
          $('#div_religiao').hide();
          $('#div_nome_conjuge').hide();
          $('#div_sobrenome_conjuge').hide();
          
          $('#div_estado_civil').removeAttr('required');
          $('#div_religiao').removeAttr('required');
          $('#div_nome_conjuge').removeAttr('required');
          $('#div_sobrenome_conjuge').removeAttr('required');
  
          $('#div_declarante').show();
          $('#div_religiao').show();
  
          $('#declarante').attr("required", "req");
          $('#religiao').attr("required", "req");          
        break;
  
        case 2:
          $('#div_estado_civil').show();
          $('#div_nome_conjuge').show();
          $('#div_sobrenome_conjuge').show();
          $('#div_religiao').show();

          $('#estado_civil').attr("required", "req");
          $('#nome_conjuge').attr("required", "req");
          $('#sobrenome_conjuge').attr("required", "req");
          $('#religiao').attr("required", "req");
          
          $('#div_declarante').hide();
          $('#div_declarante_terceiro').hide();

          $('#declarante').removeAttr('required');
          $('#declarante_terceiro').removeAttr('required');

          $('#nome_conjuge').val(registro.nome_conjuge);
          $('#sobrenome_conjuge').val(registro.sobrenome_conjuge);
        break;
  
        case 3:
          $('#div_estado_civil').show();
          $('#div_nome_conjuge').show();
          $('#div_sobrenome_conjuge').show();
          $('#div_religiao').show();
          $('#div_declarante').show();
          $('#div_religiao').show();
  
          $('#estado_civil').attr("required", "req");
          $('#nome_conjuge').attr("required", "req");
          $('#sobrenome_conjuge').attr("required", "req");
          $('#id_religiao').attr("required", "req");
          $('#religiao').attr("required", "req");

          $('#nome_conjuge').val(registro.nome_conjuge);
          $('#sobrenome_conjuge').val(registro.sobrenome_conjuge);
        break;
      
      }

      // console.log(registro.declarante_terceiro);

      if (parseInt(registro.declarante_terceiro) === 4) {

        $('#div_declarante_terceiro').show();
        $('#declarante_terceiro').val(registro.declarante_terceiro);
        $('#declarante_terceiro').attr("required", "req");

      } else {

        $('#div_declarante_terceiro').hide();
        $('#declarante_terceiro').removeAttr('required');

      }
    
      $('#nome_pai').val(registro.nome_pai);
      $('#sobrenome_pai').val(registro.sobrenome_pai);
      $('#nome_mae').val(registro.nome_mae);
      $('#sobrenome_mae').val(registro.sobrenome_mae);

      $('#avos_registrados').empty();
      $('#avos_registrados').append('<option value="0"' + (parseInt(registro.avos_registrados) === 0 ? 'selected="selected"' : '') + '>Não</option>');
      $('#avos_registrados').append('<option value="1"' + (parseInt(registro.avos_registrados) === 1 ? 'selected="selected"' : '') + '>Sim</option>');
     
      switch (parseInt(registro.avos_registrados)) {

        case 0:
          $('#div_nome_avo_materno').hide();
          $('#div_sobrenome_avo_materno').hide();
          $('#div_nome_avo_materna').hide();
          $('#div_sobrenome_avo_materna').hide();
          $('#div_nome_avo_paterno').hide();
          $('#div_sobrenome_avo_paterno').hide();
          $('#div_nome_avo_paterna').hide();
          $('#div_sobrenome_avo_paterna').hide();
  
          $('#nome_avo_materno').removeAttr('required');
          $('#sobrenome_avo_materno').removeAttr('required');
          $('#nome_avo_materna').removeAttr('required');
          $('#sobrenome_avo_materna').removeAttr('required');
          $('#nome_avo_paterno').removeAttr('required');
          $('#sobrenome_avo_paterno').removeAttr('required');
          $('#nome_avo_paterna').removeAttr('required');
          $('#sobrenome_avo_paterna').removeAttr('required');
        break;
  
        case 1:
          $('#div_nome_avo_materno').show();
          $('#div_sobrenome_avo_materno').show();
          $('#div_nome_avo_materna').show();
          $('#div_sobrenome_avo_materna').show();
          $('#div_nome_avo_paterno').show();
          $('#div_sobrenome_avo_paterno').show();
          $('#div_nome_avo_paterna').show();
          $('#div_sobrenome_avo_paterna').show();
  
          $('#nome_avo_materno').attr("required", "req");
          $('#sobrenome_avo_materno').attr("required", "req");
          $('#nome_avo_materna').attr("required", "req");
          $('#sobrenome_avo_materna').attr("required", "req");
          $('#nome_avo_paterno').attr("required", "req");
          $('#sobrenome_avo_paterno').attr("required", "req");
          $('#nome_avo_paterna').attr("required", "req");
          $('#sobrenome_avo_paterna').attr("required", "req");
        break;
  
      }

      if ( arquivos === 0) {

        // $("#id_registro").val(retorno.id_registro);
        $("#div_lista_arquivos").hide();
    
      } else {
    
        // $("#id_registro").val(retorno.id_registro);
    
        $("#div_lista_arquivos").show();
    
        $("#card_arquivos").empty();
    
        $.each(arquivos, function(index, arquivo) {
    
          $("#card_arquivos").append('<p><a href="/registro/arquivo/' + arquivo.id + '" target="_blank">' + arquivo.nome_arquivo + '</a></p>');
        
        });
    
      }

      $("#lista_arquivos").attr('class', "collapse show");
    
      $("#upload_arquivos").attr('class', "collapse");
    
      $("#barra_progresso").css("width", "100%");
      $("#barra_progresso").attr('aria-valuenow', "100");
      $('#label_barra_progresso').html("Carregamento concluído e Registro Salvo");

      $('#tipo_registro').empty();
      $.each(tipo_registro, function(index, obj) {
        $('#tipo_registro').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_tipo_registro ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#tipo_local_registro').empty();
      $.each(tipo_local_registro, function(index, obj) {
        $('#tipo_local_registro').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_tipo_local_registro ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#nacionalidade_sobrenome').empty();
      $.each(nacionalidade_sobrenome, function(index, obj) {
        $('#nacionalidade_sobrenome').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_nacionalidade_sobrenome ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#estado_civil').empty();
      $.each(estado_civil, function(index, obj) {
        $('#estado_civil').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_estado_civil ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })
      
      $('#declarante').empty();
      $.each(declarante, function(index, obj) {
        $('#declarante').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_declarante ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      }) 

      $('#button_modal').attr('onclick','change_registro();');
      $('#titulo_modal').html(titulo);
    
    }

  });

}

function save_registro() {

  var count = 0
  var forms = document.querySelectorAll("[required]");

  $.each(forms, function(index, form) {

    if (form.value === '') {

      count = count + 1;
    
    }

  })

  console.log(count);

  if (count > 0) {

    alert('Todos os campos devem ser preenchidos antes de salvar o registro!');

    return false;

  } else {

    var _token = $('meta[name="_token"]').attr('content');

    const formData = new FormData(formulario);

    $.ajax({
        headers: { 'X-CSRF-TOKEN': _token },
        url: '/registro/',
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

function clearforms($form)
{
    $(':input', ':hidden', ':select').not(':button, :submit, :reset, :checkbox, :radio').val('');
    $(':checkbox, :radio').prop('checked', false);
}

function uploadProgressHandler(event) {

  $('#div_barra_progresso').show();
  $('#label_barra_progresso').html("Carregando arquivos " + (event.loaded / 1000000).toFixed(3) + " MB de " + (event.total / 1000000).toFixed(3));

  // $("#label_barra_progresso").html("Carregando arquivos " + event.loaded + " bytes de " + event.total);
  var percent = (event.loaded / event.total) * 100;
  var progress = Math.round(percent);

  $("#barra_progresso").html(progress + "%");
  $("#barra_progresso").css("width", progress + "%");
  $("#barra_progresso").attr('aria-valuenow', progress);
  // $("#label_barra_progresso").html(progress + "% uploaded... please wait");
}

function loadHandler(event) {
  
  console.log(JSON.parse(event.currentTarget.response));

  var retorno = JSON.parse(event.currentTarget.response);

  if (retorno.arquivos === 0) {

    $("#id_registro").val(retorno.id_registro);
    $("#div_lista_arquivos").hide();

  } else {

    $("#id_registro").val(retorno.id_registro);

    $("#div_lista_arquivos").show();

    $("#card_arquivos").empty();

    $.each(retorno.arquivos, function(index, arquivo) {

      $("#card_arquivos").append('<p><a href="/registro/arquivo/' + arquivo.id + '" target="_blank">' + arquivo.nome_arquivo + '</a></p>');
    
    });

  }

  $("#upload_arquivos").attr('class', "collapse");

  $("#barra_progresso").css("width", "100%");
  $("#barra_progresso").attr('aria-valuenow', "100");
  $('#label_barra_progresso').html("Carregamento concluído e Registro Salvo");

}

function errorHandler(event) {
  $("#label_barra_progresso").html("Carregamento Falhou");
}

function abortHandler(event) {
  $("#label_barra_progresso").html("Carregamnto Abortado");
}

 // var len = response.length;
      // for(var i=0; i<len; i++){

          // var id = response[i].id;
          // var username = response[i].username;
          // var name = response[i].name;
          // var email = response[i].email;

          // var tr_str = "<tr>" +
          //     "<td align='center'>" + (i+1) + "</td>" +
          //     "<td align='center'>" + username + "</td>" +
          //     "<td align='center'>" + name + "</td>" +
          //     "<td align='center'>" + email + "</td>" +
          //     "</tr>";

          // $("#userTable tbody").append(tr_str);
      // }

