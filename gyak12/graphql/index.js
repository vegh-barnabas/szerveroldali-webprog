const { graphqlHTTP } = require("express-graphql");
const { makeExecutableSchema } = require("@graphql-tools/schema");
const { readFileSync } = require("fs");
const { join: pathJoin } = require("path");

// Typedefs
const { typeDefs: scalarsTypeDefs, resolvers: scalarsResolvers } = require("graphql-scalars");
const ourTypeDefs = readFileSync(pathJoin(__dirname, "./typedefs.gql")).toString();

// Resolvers
const ourResolver = require("./resolvers");

// Schema
const executableSchema = makeExecutableSchema({
  typeDefs: [scalarsTypeDefs, ourTypeDefs],
  resolvers: [scalarsResolvers, ourResolver],
});

// Middleware
module.exports = graphqlHTTP({
  schema: executableSchema,
  graphiql: {
    headerEditorEnabled: true,
  },
});
