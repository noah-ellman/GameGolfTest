<?php

/**
 * Represents a user in the challenge. This classes handles the rounds.
 * @Class ChallengeUser
 */
class ChallengeUser {

    public $id = 0;
    public $username = "John";
    public $avgScore = 0;
    public $rank = null;

    protected $rounds = [];
    protected $numRounds = 0;

    /**
     * @var array Hash to handle the round ID's for quick lookup of $rounds by database ID.
     */
    private $hashMap = [];
    private $sumOfAllScores = 0;

    public function __construct( $userId, $username) {
        $this->id = $userId;
        $this->username = $username;
    }

    /**
     * Add a new round.
     * The players average score out all his rounds is automatically updated.
     * @param int $roundId
     * @param float $primaryStat
     * @param float $secondaryStat
     * @return bool
     */
    public function addRound(int $roundId, float $primaryStat, float $secondaryStat ) {
        if( isset($this->hashMap[$roundId]) ) return false;
        $round = (object)[
            'id' => $roundId,
            'primaryStat' => $primaryStat,
            'secondaryStat' => $secondaryStat
        ];
        $this->sumOfAllScores += $primaryStat;
        $this->hashMap[$roundId] = array_push($this->rounds, $round) - 1;
        $this->numRounds++;
        $this->calculateAvgScore();
    }

    public function removeRound(int $roundId) {
        $index = $this->hashMap[ $roundId ] ?? -1;
        if( $index === -1 ) return false;
        $this->sumOfAllScores -= $this->rounds[$index]->primaryStat;
        $this->numRounds--;
        $oldround =  array_splice( $this->rounds, $index );
        $this->calculateAvgScore();
        return $oldround;
    }

    /**
     * Maintain the average score of user's rounds with O(1) time.
     * @return float|int
     */
    private function calculateAvgScore() {
        $this->avgScore = $this->sumOfAllScores / $this->numRounds;
        return $this->avgScore;
    }

    public function getAvgScore() {
        return $this->avgScore;
    }

    public function getRounds() {
        return $this->rounds;
    }

}