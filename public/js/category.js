import { openModalWindow, closeModalWindow } from "./modalWindow.js";

export function catOpenModal(category) {
  markUpdateUnknown(category);
  openModalWindow();
  let nameinput = document.getElementById("nameCatPut");
  nameinput.value = category.name;
  let idinput = document.getElementById("idCatPut");
  idinput.value = category.id;
}

export function catSendPutRequest() {
  let category = {};
  let nameinput = document.getElementById("nameCatPut");
  category.name = nameinput.value;
  let idinput = document.getElementById("idCatPut");
  category.id = idinput.value;

  let csrf_name = document.getElementById("csrf_name");
  let csrf_value = document.getElementById("csrf_value");

  fetch(`/categories`, {
    method: "PUT",
    body: JSON.stringify({
      [csrf_name.name]: csrf_name.value,
      [csrf_value.name]: csrf_value.value,
      category,
    }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (response["status"] == 200) {
        markUpdateSuccessful(category);
      } else {
        markUpdateFailed(category);
      }
    })
    .catch((error) => console.log(error));
  closeModalWindow();
  catRenderUpdated(category);
}

function catRenderUpdated(category) {
  let name_td = document.querySelector(
    `#category_row${category.id} #category_name${category.id}`
  );
  name_td.innerHTML = category.name;
}

function markUpdateSuccessful(category) {
  let category_tr = document.getElementById(`category_row${category.id}`);
  category_tr.classList.add("update-successful");
}
function markUpdateFailed(category) {
  let category_tr = document.getElementById(`category_row${category.id}`);
  category_tr.classList.add("update-failed");
}
function markUpdateUnknown(category) {
  let category_tr = document.getElementById(`category_row${category.id}`);
  category_tr.classList.remove("update-failed");
  category_tr.classList.remove("update-successful");
}
