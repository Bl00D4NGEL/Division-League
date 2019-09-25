export default class WinnerLoserValidator {
    static isLoserAndWinnerNotSet(state) {
        return !(
            this.doesIdExist(state.winner)
            && this.doesIdExist(state.loser)
        );
    }

    static areOpponentsEqual(state) {
        return (!this.isLoserAndWinnerNotSet(state) && state.winner.id === state.loser.id);
    }

    static doesIdExist(object) {
        return object !== undefined && object.id !== null;
    }
}