export function getNewCode() {
  fetch("/getNewVerificationCode")
    .then((response) => {})
    .catch((error) => console.log(error));
}
