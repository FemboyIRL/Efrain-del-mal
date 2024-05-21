function toggleCardBody() {
  var cardBody = document.getElementById("cardBody");
  cardBody.classList.toggle("d-none");
}

function toggleCardBody2() {
  var cardBody = document.getElementById("cardBody2");
  cardBody.classList.toggle("d-none");
}
var myModal = document.getElementById("staticBackdrop");
myModal.addEventListener("show.bs.modal", function (event) {
  var button = event.relatedTarget;
  var libro = button.getAttribute("data-libro");
  var modalNombreLibro = myModal.querySelector("#nombreLibro");
  modalNombreLibro.textContent = libro;
  var campoLibroSeleccionado = myModal.querySelector("#libroSeleccionado");
  campoLibroSeleccionado.value = libro;
});

const urlParams = new URLSearchParams(window.location.search);
const prestamoExitoso = urlParams.get("prestamoExitoso");

if (prestamoExitoso === "1") {
  alert(
    "Préstamo solicitado con éxito, pase a la biblioteca para recoger su libro."
  );
}


