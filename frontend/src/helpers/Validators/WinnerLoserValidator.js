export default class WinnerLoserValidator {
    static isLoserAndWinnerNotSet({winner, loser}) {
        return !(
            this.doesIdExist(winner)
            && this.doesIdExist(loser)
        );
    }

    static areOpponentsEqual({winner, loser}) {
        return (!this.isLoserAndWinnerNotSet({winner, loser}) && winner.id === loser.id);
    }

    static doesIdExist(object) {
        return object !== undefined && object.id !== null;
    }
}