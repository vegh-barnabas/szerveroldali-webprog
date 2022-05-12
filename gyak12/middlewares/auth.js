const { expressjwt } = require("express-jwt");

module.exports = expressjwt({ secret: "secret", algorithms: ["HS256"] });
