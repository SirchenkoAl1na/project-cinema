
let modal = null;

function openModal(id) {
  modal = document.getElementById(id);
  modal.style.display = "block";
}

function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}


