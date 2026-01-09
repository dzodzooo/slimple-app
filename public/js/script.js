import { catOpenModal, catSendPutRequest } from "./category.js";
import {
  transactionOpenModal,
  transactionSendPutRequest,
} from "./transaction.js";
import { closeModalWindow } from "./modalWindow.js";

document.catOpenModal = catOpenModal;
document.catSendPutRequest = catSendPutRequest;
document.transactionOpenModal = transactionOpenModal;
document.transactionSendPutRequest = transactionSendPutRequest;
document.closeModalWindow = closeModalWindow;
