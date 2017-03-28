<?php


/**
 * @Class ChallengeManager
 */
class ChallengeManager implements IChallengeManager {

    /**
     * Users in the challenge, numeric array, no order
     * @var ChallengeUser[]
     */
    protected $users = [];

    /**
     * Users in the challenge sorted by rank
     * @var ChallengeUser[]
     */
    protected $ranks = [];

    /**
     * Lookup table for the users array, allows for O(1) accesss time by userId
     * @var int[]
     */
    private $usersHash = [];

    /**
     * @var bool Weather ot not the ranks to be recalculated
     */
    private $valid = true;



    public function __construct() { }

    public function addUser(int $userId, string $username) : void {
        if ($this->hasUser($userId)) return;
        $newUser = new ChallengeUser($userId, $username);
        $this->usersHash[ $userId ] = array_push($this->users, $newUser) - 1;
        $this->ranks[] = $newUser;
    }

    public function addUserRound(int $userId, int $roundId, float $primaryStat, float $secondaryStat) : void {
        if (!$this->hasUser($userId)) return;
        $this->getUser($userId)->addRound($roundId, $primaryStat, $secondaryStat);
        $this->invalidate();
    }

    public function getUserRank(int $userId) : int {
        if (!$this->valid()) $this->calculateRanks();
        return $this->getUser($userId)->rank;
    }

    public function getUserRounds(int $userId) : array {
        return $this->getUser($userId)->getRounds();
    }

    public function & getUsers() : array {
        return $this->users;
    }

    public function removeUserRound(int $userId, int $roundId) : void {
        if (!$this->getUser($userId)) return;
        $this->getUser($userId)->removeRound($roundId);
        $this->invalidate();
    }

    public function hasUser(int $userId) : bool {
        return isset($this->usersHash[ $userId ]) ? true : false;
    }

    public function getUser(int $userId) : ?ChallengeUser {
        return $this->users[ $this->usersHash[ $userId ] ] ?? null;
    }

    /**
     * Recalculates all the ranks of all the players based on their average scores for their golf rounds.
     * Since their average score is already computed, this is a simple quicksort.
     * O(n log(n)) + O(n)
     * However it still could be improved even further by using a Heap.
     * However the PHP SPLHeap doesn't have the features we need, so we'd have to build our own.
     *
     * @return ChallengeManager
     * @complexity O(n log(n)) + O(n)
     * @todo Maybe use a heap, that could be faster.
     */
    public function calculateRanks() : self {
        if( $this->valid ) return $this;
        // O(n log(n))
        usort($this->ranks, function(ChallengeUser $a, ChallengeUser $b) {
            return $b->avgScore <=> $a->avgScore;
        });
        // for convience
        foreach ($this->ranks as $k => $v) {
            $v->rank = $k;
        }
        $this->invalidate(false);
        return $this;
    }

    public function & getRanks() : array {
        if (!$this->valid()) $this->calculateRanks();
        return $this->ranks;
    }

    /**
     * Invalidate the ranks because the data changed somewhere
     * @param bool $yesno
     * @return bool
     */
    protected function invalidate(bool $yesno = true) {
        return $this->valid = !$yesno;
    }

    protected function valid() { return $this->valid; }



}