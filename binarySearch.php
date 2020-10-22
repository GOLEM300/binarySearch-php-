<?php

//Constants
define("FILE_NAME", "test.txt");
define("VALUE", "key0");
define("SIZE", 100000);

//Create file if it is not exist
if (!file_exists(FILE_NAME)) {
    createFile(FILE_NAME, SIZE);
}

function createFile($fileName, $length)
{
    $file = fopen($fileName, 'w');
    for ($i = 0; $i < $length; $i++) {
        fwrite($file, "key" . $i . "\t" . "value" . $i . "\x0A");
    }
}

//Main function
function binarySearch($fileName, $value)
{
    $file = new SplFileObject($fileName); //parent to get "string" method
    $left = 0;
    $right = sizeof(file($fileName));

    while ($left <= $right) {

        $mid = floor(($left + $right) / 2);

        $file->seek($mid); //mid seek
        $elem = explode("\t", $file->current()); //array return  elem[key,value]
        $strnatcmp = strnatcmp($elem[0], $value); //compare strings
        if ($strnatcmp > 0) {
            $right = $mid - 1;
        } elseif ($strnatcmp < 0) {
            $left = $mid + 1;
        } 
        else {
            return $elem[1];
        }
    }
    return 'undef';
}

//Time
function getTime($time = false)
{
    return $time === false ? microtime(true) : round(microtime(true) - $time, 3);
}

//Function expression
$time = getTime();
$result = binarySearch(FILE_NAME, VALUE);
$time = getTime($time);
echo "Значение ключа - " . $result . "\n" . "Времени затрачено - " . $time . " секунд  ";
