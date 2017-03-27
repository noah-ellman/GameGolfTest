<?php

/**
 * Interface for the ChallengeManager class that had to be used
 * as per the specs of this project.
 *
 * @Interface IChallengeManager
 */
interface IChallengeManager {

    function addUser(int $userId, string $username) : void;

    function addUserRound(int $userId, int $roundId, float $primaryStat, float $secondaryStat) : void;

    function removeUserRound(int $userId, int $roundId) : void;

    function getUserRank(int $userId) : int;

    function getUsers() : array;

    function getUserRounds(int $userId) : array;

}