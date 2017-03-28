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


function addRandomUsers(ChallengeManager $challenge, int $how_many) {
    $i = $how_many;
    while ($i--) {
        $userId = mt_rand(1, $how_many * 10);
        $challenge->addUser($userId, crc32($userId));
        $ii = 10;
        while ($ii--) {
            $challenge->addUserRound($userId, mt_rand(1, 100), (float)mt_rand(50, 99), 0);
        }
    }

}

addRandomUsers($challenge, 20000);

$startTime = microtime(true);
$challenge->calculateRanks();
$runtime =  microtime(true) - $startTime;
$runtime *= 1000;

echo "\nTOP FIVE:\n";
echo "Rank\t\tUser\t\tScore\n";
foreach( $challenge->getRanks() as $k => $v ) {
    if( $k > 5 ) break;
    echo $k . "\t" .  $v->username . "\t" . $v->avgScore . "\n";
}


echo "\nRun-time to rank: ";
echo $runtime . 'ms';
$mem = number_format(memory_get_peak_usage()/1000/1000) . ' MB';
echo "\nMax memory Usage: $mem";

echo "\nNumber of players: " . number_format(count($challenge->getUsers()));

