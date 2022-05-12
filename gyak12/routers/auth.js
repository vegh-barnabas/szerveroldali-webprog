const express = require("express");
const router = express.Router();

const models = require("../models");
const { User } = models;

const jwt = require("jsonwebtoken");

const auth = require("../middlewares/auth");

router.post("/login", async function (req, res) {
  const { email, password } = req.body;

  if (!email || !password) {
    return res.status(400).send({ message: "Nem adtál meg emailt vagy jelszót!" });
  }

  const user = await User.findOne({ where: { email } });
  if (!user) {
    return res.status(404).send({ message: "A megadott email címmel nem létezik felhasználó!" });
  }

  if (!user.comparePassword(password)) {
    return res.status(401).send({ message: "A megadott jelszó helytelen!" });
  }

  const token = jwt.sign(user.toJSON(), "secret", { algorithm: "HS256" });
  return res.send({ token });
});

router.get("/who", auth, async function (req, res) {
  res.send(req.auth);
});

router.post("/register", async function (req, res) {
  const { name, email, password } = req.body;
  if (!name || !email || !password) {
    return res.status(400).send({ message: "Nem adtál meg emailt, nevet vagy jelszót!" });
  }

  const user = await User.create({ name, email, password });

  const token = jwt.sign(user.toJSON(), "secret", { algorithm: "HS256" });
  return res.status(201).send({ token });
});

module.exports = router;
