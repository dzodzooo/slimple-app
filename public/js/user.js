export function getNewCode() {
  fetch("/getNewVerificationCode")
    .then((response) => {
      console.log(response);
    })
    .catch((error) => console.log(error));
}
