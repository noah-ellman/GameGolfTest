#!/usr/bin/env php
<?php
/**
 * Script to test the ChallengeManager classes.
 * Doesn't test removing rounds yet.
 */


include "IChallengeManager.php";
include "ChallengeManager.php";
include "ChallengeUser.php";


$challange = new ChallengeManager();

$MAX_USERS = 20000;
$i = $MAX_USERS;
while( $i-- ) {
    $userId = mt_rand(1, $MAX_USERS*2);
    $challange->addUser($userId, crc32($userId));
    $ii = 10;
    while( $ii-- ) {
        $challange->addUserRound($userId, mt_rand(1, 100), (float)mt_rand(1, 100), 0);
    }
}

$startTime = microtime(true);
$challange->calculateRanks();
$runtime =  microtime(true) - $startTime;
$runtime *= 1000;

echo "\nTOP FIVE:\n";
echo "Rank\t\tUser\t\tScore\n";
foreach( $challange->getRanks() as $k => $v ) {
    if( $k > 5 ) break;
    echo $k . "\t" .  $v->username . "\t" . $v->avgScore . "\n";
}


echo "\nRun-time to rank: ";
echo $runtime . 'ms';
$mem = number_format(memory_get_peak_usage()/1000/1000) . ' MB';
echo "\nMax memory Usage: $mem";

echo "\nNumber of players: " . number_format(count($challange->getUsers()));

