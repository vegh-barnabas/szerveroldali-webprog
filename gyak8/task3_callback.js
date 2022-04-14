const fs = require("fs");

fs.readdir("./task3", (err, files) => {
  if (err) throw err;

  console.log(files);

  let contents = [];
  files.forEach((file) => {
    // console.log(file);

    fs.readFile(`./task3/${file}`, "utf-8", (err, data) => {
      if (err) throw err;

      // console.log(data);

      contents.push(data);

      if (contents.length === files.length) {
        // console.log(contents);

        fs.writeFile("./result.txt", contents.join("\n"), (err) => {
          if (err) throw err;

          console.log("end");
        });
      }
    });
  });
});
