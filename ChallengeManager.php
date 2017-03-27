<?php

/**
 * @Class ChallengeManager
 */
class ChallengeManager implements IChallengeManager {

    /**
     * @var ChallengeUser[]
     */
    protected $users = [];

    /**
     * @var ChallengeUser[]
     */
    protected $ranks = [];

    private $usersHash = [];
    private $valid = true;

    public function __construct() { }


    public function addUser(int $userId, string $username) : void {
        if (isset($this->usersHash[ $userId ])) return;
        $this->usersHash[ $userId ] = array_push($this->users, new ChallengeUser($userId, $username));
        $this->users[ $userId ] = new ChallengeUser($userId, $username);
        $this->ranks[] = $this->users[ $this->usersHash[ $userId ] ];
    }

    public function addUserRound(int $userId, int $roundId, float $primaryStat, float $secondaryStat) : void {
        $this->users[ $userId ]->addRound($roundId, $primaryStat, $secondaryStat);
        $this->invalidate();
    }

    public function getUserRank(int $userId) : int {
        if( !$this->valid() ) $this->calculateRanks();
        return $this->users[ $userId ]->rank;
    }

    public function getUserRounds(int $userId) : array {
        return $this->getUser($userId)->getRounds();
    }

    public function & getUsers() : array {
        return $this->users;
    }

    public function & getRanks() : array {
        return $this->ranks;
    }

    public function removeUserRound(int $userId, int $roundId) : void {
        if( !$this->getUser($userId) ) return;
        $this->getUser($userId)->removeRound($roundId);
        $this->invalidate();
    }


    public function getUser(int $userId) : ?ChallengeUser {
        return null ?? $this->users[ $this->usersHash[ $userId ] ];
    }

    public function calculateRanks() : self {
        usort($this->ranks, function(ChallengeUser $a, ChallengeUser $b) {
            return $a->avgScore <=> $b->avgScore;
        });
        foreach ($this->ranks as $k => $v) {
            $v->rank = $k;
        }
        return $this;
    }

    protected function invalidate(bool $yesno = true) {
        return $this->valid = !$yesno;
    }

    protected function valid() { return $this->valid; }

}