import { openModalWindow, closeModalWindow } from "./modalWindow.js";

export function transactionOpenModal(transaction) {
  markUpdateUnknown(transaction);
  openModalWindow();
  setModalFields(transaction);
}

function setModalFields(transaction) {
  let idinput = document.getElementById("transactionPutId");
  idinput.value = transaction.id;
  let descriptioninput = document.getElementById("transactionPutDescription");
  descriptioninput.value = transaction.description;
  let amountinput = document.getElementById("transactionPutAmount");
  amountinput.value = transaction.amount;
  let dateinput = document.getElementById("transactionPutDate");
  let date_valid_format = JSON.parse(transaction.date)["date"].split(" ")[0];
  dateinput.value = date_valid_format;
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
      if (response["status"] == 200) {
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
    `#transaction_row${transaction.id} #transaction_description${transaction.id}`
  );
  description_td.innerHTML = transaction.description;

  let amount_td = document.querySelector(
    `#transaction_row${transaction.id} #transaction_amount${transaction.id}`
  );
  amount_td.innerHTML = `${transaction.amount}$`;

  let date_td = document.querySelector(
    `#transaction_row${transaction.id} #transaction_date${transaction.id}`
  );
  date_td.innerHTML = `${new Date(transaction.date).toLocaleDateString(
    "uk-Uk"
  )}.`;

  let category_td = document.querySelector(
    `#transaction_row${transaction.id} #transaction_category${transaction.id}`
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
}
function markUpdateFailed(transaction) {
  let transaction_tr = document.getElementById(
    `transaction_row${transaction.id}`
  );
  transaction_tr.classList.add("update-failed");
}
function markUpdateUnknown(transaction) {
  let transaction_tr = document.getElementById(
    `transaction_row${transaction.id}`
  );
  transaction_tr.classList.remove("update-failed");
  transaction_tr.classList.remove("update-successful");
}
