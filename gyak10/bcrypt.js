const bcrypt = require("bcrypt");

const pw = "password";

const hash1 = bcrypt.hashSync(pw, bcrypt.genSaltSync(12));
const hash2 = bcrypt.hashSync(pw, bcrypt.genSaltSync(12));

console.log(hash1);
console.log(hash2);

console.log(bcrypt.compareSync(pw, hash1));
console.log(bcrypt.compareSync(pw, hash2));
