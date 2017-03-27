<?php

/**
 * @Class ChallengeUser
 */
class ChallengeUser {

    public $id = 0;
    public $username = "John";
    public $avgScore = 0;
    public $rank = null;

    protected $rounds = [];
    protected $numRounds = 0;

    private $hashMap = [];
    private $sumOfAllScores = 0;

    public function __construct( $userId, $username) {
        $this->id = $userId;
        $this->username = $username;
    }

    public function addRound(int $roundId, float $primaryStat, float $secondaryStat ) {
        if( isset($hashMap[$roundId]) ) return false;
        $round = (object)[
            'id' => $roundId,
            'primaryStat' => $primaryStat,
            'secondaryStat' => $secondaryStat
        ];
        $this->sumOfAllScores += $primaryStat;
        $hashMap[$roundId] = array_push($this->rounds, $round);
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