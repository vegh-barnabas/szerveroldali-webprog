const models = require("./models");
const { User, Genre, Movie, Rating } = models;
const { Op } = require("sequelize");
const { sequelize } = require("./models");

(async () => {
  // Minden film lekérése
  console.log(await Movie.findAll());

  // Filmek megszámolása
  console.log(await Movie.count());

  // Egy adott film lekérése
  console.log(await Movie.findByPk(1));
  console.log(await Movie.findByPk(100)); // null

  // Csak a megadott mezők lekérése
  // 1. verzió
  console.log(
    await Movie.findAll({
      attributes: ["id", "title"],
    })
  );
  // 2. verzió
  console.log(
    (
      await Movie.findAll({
        attributes: {
          exclude: ["title"],
        },
      })
    ).map((movie) => movie.toJSON())
  );

  // Mező lekérése más néven
  console.log(
    await Movie.findAll({
      attributes: ["id", ["title", "cim"]],
    })
  );

  // Feltételes lekérés
  console.log(
    await Movie.findAll({
      where: {
        year: {
          [Op.gt]: 1950,
        },
      },
    })
  );
  console.log(
    await Movie.findAll({
      where: {
        year: 2011,
      },
    })
  );

  // Film kategóriáinak lekérése
  console.log((await (await Movie.findByPk(3)).getGenres()).map((genre) => genre.toJSON()));

  // Filmek átlag értékelésének kiszámolása
  console.log(
    JSON.stringify(
      await Movie.findAll({
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
      }),
      null,
      4
    )
  );

  // console.log((await User.findByPk(1)).toJSON());
  // console.log((await User.findByPk(1)).comparePassword("password"));
})();
