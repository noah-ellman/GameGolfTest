<?php


// TESTING SOMETHING


class RankHeap extends SplHeap {

    protected function compare($value1, $value2) {
        if ($value1[0] === $value2[0]) return 0;
        return $value1[0] < $value2[0] ? -1 : 1;
    }

};

$heap = new RankHeap();

$i = 100;
while( $i-- ) {
$heap->insert([mt_rand(1,100),"blah"]);
}

$heap->top();
while( $heap->valid() ) {
    $data = $heap->current();
    echo "$data[0]\t$data[1]\n";
    $heap->next();
};