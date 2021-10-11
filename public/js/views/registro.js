$(function() {

  // console.log(api_username, api_password);

  /* Habilita / Desabilita Campos com Base na Escolha */
  $('#tipo_registro').on('change', function(){

    var tipo_registro =  $('#tipo_registro').val();  

    switch (tipo_registro) {
      case '1':
        $('#div_estado_civil').hide();
        $('#div_religiao').hide();
        $('#div_nome_conjuge').hide();
        $('#div_sobrenome_conjuge').hide();
        $('#div_declarante').show();
        $('#label_data_registro').html('Data de Nascimento');

      break;

      case '2':
        $('#div_estado_civil').show();
        $('#div_nome_conjuge').show();
        $('#div_sobrenome_conjuge').show();
        $('#div_declarante').hide();
        $('#div_declarante_terceiro').hide();
        $('#label_data_registro').html("Data de Casamento");
      break;

      case '3':
        $('#div_estado_civil').show();
        $('#div_nome_conjuge').show();
        $('#div_sobrenome_conjuge').show();
        $('#label_data_registro').html("Data de Óbito");
      break;
      
    }
  })

  $('#declarante').on('change', function(){

    var declarante =  $('#declarante').val();

    console.log(declarante);

    switch (declarante) {
      case '1':
        $('#div_declarante_terceiro').hide();
      break;

      case '2':
        $('#div_declarante_terceiro').hide();
      break;

      case '3':
        $('#div_declarante_terceiro').hide();
      break;

      case '4':
        $('#div_declarante_terceiro').show();
      break;
      
    }
  })

  /* Habilita / Desabilita Campos de Avós com Base na Escolha */

  $('#avos_registrados').on('change', function(){

    var avos_registrados =  $('#avos_registrados').val()

    console.log(avos_registrados);

    switch (avos_registrados) {
      case '0':
        $('#div_nome_avo_materno').hide();
        $('#div_sobrenome_avo_materno').hide();
        $('#div_nome_avo_materna').hide();
        $('#div_sobrenome_avo_materna').hide();
        $('#div_nome_avo_paterno').hide();
        $('#div_sobrenome_avo_paterno').hide();
        $('#div_nome_avo_paterna').hide();
        $('#div_sobrenome_avo_paterna').hide();
      break;

      case '1':
        $('#div_nome_avo_materno').show();
        $('#div_sobrenome_avo_materno').show();
        $('#div_nome_avo_materna').show();
        $('#div_sobrenome_avo_materna').show();
        $('#div_nome_avo_paterno').show();
        $('#div_sobrenome_avo_paterno').show();
        $('#div_nome_avo_paterna').show();
        $('#div_sobrenome_avo_paterna').show();
      break;

      default:
        console.log(`Sorry, we are out of ${expr}.`);
    }

  })

});

// Lista os Dados no Select Tipo Registro

function new_registro() {

  clearforms($("#formulario"));

  $('#avos_registrados').empty();
  $('#avos_registrados').append('<option value="0" selected="selected">Não</option>');
  $('#avos_registrados').append('<option value="1">Sim</option>');

  $.ajax({
    url: '/registro/list_tipo_registro',
    type: 'get',
    dataType: 'JSON',
    success: function(response){

      $('#tipo_registro').empty();
      $('#tipo_registro').append('<option value="" selected="selected">Selecionar</option>');

      response.forEach(function(obj){
      $('#tipo_registro').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      })   
    }
  })

  $.ajax({
    url: '/registro/list_tipo_local_registro',
    type: 'get',
    dataType: 'JSON',
    success: function(response){

      $('#tipo_local_registro').empty();
      $('#tipo_local_registro').append('<option value="" selected="selected"></option>');

      response.forEach(function(obj){
      $('#tipo_local_registro').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      })
    }
  })

  $.ajax({
    url: '/registro/list_declarante',
    type: 'get',
    dataType: 'JSON',
    success: function(response){

      $('#declarante').empty();
      $('#declarante').append('<option value="" selected="selected"></option>');

      response.forEach(function(obj){
      $('#declarante').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      })
    }
  })

  $.ajax({
    url: '/registro/list_nacionalidade_sobrenome',
    type: 'get',
    dataType: 'JSON',
    success: function(response){

      $('#nacionalidade_sobrenome').empty();
      $('#nacionalidade_sobrenome').append('<option value="" selected="selected"></option>');

      response.forEach(function(obj){
      $('#nacionalidade_sobrenome').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      })
    }
  })

  $.ajax({
    url: '/registro/list_estado_civil',
    type: 'get',
    dataType: 'JSON',
    success: function(response){

      //console.log(estado_civil);

      $('#estado_civil').empty();
      $('#estado_civil').append('<option value="" selected="selected"></option>');

      response.forEach(function(obj){
      $('#estado_civil').append('<option value="'+ obj.id +'">'+ obj.descricao +'</option>');
      })
    }
  })



}

// Lista os Dados dos Campos do Formularário

function show(id_registro) {

  clearforms($("#formulario"));

  $.ajax({
    url: '/registro/show/'+id_registro,
    type: 'get',
    dataType: 'JSON',
    success: function(response){

      var titulo = response['titulo'];
      var registro = response['registro'][0];
      var tipo_registro = response['tipo_registro'];
      var tipo_local_registro = response['tipo_local_registro'];
      var declarante = response['declarante'];
      var nacionalidade_sobrenome = response['nacionalidade_sobrenome'];
      var estado_civil = response['estado_civil'];
      
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
      $('#nome_pai').val(registro.nome_pai);
      $('#sobrenome_pai').val(registro.sobrenome_pai);
      $('#nome_mae').val(registro.nome_mae);
      $('#sobrenome_mae').val(registro.sobrenome_mae);
      $('#nome_mae').val(registro.nome_mae);
      $('#declarante_terceiro').val(registro.declarante_terceiro);

      $('#avos_registrados').empty();
      $('#avos_registrados').append('<option value="0"' + (registro.avos_registrados === 0 ? 'selected="selected"' : '') + '>Não</option>');
      $('#avos_registrados').append('<option value="1"' + (registro.avos_registrados === 1 ? 'selected="selected"' : '') + '>Sim</option>');
      
      $('#tipo_registro').empty();
      $.each(tipo_registro, function(index, obj) {
        $('#tipo_registro').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_tipo_registro ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#tipo_local_registro').empty();
      $.each(tipo_local_registro, function(index, obj) {
        $('#tipo_local_registro').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_tipo_local_registro ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#declarante').empty();
      $.each(declarante, function(index, obj) {
        $('#declarante').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_declarante ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#nacionalidade_sobrenome').empty();
      $.each(nacionalidade_sobrenome, function(index, obj) {
        $('#nacionalidade_sobrenome').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_nacionalidade_sobrenome ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      $('#estado_civil').empty();
      $.each(estado_civil, function(index, obj) {
        $('#estado_civil').append('<option value="'+ obj.id +'" '+ ( obj.id === registro.fk_estado_civil ? 'selected="selected"' : ' ') +'>'+ obj.descricao +'</option>');
      })

      document.getElementById("titulo_modal").innerHTML = titulo;
    
    }

  })

}

function save_registro() {

  var _token = $('meta[name="_token"]').attr('content');

  $.ajaxSetup({
  
      headers: {
          'X-CSRF-TOKEN': _token
      }
  
  });
  
 
  $.ajax({
      url: '/registro/',
      type: 'POST',
      data: {
          'user': 'oi'
          
      },
      dataType: 'JSON',

      success: function(data){
          console.log(data);
      }
  });
     

}

function clearforms($form)
{
    $(':input', ':hidden', ':select').not(':button, :submit, :reset, :checkbox, :radio').val('');
    $(':checkbox, :radio').prop('checked', false);
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