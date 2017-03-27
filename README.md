
### GameGolfTest
### ChallangeManager

This is a simple leadboard module

*Classes* `ChallangeManager` `ChallangeUser`

*Interfaces* `IChallangeManager`

The `ChallengeManager` contains a array of `ChallengeUser`'s. Through this classes interface your add golf apponents and add new rounds of golf for a particular user.  You can also remove them.  It will maintain arrays and hashmaps of users based on both their rank and their userId.  When adding users or users' rounds, the ranking will becomes invalidated, and will recalculate for everyone the next time any user's rank is retreived via `getUserRank()` or `getRanks()`. 


The `ChallengeUser` class manages adding and removing golf rounds to the user's records.  It keeps track of the average scrore and ensures the `avgScore` property is always accurate. It does this in constant time, very effenciently, regardless weather there is 2 rounds or 100,000 rounds.   


## Testing

Run `index.php` to test.

## Todo

All players ranks recalcuate in about 150ms for about 20,000 players each with 10 rounds.

This could be even faster though if needed.

## Example
```
$challange = new ChallengeManager();
$challange->addUser(1, 'Joe'));
$challange->addUserRound(1, 1, 80.523, 1);
$challange->addUserRound(1, 2, 40.523, 1);

$challange->addUser(2, 'Tom'));
$challange->addUserRound(1, 1, 30.5, 1);
$challange->addUserRound(1, 2, 90.24, 1);

$challenge->getUserRank(1);  // get joe's rank

$challenge->getRanks(); // get all ranks



```