<?php
// tested insertion sorts
// damn they are soo slow!

function timer($print=FALSE) {
    static $t;
    if( $t ) {
        $now = microtime(TRUE);
        $tt = $now - $t;
        $t = $now;
        $tt = round($tt*1000,2);
        if( $print ) echo("\nTimer: $tt ms\n");
        return (float)$tt;
    }
    else $t = microtime(TRUE);
    return 0.0;
}


function sortInsertion($array) {
    $sortedArray = array();
    for ($i = 0 ; $i < count($array); $i++) {
        $element = $array[$i];
      $j = $i;
        while($j > 0 && $sortedArray[$j-1] > $element) {
           $sortedArray[$j] = $sortedArray[$j-1];
            $j = $j-1;
        }
        $sortedArray[$j] = $element;
    }
    return $sortedArray;
};

$ii = 10000;
$a = [];
while( $ii-- ) {
    $a[] = mt_rand(1,100);
}
$test2 = $a;

timer();
$b = sortInsertion($a);
timer(TRUE);

rsort($test2);
timer(TRUE);

//echo '\n'. implode(',',$b);
