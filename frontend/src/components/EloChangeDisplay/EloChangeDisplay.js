import React from "react";
import WinnerLoserValidator from "../../helpers/Validators/WinnerLoserValidator";

export default function EloChangeDisplay({winner, loser, changes}) {
    const shouldRender = () => {
        return !(
            !WinnerLoserValidator.isLoserAndWinnerSet({winner, loser})
            || WinnerLoserValidator.areOpponentsEqual({winner, loser})
            || changes === undefined
        );
    };

    if (!shouldRender()) {
        return <div/>;
    }
    return (
        <div style={{marginTop: 25 + 'px'}}>
            <span>Results:</span>
            <div>
                <div>{winner.name} wins against {loser.name}</div>
                <br/>
                <div>{winner.name} moves
                    from {winner.elo - changes.winner} to {winner.elo} elo
                    (+{changes.winner})
                </div>
                <br/>
                <div>{loser.name} moves
                    from {loser.elo - changes.loser} to {loser.elo} elo
                    ({changes.loser})
                </div>
                <br/>
            </div>
        </div>
    )
}