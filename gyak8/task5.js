const fs = require("fs");
const { promisify } = require("util");
const jimp = require("jimp");
const path = require("path");
const date = require("date-and-time");
const sqlite = require("sqlite3").verbose();

const pReadDir = promisify(fs.readdir);

(async () => {
  const db = new sqlite.Database("task5.sqlite");
  db.run(`CREATE TABLE IF NOT EXISTS images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    original_name VARCHAR(255),
    new_name VARCHAR(255),
    processed_on DATETIME
  )`);

  const files = await pReadDir("./task5");

  console.log(files);

  for (const file of files) {
    let image = await jimp.read(`./task5/${file}`);
    const fileProps = path.parse(`/.task5/${file}`);

    // console.log(image);
    console.log(fileProps);

    image.resize(120, jimp.AUTO);

    const now = new Date();
    const nowFormatted = date.format(now, "YYYY_MM_DD_HH_mm_ss");

    const newName = `${fileProps.name}_${nowFormatted}${fileProps.ext}`;
    await image.write(`./task5_result/${newName}`);

    db.run(
      `INSERT INTO images (
      original_name,
      new_name,
      processed_on
    )
    VALUES (?, ?, ?)`,
      [file, newName, now]
    );
  }
})();
