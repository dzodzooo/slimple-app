import { openModalWindow, closeModalWindow } from "./modalWindow.js";

export function transactionSetup() {
  let elements = document.getElementsByClassName("transactionBtnOpenModal");
  let length = elements.length;
  for (let index = 0; index < length; index++) {
    const element = elements[index];
    element.addEventListener("click", transactionOpenModal);
  }
}

function transactionOpenModal(event) {
  let element = event.target;
  let transactionId = getTransactionId(element);
  let transaction = getTransactionData(transactionId);
  markUpdateUnknown(transaction);
  openModalWindow();
  setModalFields(transaction);
}

function getTransactionId(button) {
  let btnId = button.id;
  let transactionId = btnId.match(/[0-9]+/g)[0];
  return transactionId;
}

function getTransactionData(id) {
  let transaction = {};
  transaction.id = id;
  let description = document.getElementById(`transaction_description${id}`);
  transaction.description = sanitize(description.innerHTML);
  let amount = document.getElementById(`transaction_amount${id}`);
  let sanitized = sanitize(amount.innerHTML);
  transaction.amount = sanitized.match(/[0-9]+/g)[0];
  let date = document.getElementById(`transaction_date${id}`);
  let datesanitized = sanitize(date.innerHTML);
  let datearr = datesanitized.split(".");
  transaction.date = datearr[2] + "-" + datearr[1] + "-" + datearr[0];

  let category = document.getElementById(`transaction_category${id}`);
  transaction.category = category.dataset.id;
  console.log(transaction);
  return transaction;
}
function sanitize(string) {
  let sanitized = string.match(/[^\n +].+[^\n +]/g)[0];
  console.log(sanitized);
  return sanitized;
}

function setModalFields(transaction) {
  let idinput = document.getElementById("transactionPutId");
  idinput.value = transaction.id;
  let descriptioninput = document.getElementById("transactionPutDescription");
  descriptioninput.value = transaction.description;
  let amountinput = document.getElementById("transactionPutAmount");
  amountinput.value = transaction.amount;
  let dateinput = document.getElementById("transactionPutDate");
  dateinput.value = transaction.date;
  let categoryselect = document.getElementById("transactionPutCategory");
  categoryselect.value = transaction.category;
}

function getModalFields() {
  let transaction = {};
  let idinput = document.getElementById("transactionPutId");
  transaction.id = idinput.value;
  let descriptioninput = document.getElementById("transactionPutDescription");
  transaction.description = descriptioninput.value;
  let amountinput = document.getElementById("transactionPutAmount");
  transaction.amount = amountinput.value;
  let dateinput = document.getElementById("transactionPutDate");
  transaction.date = dateinput.value;
  let categoryselect = document.getElementById("transactionPutCategory");
  transaction.categoryId = categoryselect.value;
  return transaction;
}

export function transactionSendPutRequest() {
  let transaction = getModalFields();

  let csrf_name = document.getElementById("csrf_name");
  let csrf_value = document.getElementById("csrf_value");

  fetch(`/transactions`, {
    method: "PUT",
    body: JSON.stringify({
      [csrf_name.name]: csrf_name.value,
      [csrf_value.name]: csrf_value.value,
      transaction,
    }),
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (response.ok) {
        markUpdateSuccessful(transaction);
      } else {
        markUpdateFailed(transaction);
      }
    })
    .catch((error) => console.log(error));

  closeModalWindow();
  transactionRenderUpdated(transaction);
}

function transactionRenderUpdated(transaction) {
  let description_td = document.querySelector(
    `#transaction_description${transaction.id}`
  );
  description_td.innerHTML = transaction.description;

  let amount_td = document.querySelector(
    `#transaction_amount${transaction.id}`
  );
  amount_td.innerHTML = `${transaction.amount}$`;

  let date_td = document.querySelector(`#transaction_date${transaction.id}`);
  date_td.innerHTML = `${new Date(transaction.date).toLocaleDateString(
    "uk-Uk"
  )}.`;

  let category_td = document.querySelector(
    `#transaction_category${transaction.id}`
  );
  let category_value = document.getElementById(
    `transactionPutCategory${transaction.categoryId}`
  ).innerHTML;
  category_td.innerHTML = category_value;
}

function markUpdateSuccessful(transaction) {
  let transaction_tr = document.getElementById(
    `transaction_row${transaction.id}`
  );
  transaction_tr.classList.add("update-successful");
  setTimeout(markUpdateUnknown, 3000, transaction);
}
function markUpdateFailed(transaction) {
  let transaction_tr = document.getElementById(
    `transaction_row${transaction.id}`
  );
  transaction_tr.classList.add("update-failed");
  setTimeout(markUpdateUnknown, 3000, transaction);
}
function markUpdateUnknown(transaction) {
  let transaction_tr = document.getElementById(
    `transaction_row${transaction.id}`
  );
  transaction_tr.classList.remove("update-failed");
  transaction_tr.classList.remove("update-successful");
}
