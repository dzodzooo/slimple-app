function openModal(category) {
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

function updateCategory() {
  console.log("trying to update category...");
  let category = {};
  let nameinput = document.getElementById("nameCatPut");
  category.name = nameinput.value;
  let idinput = document.getElementById("idCatPut");
  category.id = idinput.value;
  let csrf_id = document.getElementById("csrf_id");
  let csrf_value = document.getElementById("csrf_value");
  fetch(`/categories`, {
    method: "PUT",
    body: JSON.stringify({
      [csrf_id.name]: csrf_id.value,
      [csrf_value.name]: csrf_value.value,
      category,
    }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (response["status"] == 200) {
        markUpdateSuccessful();
      } else {
        markUpdateFailed();
      }
    })
    .catch((error) => console.log(error));
  closeModal();
  renderUpdatedCategory(category);
}
function getAllCategories() {
  fetch("/categories")
    .then((response) => {
      return response.text();
    })
    .then((response) => {
      document.body.innerHTML = response;
    })
    .catch((error) => {
      console.log(error);
    });
}

function renderUpdatedCategory(category) {
  name_td = document.querySelector(`#categoryrow${category.id} #category_name`);
  if (name_td) name_td.innerHTML = category.name;
}
