$(function() {
  
  $("#senha").on("focusout", function () {
    if ($("#senha").val() != $("#confirmacao_senha").val()) {
      $("#confirmacao_senha").removeClass("is-valid").addClass("is-invalid");
    } else {
      $("#confirmacao_senha").removeClass("is-invalid").addClass("is-valid");
    }
  });
  
  $("#confirmacao_senha").on("keyup", function () {
    if ($("#senha").val() != $(this).val()) {
      $(this).removeClass("is-valid").addClass("is-invalid");
    } else {
      $(this).removeClass("is-invalid").addClass("is-valid");
    }
  });

});