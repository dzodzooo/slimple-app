function deleteCategory(category_id) {
  fetch("http://host.docker.internal:3000/categories/" + category_id, {
    method: "DELETE",
  })
    .then(alert("delete"))
    .catch((reason) => {
      alert(reason);
    });
}
