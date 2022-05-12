const express = require("express");
const router = express.Router();

const { sequelize } = require("../models");

const models = require("../models");
const { Movie, Rating, Genre } = models;

const auth = require("../middlewares/auth");
const { route } = require("./auth");

router.get("/", async function (req, res) {
  const movies = await Movie.findAll({
    attributes: {
      // meglévő attribútumok mellé felveszünk továbbiakat
      include: [
        // sequelize.fn - aggregációkat lehet vele csinálni (pl. átlagot - AVG) - dokumentációban bennevannak a lehetséges aggregációk
        // aliasing - avgRating legyen a column neve
        [sequelize.fn("AVG", sequelize.col("Ratings.rating")), "avgRating"],
      ],
    },
    include: [
      {
        model: Genre,
        attributes: {
          exclude: ["createdAt", "updatedAt"],
        },
        through: { attributes: [] },
      },
      {
        model: Rating,
        attributes: [], // ne jelenítsen meg semmilyen Ratinggel kapcsolatos adatot, csak az átlag értékelést
      },
    ],
    group: ["movie.id", "Genres.id"], // csoportosítás
    order: sequelize.literal("avgRating DESC"), // csökkenő sorrendben az avgRating szerint
  });

  res.send(movies);
});

router.post("/:id/rate", auth, async function (req, res) {
  const { id } = req.params;

  console.log(req.auth);

  if (isNaN(id)) {
    return res.status(400).send({ message: "A megadott ID nem szám!" });
  }

  const movie = await Movie.findByPk(id);
  if (movie === null) {
    return res.status(404).send({ message: "A megadott ID-vel nem létezik film!" });
  }

  if (!movie.ratingsEnabled) {
    return res.status(403).send({ message: "A megadott filmhez nincs engedélyezve értékelés!" });
  }

  // van-e a usernek ehhez a filmhez készített értékelése?
  let rating = await Rating.findOne({ where: { UserId: req.auth.id, MovieId: id } });
  if (rating) {
    // módosítjuk a meglévő értékelést
    await rating.update(req.body);
    return res.status(200).send({ message: "Sikeresen módosítottad a korábbi értékelésed!", rating });
  } else {
    // új értékelést hozunk létre
    rating = await Rating.create({ UserId: req.auth.id, MovieId: id, ...req.body });
    return res.status(201).send({ message: "Sikeresen értékelted ezt a filmet!", rating });
  }
});

router.delete("/:id/rate", auth, async function (req, res) {
  const { id } = req.params;

  console.log(req.auth);

  if (isNaN(id)) {
    return res.status(400).send({ message: "A megadott ID nem szám!" });
  }

  const movie = await Movie.findByPk(id);
  if (movie === null) {
    return res.status(404).send({ message: "A megadott ID-vel nem létezik film!" });
  }

  // van-e a usernek ehhez a filmhez készített értékelése?
  let rating = await Rating.findOne({ where: { UserId: req.auth.id, MovieId: id } });
  if (rating) {
    await rating.destroy();
    return res.status(200).send({ message: "Sikeresen törölted a korábbi értékelésed!" });
  } else {
    return res.status(201).send({ message: "Ehhez a filmhez nem írtál értékelést, nincs mit törölni!" });
  }
});

module.exports = router;
