function updateCategory(category) {
  let categoryModalBg = document.getElementById("categoryModalBg");
  categoryModalBg.classList.remove("none");
  categoryModalBg.classList.add("modalBg");
  let categoryModal = document.getElementById("categoryModal");
  categoryModal.removeAttribute("hidden");
  categoryModal.classList.add("modal");
  let nameinput = document.getElementById("nameCatPut");
  nameinput.value = category.name;
  let idinput = document.getElementById("idCatPut");
  idinput.value = category.id;
}

function closeModal() {
  let categoryModal = document.getElementById("categoryModal");
  categoryModal.classList.remove("modal");
  categoryModal.setAttribute("hidden", "");
  let categoryModalBg = document.getElementById("categoryModalBg");
  categoryModalBg.classList.remove("modalBg");
  categoryModalBg.classList.add("none");
}
