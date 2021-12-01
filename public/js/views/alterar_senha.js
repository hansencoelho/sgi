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
    // document.getElementById("button_modal").disabled = true;
    $('#button_modal').prop('disabled', true);
	}else if((forca >= 30) && (forca < 50)){
		document.getElementById("div_forca_senha").innerHTML = "Força: " + "<span style='color: #fd7e14'>Média</span>";    
    $('#senha').attr("required", "req");
    $('#button_modal').prop('disabled', true);
    // document.getElementById("button_modal").disabled = true;
	}else if((forca >= 50) && (forca < 70)){
		document.getElementById("div_forca_senha").innerHTML = "Força: " + "<span style='color: #198754'>Forte</span>";
    $('#senha').attr("required", "req");
    $('#button_modal').prop('disabled', false);
	}else if((forca >= 70) && (forca < 100)){
		document.getElementById("div_forca_senha").innerHTML = "Força: " + "<span style='color: #0d6efd'>Excelente</span>";
    $('#senha').attr("required", "req");
    $('#button_modal').prop('disabled', false);
	}
}