"use strict";
const { Model } = require("sequelize");
const bcrypt = require("bcrypt");
module.exports = (sequelize, DataTypes) => {
  class User extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      this.hasMany(models.Rating);
    }

    comparePassword(password) {
      return bcrypt.compareSync(password, this.password);
    }

    toJSON() {
      let data = this.get();

      if (data.hasOwnProperty("password")) {
        delete data.password;
      }

      return data;
    }
  }
  User.init(
    {
      name: DataTypes.STRING,
      email: DataTypes.STRING,
      password: DataTypes.STRING,
      isAdmin: DataTypes.BOOLEAN,
    },
    {
      sequelize,
      modelName: "User",
      hooks: {
        beforeCreate: function (user) {
          user.password = bcrypt.hashSync(
            user.password,
            bcrypt.genSaltSync(12)
          );
        },
      },
    }
  );
  return User;
};
