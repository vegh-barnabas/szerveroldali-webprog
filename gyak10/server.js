const express = require("express");
const app = express();

const models = require("./models");
const { Genre } = models;

app.use(function (req, res, next) {
  console.log("Saját middleware");

  req.a = 2;

  next();
});

app.use(express.json());

app.get("/genres", async function (req, res) {
  const genres = await Genre.findAll();

  res.send(genres);
});
app.post("/genres", async function (req, res) {
  console.log(req.a);

  const genre = await Genre.create(req.body);
  res.send(genre);
});

app.get("/genres/:id", async function (req, res) {
  // console.log(req.params);

  const { id } = req.params;
  if (isNaN(id)) return res.sendStatus(400);

  const genre = await Genre.findByPk(id);

  if (genre === null) {
    return res.sendStatus(404);
  }

  res.send(genre);
});

app.listen(3000, () => {
  console.log("Express started");
});
