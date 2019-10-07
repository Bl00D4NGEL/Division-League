export default class WinnerLoserValidator {
    static isLoserAndWinnerSet({winner, loser}) {
        return (
            this.doesIdExist(winner)
            && this.doesIdExist(loser)
        );
    }

    static areOpponentsEqual({winner, loser}) {
        return (this.isLoserAndWinnerSet({winner, loser}) && winner.id === loser.id);
    }

    static doesIdExist(object) {
        return object !== undefined && object.id !== null;
    }

    static isLeagueEqualFor({winner, loser}) {
        return winner.league === loser.league;
    };
}