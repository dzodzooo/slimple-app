import { catSetup, catSendPutRequest } from "./category.js";
import { transactionSetup, transactionSendPutRequest } from "./transaction.js";
import { closeModalWindow } from "./modalWindow.js";
import { getNewCode } from "./user.js";

catSetup();
transactionSetup();
document.catSendPutRequest = catSendPutRequest;
document.transactionSendPutRequest = transactionSendPutRequest;
document.closeModalWindow = closeModalWindow;
document.getNewCode = getNewCode;
