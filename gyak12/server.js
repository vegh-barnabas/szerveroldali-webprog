const express = require("express");
require("express-async-errors");
const app = express();

const expressPlayground = require("graphql-playground-middleware-express").default;

app.use(express.json());

app.use("/auth", require("./routers/auth"));
app.use("/genres", require("./routers/genre"));
app.use("/movies", require("./routers/movie"));
app.use("/graphql", require("./graphql"));
app.use("/playground", expressPlayground({ endpoint: "/graphql" }));

app.use((err, req, res, next) => {
  if (res.headerSent) {
    return next(err);
  }

  res.status(500).send({
    name: err.name,
    message: err.message,
    stack: err.stack.split(/$\s+/gm),
  });
});

app.listen(3000, () => {
  console.log("Express started");
});
