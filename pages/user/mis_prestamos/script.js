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

  function updateDateTime() {
    const now = new Date();
    const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    const dateTimeString = now.toLocaleDateString('es-ES', options).replace(",", " -");
    document.querySelector('.real-time').textContent = dateTimeString;
}

updateDateTime();
setInterval(updateDateTime, 1000);

const urlParams = new URLSearchParams(window.location.search);
const eliminacionExitosaExitosa = urlParams.get("eliminacionExitosa");

if (eliminacionExitosaExitosa === "1") {
  alert(
    "Solicitud para devolver el libro exitosa, pase a la biblioteca a entregar el libro"
  );
}
