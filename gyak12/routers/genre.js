const express = require("express");
const router = express.Router();

const models = require("../models");
const { Genre } = models;

router.get("/", async (req, res) => {
  const genres = await Genre.findAll();

  res.send(genres); // ugyanaz, mint: res.status(200).send({ message: genres });
});
router.post("/", async function (req, res) {
  console.log(req.a);

  const genre = await Genre.create(req.body);
  res.send(genre);
});

router.get("/:id", async function (req, res) {
  // console.log(req.params);

  const { id } = req.params;
  if (isNaN(id)) return res.sendStatus(400);

  const genre = await Genre.findByPk(id);

  if (genre === null) {
    return res.sendStatus(404);
  }

  res.send(genre);
});

router.put("/:id", async function (req, res) {
  const { id } = req.params;
  if (isNaN(id)) return res.status(400).send({ message: "A megadott ID nem egy szám." });

  const genre = await Genre.findByPk(id);

  if (genre === null) {
    return res.status(404).send({ message: "A megadott ID-val nem létezik műfaj." });
  }

  await genre.update(req.body);

  res.send(genre);
});

router.delete("/:id", async function (req, res) {
  const { id } = req.params;
  if (isNaN(id)) return res.status(400).send({ message: "A megadott ID nem egy szám." });

  const genre = await Genre.findByPk(id);

  if (genre === null) {
    return res.status(404).send({ message: "A megadott ID-val nem létezik műfaj." });
  }

  await genre.destroy(req.body);

  res.status(200).send({ message: "Sikeresen törölted a műfajt!" });
});

module.exports = router;
