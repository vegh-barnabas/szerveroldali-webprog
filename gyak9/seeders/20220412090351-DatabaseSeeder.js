"use strict";

const models = require("../models");
const { User, Genre, Movie, Rating } = models;
const { faker } = require("@faker-js/faker");

module.exports = {
  async up(queryInterface, Sequelize) {
    // -- Műfajok --
    const genresCount = faker.datatype.number({ min: 5, max: 10 });
    const genres = [];

    for (let i = 0; i < genresCount; i++) {
      genres.push(
        await Genre.create({
          name: faker.lorem.word(),
          description: faker.lorem.sentence(4),
        })
      );
    }

    // -- Felhasználók --
    const usersCount = faker.datatype.number({ min: 10, max: 20 });
    const users = [];

    // Admin
    users.push(
      await User.create({
        name: "Admin",
        email: "admin@szerveroldali.hu",
        password: "password",
        isAdmin: true,
      })
    );

    // User
    for (let i = 1; i < usersCount; i++) {
      users.push(
        await User.create({
          name: faker.name.findName(),
          email: `user${i}@szerveroldali.hu`,
          password: "password",
          isAdmin: false,
        })
      );
    }

    // -- Filmek --
    const moviesCount = faker.datatype.number({ min: 15, max: 25 });
    const movies = [];

    for (let i = 0; i < moviesCount; i++) {
      movies.push(
        await Movie.create({
          title: faker.lorem.words(faker.datatype.number({ min: 1, max: 4 })),
          director: faker.name.findName(),
          description: faker.lorem.sentence(),
          year: faker.datatype.number({
            min: 1870,
            max: new Date().getFullYear(),
          }),
          length: faker.datatype.number({ min: 60, max: 60 * 4 }),
          imageUrl: faker.image.imageUrl(),
          ratingsEnabled: faker.datatype.boolean(),
        })
      );
    }

    // -- Relációk, értékelések --
    // Műfaj a filmhez
    for (let movie of movies) {
      await movie.setGenres(faker.random.arrayElements(genres));

      // Értékelés a filmhez
      const randomUsers = faker.random.arrayElements(users);
      for (let user of randomUsers) {
        await Rating.create({
          rating: faker.datatype.number({ min: 1, max: 5 }),
          comment: faker.datatype.boolean() ? faker.lorem.sentence() : "",
          UserId: user.id,
          MovieId: movie.id,
        });
      }
    }
  },

  async down(queryInterface, Sequelize) {
    /**
     * Add commands to revert seed here.
     *
     * Example:
     * await queryInterface.bulkDelete('People', null, {});
     */
  },
};
