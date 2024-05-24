var myModal = document.getElementById("staticBackdrop");
myModal.addEventListener("show.bs.modal", function (event) {
  var button = event.relatedTarget;
  var multa = button.getAttribute("data-multa");
  var modalMultaPagar = myModal.querySelector("#multaPagar");
  modalMultaPagar.textContent = multa; 
  var campoMultaEnPago = myModal.querySelector("#multaEnPago");
  campoMultaEnPago.value = multa;
  var idSancion = button.getAttribute("data-idSancion");
  var modalIdSancion = myModal.querySelector("#idSancion");
  modalIdSancion.value = idSancion;
});
