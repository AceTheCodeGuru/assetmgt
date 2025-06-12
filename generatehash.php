<?php
echo password_hash('admin123', PASSWORD_DEFAULT);
// Output: $2y$10$eW5z1Z1a3b1c2d3e4f5g6h7i8j9k0l1m2n3o4p5q6r7s8t9u0v1w2x3y4z5
// Note: The output will vary each time due to the nature of password hashing.
