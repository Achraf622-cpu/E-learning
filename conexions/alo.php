<?php
$username = 'AchrafHz'; // Replace with desired admin username
$email = 'hanzaz@gmail.com'; // Replace with admin's email
$password = 'password'; // Replace with desired admin password

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Output the hashed password
echo $hashed_password;
