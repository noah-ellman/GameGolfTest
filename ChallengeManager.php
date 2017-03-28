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
        $newUser->rank = count($this->ranks);
        $this->ranks[] = $newUser;
    }

    public function addUserRound(int $userId, int $roundId, float $primaryStat, float $secondaryStat) : void {
        if (!$this->hasUser($userId)) return;
        if ($this->getUser($userId)->addRound($roundId, $primaryStat, $secondaryStat)) {
            $this->invalidate();
        }
        //$this->updateUserRank($userId);

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
     * Invalidate the ranks because the data changed somewhere
     * @param bool $yesno
     * @return bool
     */
    protected function invalidate(bool $yesno = true) {
        return $this->valid = !$yesno;
    }

    /**
     * Better implementation for calculating user ranks.
     * Does it inline for a specific user without requiring a sort.
     * This should be called after a single user adds or removes a golf round.
     * @complexity O(1) (best) O(n) (worst)
     * @param int $userId
     * @return bool
     */
    protected function updateUserRank($userId) {
        $user = $this->getUser($userId);
        if (!$user) return false;
        if ($user->avgScore === $user->avgScorePrevious) return true;
        $i = $user->rank;
        if( count($this->ranks) < 2 ) return;
        if ($user->avgScore > $user->avgScorePrevious) {
            while ($i > 0  && $this->ranks[ $i ]->avgScore < $user->avgScore) $i--;
            $blah = 'foo';
        }
        else {
            $count = count($this->ranks);
            while ($i < $count && $this->ranks[ $i ]->avgScore > $user->avgScore) $i++;
            $blah = 'foo';

        }
        $removed = array_splice($this->ranks, $user->rank, 1);
        array_splice($this->ranks, $i-1, 0, $removed);
        $this->massUpdateUserRanks();
        $this->invalidate(false);
    }

    protected function valid() { return $this->valid; }

    /**
     * Recalculates all the ranks of all the players based on their average scores for their golf rounds.
     * Since their average score is already computed, this is a simple quicksort.
     * O(n log(n)) + O(n)
     * However it still could be improved even further by using a Heap.
     * However the PHP SPLHeap doesn't have the features we need, so we'd have to build our own.
     * @return ChallengeManager
     * @complexity O(n log(n)) + O(n)
     * @todo Use the updateUserRank() instead which is O(n) and splices the ranks array in real-time.
     */
    public function calculateRanks() : self {
        if ($this->valid) return $this;
        // O(n log(n))
        usort($this->ranks, function(ChallengeUser $a, ChallengeUser $b) {
            return $b->avgScore <=> $a->avgScore;
        });
        // for convience
        $this->massUpdateUserRanks();
        $this->invalidate(false);
        return $this;
    }

    private function massUpdateUserRanks() {
        foreach ($this->ranks as $k => $v) {
            $v->rank = $k;
        }
    }

    public function & getRanks() : array {
        if (!$this->valid()) $this->calculateRanks();
        return $this->ranks;
    }

}