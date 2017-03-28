#!/usr/bin/env php
<?php
/**
 * Script to test the ChallengeManager classes.
 * Doesn't test removing rounds yet.
 */


include "IChallengeManager.php";
include "ChallengeManager.php";
include "ChallengeUser.php";


$challenge = new ChallengeManager();


$challenge->addUser(1, crc32(1));
$challenge->addUserRound(1, 1, 50, 0);
$challenge->addUser(2, crc32(2));
$challenge->addUserRound(2, 1, 45, 0);
$challenge->addUserRound(2, 2, 90, 0);

$challenge->addUser(3, crc32(1));
$challenge->addUserRound(3, 1, 20, 0);
$challenge->addUser(4, crc32(4));
$challenge->addUserRound(4, 1, 99, 0);
$challenge->addUserRound(4, 2, 95, 0);


$startTime = microtime(true);
$challenge->calculateRanks();
$runtime =  microtime(true) - $startTime;
$runtime *= 1000;

echo "\nTOP FIVE:\n";
echo "Rank\tUser\t\tScore\n";
foreach( $challenge->getRanks() as $k => $v ) {
    if( $k > 5 ) break;
    echo $k . "\t" .  $v->username . "\t" . $v->avgScore . "\n";
}


echo "\nRun-time to rank: ";
echo $runtime . 'ms';
$mem = number_format(memory_get_peak_usage()/1000/1000) . ' MB';
echo "\nMax memory Usage: $mem";

echo "\nNumber of players: " . number_format(count($challenge->getUsers()));

