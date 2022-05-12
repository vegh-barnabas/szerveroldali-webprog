"use strict";
const { Model } = require("sequelize");
module.exports = (sequelize, DataTypes) => {
  class Movie extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      this.hasMany(models.Rating);
      this.belongsToMany(models.Genre, { through: "GenreMovie" });
    }
  }
  Movie.init(
    {
      title: {
        type: DataTypes.STRING,
        allowNull: false,
      },
      director: DataTypes.STRING,
      description: DataTypes.TEXT,
      year: DataTypes.INTEGER,
      length: DataTypes.INTEGER,
      imageUrl: DataTypes.STRING,
      ratingsEnabled: {
        type: DataTypes.BOOLEAN,
        allowNull: false,
      },
    },
    {
      sequelize,
      modelName: "Movie",
    }
  );
  return Movie;
};
