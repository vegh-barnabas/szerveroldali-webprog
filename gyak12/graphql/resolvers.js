const models = require("../models");
const { sequelize, User, Genre, Movie, Rating } = models;

module.exports = {
  Query: {
    helloWorld: () => "Hello world",

    // (parent, params, context, info)
    helloName: (_, { name }) => `Hello ${name}!`,

    genres: () => Genre.findAll(),
    genre: (_, { id }) => Genre.findByPk(id),

    movies: () => Movie.findAll(),
    movie: (_, { id }) => Movie.findByPk(id),

    users: () => User.findAll(),
    user: (_, { id }) => User.findByPk(id),

    ratings: () => Rating.findAll(),
    rating: (_, { id }) => Rating.findByPk(id),

    top: async (_, { limit }) => {
      (
        await Movie.findAll({
          attributes: {
            include: [[sequelize.fn("AVG", sequelize.col("Ratings.rating")), "averageRating"]],
          },
          include: [
            {
              model: Rating,
              attributes: [],
            },
          ],
          group: ["movie.id"],
          order: sequelize.literal("averageRating DESC"),
        })
      ).slice(0, limit);
    },
  },
  Genre: {
    // movies: async (genre) => {
    //   console.log(genre);
    //   return await genre.getMovies();
    // },

    movies: (genre) => genre.getMovies(),
  },
  Movie: {
    genres: (movie) => movie.getGenres(),
    ratings: (movie) => movie.getRatings(),

    averageRating: async (movie) => {
      const rating = await movie.getRatings({
        // [MIT, MILYEN ÉRTÉK]
        attributes: [[sequelize.fn("AVG", sequelize.col("rating")), "averageRating"]],
        raw: true, // jsont ad vissza
      });

      // console.log(rating);
      return rating[0].averageRating;
    },
  },
  Rating: {
    user: (rating) => rating.getUser(),
    movie: (rating) => rating.getMovie(),
  },
  User: {
    ratings: (user) => user.getRatings(),
  },
};
