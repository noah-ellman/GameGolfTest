#!/usr/bin/env php
<?php
include "IChallengeManager.php";
include "ChallengeManager.php";
include "ChallengeUser.php";


$challange = new ChallengeManager();


$i = 10000;
while( $i-- ) {
    $userId = mt_rand(1, 1000);
    $challange->addUser($userId, crc32($userId));
    $ii = 5;
    while( $ii-- ) {
        $challange->addUserRound($userId, mt_rand(1, 1000), (float)mt_rand(1, 100), 0);
    }
}

$startTime = microtime(true);
$challange->calculateRanks();
$runtime =  microtime(true) - $startTime;

foreach( $challange->getUsers() as $k => $v ) {
    if( $k > 5 ) break;
    echo $k . "\t" .  $v->username . "\t" . $v->avgScore . "\n";
}

$i = 1000;
while( $i-- ) {
    $userId = mt_rand(1, 100000);
    $challange->addUser($userId, crc32($userId));
    $ii = 3;
    while( $ii-- ) {
        $challange->addUserRound($userId, mt_rand(1, 1000), (float)mt_rand(1, 100), 0);
    }
}

echo "--------\n";


$startTime = microtime(true);


$challange->calculateRanks();

$runtime =  microtime(true) - $startTime;


foreach( $challange->getUsers() as $k => $v ) {
    if( $k > 5 ) break;
    echo $k . "\t" .  $v->username . "\t" . $v->avgScore . "\n";
}

echo "\nRun-time: ";
echo round(($runtime*100),1) . 'ms';
echo "\n";

$mem = memory_get_peak_usage();
echo "\nMax memory Usage: $mem";