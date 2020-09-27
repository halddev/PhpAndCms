<?php 

//alternative to crypt(). takes 3 params:
// - The password you want to hash
// - The algorithm you want to use
// - options array. In this case we use cost => 10 - same as 10 in "$2y$10$"
//   Low cost = faster for the server but easier to crack.
//   High cost = more processing power and longer loading times, but tougher to crack.
echo password_hash('secret', PASSWORD_BCRYPT, array('cost' => 12
) );

?>