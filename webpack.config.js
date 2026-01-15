const path = require("path");

module.exports = {
  mode: "production",
  entry: path.resolve(__dirname, "public/js/script.js"),
  output: {
    path: path.resolve(__dirname + "/public/js"),
    filename: "bundle.js",
  },
};
