$(document).ready(function() {
    $("#produto").select2({
        placeholder: "Pesquisar produto",
        allowClear: true,
        minimumInputLength: 1 // Altere para 2 se desejar que os resultados apareçam após digitar duas letras
    });
  
});
function close() {
  var link = document.getElementById("close");
  link.style.display = "none";
}