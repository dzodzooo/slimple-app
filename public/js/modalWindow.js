export function openModalWindow() {
  let ModalBg = document.getElementById("ModalBg");
  ModalBg.classList.remove("none");
  ModalBg.classList.add("modalBg");
  let Modal = document.getElementById("Modal");
  Modal.removeAttribute("hidden");
  Modal.classList.add("modal");
}

export function closeModalWindow() {
  let Modal = document.getElementById("Modal");
  Modal.classList.remove("modal");
  Modal.setAttribute("hidden", "");
  let ModalBg = document.getElementById("ModalBg");
  ModalBg.classList.remove("modalBg");
  ModalBg.classList.add("none");
}
