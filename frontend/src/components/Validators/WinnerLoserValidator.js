export default class WinnerLoserValidator {
    static isLoserAndWinnerNotSet(state) {
        return !(state.winner.id !== null && state.loser.id !== null);
    }

    static areOpponentsEqual(state) {
        return (state.winner.id === state.loser.id);
    }
}