<?php
$providerid = rtrim(filter_input(INPUT_GET, "p", FILTER_SANITIZE_SPECIAL_CHARS));
$t = rtrim(filter_input(INPUT_GET, "t", FILTER_SANITIZE_SPECIAL_CHARS));

echo "You have a message $providerid $t";