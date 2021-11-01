$(function() {

  /* Habilita / Desabilita Campos com Base na Escolha */
  $('#tipo_registro').on('change', function(){

    var tipo_registro =  parseInt($('#tipo_registro').val());

    switch (tipo_registro) {

      case 1:
        alterar_tipo_registro(tipo_registro);
      break;

      case 2:
        alterar_tipo_registro(tipo_registro);
      break;

      case 3:
        alterar_tipo_registro(tipo_registro);
        $("#declarante").val("4");
      break;
    
    }
    
  });

  /* Habilita / Desabilita Campo Declarante Externo com Base na Escolha */
  $('#declarante').on('change', function(){
    
    var declarante =  parseInt($('#declarante').val());

    switch (declarante) {

      case 1:
        alterar_declarante(1);
      break;

      case 2:
        alterar_declarante(1);
      break;

      case 3:
        alterar_declarante(1);
      break;

      case 4:
        alterar_declarante(2);
      break;
      
    }
  });

  /* Habilita / Desabilita Campos de Avós com Base na Escolha */
  $('#avos_registrados').on('change', function(){

    var avos_registrados =  parseInt($('#avos_registrados').val());

    switch (avos_registrados) {

      case 0:
        alterar_avos(0);
      break;

      case 1:
        alterar_avos(1);
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

  clear_form();

  $.ajax({
    url: '/registro/create',
    type: 'get',
    dataType: 'JSON',
    beforeSend: function () {
      
      $('#div_carregamento').show();
      
    },       
    success: function(response){

      $('#div_carregamento').hide();

      $('#cidade').attr('disabled', true);

      $('#avos_registrados').empty();
      $('#avos_registrados').append('<option value="0" selected="selected">Não</option>');
      $('#avos_registrados').append('<option value="1">Sim</option>');
      
      $('#tipo_registro').empty();
      $('#tipo_registro').append('<option value="" selected="selected"></option>');
      $.each(response.tipo_registro, function(index, obj) {
        $('#tipo_registro').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $('#tipo_local_registro').empty();
      $('#tipo_local_registro').append('<option value="" selected="selected"></option>');
      $.each(response.tipo_local_registro, function(index, obj) {
        $('#tipo_local_registro').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $('#declarante').empty();
      $('#declarante').append('<option value="" selected="selected"></option>');
      $.each(response.declarante, function(index, obj) {
        $('#declarante').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $('#nacionalidade_sobrenome').empty();
      $('#nacionalidade_sobrenome').append('<option value="" selected="selected"></option>');
      $.each(response.nacionalidade_sobrenome, function(index, obj) {
        $('#nacionalidade_sobrenome').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $('#estado_civil').empty();
      $('#estado_civil').append('<option value="" selected="selected"></option>');
      $.each(response.estado_civil, function(index, obj) {
        $('#estado_civil').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      });

      $("#div_lista_arquivos").hide();
    
      $("#card_arquivos").empty();

      $('#button_modal').attr('onclick','save_registro();');
      $('#titulo_modal').html(response['titulo']);
    
    }

  })

}

// Carrga registro nos campos do formulário
function show_registro(id_registro) {

  clear_form();

  $.ajax({
    url: '/registro/show/'+id_registro,
    type: 'get',
    dataType: 'JSON',
    beforeSend: function () {
      
      $('#div_carregamento').show();
      
    },       
    success: function(response){

      $('#div_carregamento').hide();      

      $('#id').val(response.registro[0].id);
      $('#livro').val(response.registro[0].livro);
      $('#folha').val(response.registro[0].folha);
      $('#termo').val(response.registro[0].termo);
      $('#local_registro').val(response.registro[0].local_registro);
      $('#data_registro').val(response.registro[0].data_registro);
      $('#data_fato').val(response.registro[0].data_fato);
      $('#id_uf').val(response.registro[0].id_uf);
      $('#uf').val(response.registro[0].ds_uf);
      $('#id_cidade').val(response.registro[0].fk_cidade);
      $('#cidade').val(response.registro[0].ds_cidade);
      $('#id_religiao').val(response.registro[0].fk_religiao);
      $('#religiao').val(response.registro[0].ds_religiao);
      $('#nome').val(response.registro[0].nome);
      $('#sobrenome').val(response.registro[0].sobrenome);
      $('#nome_conjuge').val(response.registro[0].nome_conjuge);
      $('#sobrenome_conjuge').val(response.registro[0].sobrenome_conjuge);
      $('#cidade').attr('disabled', true);

      // Verifica o tipo de registro e faz o input de informações somente do necessário
      switch (response.registro[0].fk_tipo_registro) {

        case 1:
          alterar_tipo_registro(response.registro[0].fk_tipo_registro);
        break;
  
        case 2:
          alterar_tipo_registro(response.registro[0].fk_tipo_registro);
        break;
  
        case 3:
          alterar_tipo_registro(response.registro[0].fk_tipo_registro);
        break;
      
      }

      if (parseInt(response.registro[0].declarante_terceiro) === 4) {

        $('#div_declarante_terceiro').show();
        $('#declarante_terceiro').val(response.registro[0].declarante_terceiro);
        $('#declarante_terceiro').attr("required", "req");

      } else {

        $('#div_declarante_terceiro').hide();
        $('#declarante_terceiro').removeAttr('required');

      }
    
      $('#nome_pai').val(response.registro[0].nome_pai);
      $('#sobrenome_pai').val(response.registro[0].sobrenome_pai);
      $('#nome_mae').val(response.registro[0].nome_mae);
      $('#sobrenome_mae').val(response.registro[0].sobrenome_mae);

      $('#avos_registrados').empty();
      $('#avos_registrados').append('<option value="0"' + (parseInt(response.registro[0].avos_registrados) === 0 ? 'selected="selected"' : '') + '>Não</option>');
      $('#avos_registrados').append('<option value="1"' + (parseInt(response.registro[0].avos_registrados) === 1 ? 'selected="selected"' : '') + '>Sim</option>');
     
      switch (parseInt(response.registro[0].avos_registrados)) {

        case 0:
          alterar_avos(0);
        break;
  
        case 1:
          alterar_avos(1);
        break;
  
      }

      if (response.arquivos.length == 0) {

        $("#div_lista_arquivos").hide();
    
      } else {
    
        $("#div_lista_arquivos").show();
        $("#card_arquivos").empty();
        $("#lista_arquivos").attr('class', "collapse show");
    
        $.each(response.arquivos, function(index, arquivo) {
          $("#card_arquivos").append('<p id=arquivo_' + arquivo.id + '><a href="/registro/arquivo/' + arquivo.id + '" target="_blank">' + arquivo.nome_arquivo + '</a> <a href="#" onclick="delete_arquivo(' + arquivo.id + ')"> <i style="color: #dc3545;" class="fa fa-trash-alt fa-lg"></i></a></p>');  
        });
    
      }

      
      $("#upload_arquivos").attr('class', "collapse");

      $("#barra_progresso").hide();
      $("#barra_progresso").css("width", "100%");
      $("#barra_progresso").attr('aria-valuenow', "100");
      $('#label_barra_progresso').html("Carregamento concluído e Registro Salvo");

      $('#tipo_registro').empty();
      $.each(response.tipo_registro, function(index, obj) {
        $('#tipo_registro').append('<option value="'+ obj.id +'" '+ ( obj.id === response.registro[0].fk_tipo_registro ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#tipo_local_registro').empty();
      $.each(response.tipo_local_registro, function(index, obj) {
        $('#tipo_local_registro').append('<option value="'+ obj.id +'" '+ ( obj.id === response.registro[0].fk_tipo_local_registro ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#nacionalidade_sobrenome').empty();
      $.each(response.nacionalidade_sobrenome, function(index, obj) {
        $('#nacionalidade_sobrenome').append('<option value="'+ obj.id +'" '+ ( obj.id === response.registro[0].fk_nacionalidade_sobrenome ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#estado_civil').empty();
      $.each(response.estado_civil, function(index, obj) {
        $('#estado_civil').append('<option value="'+ obj.id +'" '+ ( obj.id === response.registro[0].fk_estado_civil ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })
      
      $('#declarante').empty();
      $.each(response.declarante, function(index, obj) {
        $('#declarante').append('<option value="'+ obj.id +'" '+ ( obj.id === response.registro[0].fk_declarante ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      }) 

      $('#button_modal').attr('onclick','change_registro();');
      $('#titulo_modal').html(response.titulo);
    
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
  });

  if (count > 0) {

    alert('Todos os campos devem ser preenchidos antes de salvar o registro!');
    return false;

  } else {

    var _token = $('meta[name="_token"]').attr('content');
    const formData = new FormData(formulario_registro);

    $.ajax({
        headers: { 'X-CSRF-TOKEN': _token },
        url: '/registro',
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

  // Mostra a barra de progresso
  $('#div_barra_progresso').show();
  // Mostra o progresso
  $('#label_barra_progresso').html("Carregando dados " + (event.loaded / 1000000).toFixed(3) + " MB de " + (event.total / 1000000).toFixed(3) + " MB");

  var percent = (event.loaded / event.total) * 100;
  var progress = Math.round(percent);

  // Altera o progresso
  $("#barra_progresso").html(progress + "%");
  $("#barra_progresso").css("width", progress + "%");
  $("#barra_progresso").attr('aria-valuenow', progress);
}

function loadHandler(event) {

  var retorno = JSON.parse(event.currentTarget.status);

  if (retorno == 403) {

  alert("Você não possui permissão para essa ação!");
  
  } else {

    var retorno = JSON.parse(event.currentTarget.response);

    if (retorno.arquivos.length == 0) {

      $("#id_registro").val(retorno.id_registro);
      $("#div_lista_arquivos").hide();

    } else {

      $("#id_registro").val(retorno.id_registro);
      $("#div_lista_arquivos").show();
      $("#card_arquivos").empty();

      $.each(retorno.arquivos, function(index, arquivo) {
        $("#card_arquivos").append('<p id=arquivo_' + arquivo.id + '><a href="/registro/arquivo/' + arquivo.id + '" target="_blank">' + arquivo.nome_arquivo + '</a> <a href="#" onclick="delete_arquivo(' + arquivo.id + ')"> <i style="color: #dc3545;" class="fa fa-trash-alt fa-lg"></i></a></p>');
      });

    }

    $("#upload_arquivos").attr('class', "collapse");

    $("#barra_progresso").html("100%");
    $("#barra_progresso").css("width", "100%");
    $("#barra_progresso").attr('aria-valuenow', "100");
    $('#label_barra_progresso').html("Carregamento concluído");

    $('#div_barra_progresso').hide();

    alert("Registro atualizado com sucesso!");

    }

}

function errorHandler(event) {
  $("#label_barra_progresso").html("Carregamento Falhou");
}

function abortHandler(event) {
  $("#label_barra_progresso").html("Carregamnto Abortado");
}


function change_registro() {

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

    const formData = new FormData(formulario_registro);

    $.ajax({
        headers: { 'X-CSRF-TOKEN': _token },
        url: '/registro/update',
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

function delete_registro(id_registro) {

  var resposta = confirm("Deseja remover esse registro?");

  if (resposta == true) {

    $.ajax({
      url: '/registro/delete/'+id_registro,
      type: 'get',
      dataType: 'JSON',
      beforeSend: function () {
        
        $('#div_carregamento').show();
        
      },       
      success: function(response){

        $('#div_carregamento').hide();

        if (response === 1) {

          $("#registro_" + id_registro).closest('tr').remove();

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


function delete_arquivo(id_arquivo) {

  var resposta = confirm("Deseja remover esse arquivo?");

  if (resposta == true) {

    $.ajax({
      url: '/registro/delete_arquivo/'+id_arquivo,
      type: 'get',
      dataType: 'JSON',
      data: { id_registro: $("#id").val() },
      beforeSend: function () {
        
        $('#div_carregamento').show();
        
      },       
      success: function(response){

        $('#div_carregamento').hide();

        if (response === 1) {

          $("#arquivo_" + id_arquivo).remove();

          alert("Arquivo excluído com sucesso!");
      
        } else {

          alert("Registro não excluído! Houve um erro durante a exclusão.");

        }

      }

    })

  }

}

function clear_form()
{
    $("#formulario_registro").each (function(){
      this.reset();
    });
}

// Função para alterar a visualização dos campos, com base na opção de Tipo de Registro escolhida
function alterar_tipo_registro(id){

  var campos = obter_campos();

  campos.tipo_registro[id].div_hide.forEach(function(input){
    $(input).hide();
  });

  campos.tipo_registro[id].not_required.forEach(function(input){
    $(input).removeAttr('required');
  });

  campos.tipo_registro[id].div_show.forEach(function(input){
    $(input).show();
  });

  campos.tipo_registro[id].required.forEach(function(input){
    $(input).attr("required", "req");
  });

}

// Função para alterar a visualização do campo declarante terceiro, com base na opção de declarante escolhida
function alterar_declarante(id){

  if (id == 1) {
    $('#div_declarante_terceiro').hide();
    $('#declarante_terceiro').removeAttr('required');
  }

  if (id == 2) {
    $('#div_declarante_terceiro').show();
    $('#declarante_terceiro').attr("required", "req");
  }

}


function alterar_avos(id){
  var campos = obter_campos();

  if (id == 0) {
    campos.avos[0].div_hide.forEach(function(input){
      $(input).hide();
    });
  }

  if (id == 1) {
    campos.avos[1].div_show.forEach(function(input){
      $(input).show();
    });
  }

}

// Função que retorna o nome dos campos que são configurados para determinada ação
function obter_campos(){

  var campos = { 

    tipo_registro: {
      // Opção Nascimento
      1: { 
        div_hide      : [ '#div_estado_civil', '#div_nome_conjuge' , '#div_sobrenome_conjuge'],
        not_required  : [ '#estado_civil', '#nome_conjuge' , '#sobrenome_conjuge' ],
        div_show      : [ '#div_declarante', '#div_religiao', '#div_avos_registrados' ],
        required      : [ '#declarante', '#id_religiao' , '#religiao', '#avos_registrados' ],
      },
      // Opção Casamento
      2: {
        div_hide      : [ '#div_declarante', '#div_declarante_terceiro' ],
        not_required  : [ '#declarante', '#declarante_terceiro' ],
        div_show      : [ '#div_estado_civil', '#div_nome_conjuge', '#div_sobrenome_conjuge', '#div_religiao' ],
        required      : [ '#estado_civil', '#nome_conjuge', '#sobrenome_conjuge', '#id_religiao', '#religiao' ],
      },
      // Opção Óbito
      3: {
        div_hide      : [ '#div_avos_registrados' ],
        not_required  : [ '#avos_registrados' ],
        div_show      : [ '#div_estado_civil', '#div_nome_conjuge', '#div_sobrenome_conjuge', '#div_religiao', '#div_declarante' , '#div_declarante_terceiro' ],
        required      : [ '#estado_civil', '#nome_conjuge', '#sobrenome_conjuge', '#id_religiao', '#religiao' , '#declarante', '#declarante_terceiro'],
      },  
    },

    avos: {
      // Opção não possui avós registrados
      0: {
        div_hide      : [ '#div_avos' ],
        not_required  : [ '#nome_avo_materno', '#sobrenome_avo_materno', '#nome_avo_materna', '#sobrenome_avo_materna', '#nome_avo_paterno', '#sobrenome_avo_paterno', '#nome_avo_paterna', '#sobrenome_avo_paterna' ],
      },
      // Opção possui avós registrados
      1: {
        div_show      : [ '#div_avos' ],
        required  : [ '#nome_avo_materno', '#sobrenome_avo_materno', '#nome_avo_materna', '#sobrenome_avo_materna', '#nome_avo_paterno', '#sobrenome_avo_paterno', '#nome_avo_paterna', '#sobrenome_avo_paterna' ],
      }

    }

  };

  return campos;

}


function teste_botao() {

console.log($('#div_alert'));
$('#div_alert').show();

}