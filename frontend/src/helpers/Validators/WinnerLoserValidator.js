export default class WinnerLoserValidator {
    static isLoserAndWinnerSet({winner, loser}) {
        return winner !== undefined && loser !== undefined;
    }

    static areOpponentsEqual({winner, loser}) {
        return winner === loser;
    }
}
