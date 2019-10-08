import WinnerLoserValidator from "./WinnerLoserValidator";

export default class AddHistoryValidator {
    static isValid({winner, loser}) {
        return WinnerLoserValidator.isLeagueEqualFor({winner, loser})
            && WinnerLoserValidator.isLoserAndWinnerSet({winner, loser})
            && !WinnerLoserValidator.areOpponentsEqual({winner, loser})
    };
}