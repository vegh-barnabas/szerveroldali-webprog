const fs = require("fs");
const { promisify } = require("util");

const pReadDir = promisify(fs.readdir);
const pReadFile = promisify(fs.readFile);
const pWriteFile = promisify(fs.writeFile);

pReadDir("./task3")
  .then((files) => {
    const readPromises = files.map((file) =>
      pReadFile(`./task3/${file}`, "utf-8")
    );

    return Promise.all(readPromises);
  })
  .then((contents) => {
    console.log(contents);

    return contents.join("\n");
  })
  .then((content) => pWriteFile("./result.txt", content))
  .then(() => console.log("end"))
  .catch((err) => {
    throw err;
  });
