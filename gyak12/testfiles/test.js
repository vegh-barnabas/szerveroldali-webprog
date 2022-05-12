const models = require("./models");
const { Genre } = models;
const { faker } = require("@faker-js/faker");

(async () => {
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

  console.log(genres);
})();
