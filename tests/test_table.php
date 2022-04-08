<?php

use function AtyKlaxas\LegendaryFiesta\table;

table([]);
table([1, 2 ,3]);

table([
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9],
]);

table([
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9],
], true);

table([
    'test1' => ['a' => 1, 'b' => 2, 'c' => 3],
    'test2' => ['a' => 4, 'b' => 5, 'c' => 6],
    'test3' => ['a' => 7, 'b' => 8, 'c' => 9],
    'test4' => ['d' => 7, 'e' => 8, 'f' => 9],
]);

table([
    'test1' => ['a' => 1, 'b' => 2, 'c' => 3],
    'test2' => ['a' => 4, 'b' => 5, 'c' => 6],
    'test3' => ['a' => 7, 'b' => 8, 'c' => 9],
    'test4' => ['d' => 7, 'e' => 8, 'f' => 9],
], true);
