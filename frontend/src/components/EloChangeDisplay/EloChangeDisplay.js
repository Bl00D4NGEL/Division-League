import React from "react";
import WinnerLoserValidator from "../../helpers/Validators/WinnerLoserValidator";

/**
 * @return {null}
 */
export default function EloChangeDisplay(props) {
    if (!shouldRender(props)) {
        return null;
    }
    return (
        <div>
            <span>Results:</span>
            <div>
                <div>{props.winner.name} wins against {props.loser.name}</div>
                <br/>
                <div>{props.winner.name} moves
                    from {props.winner.elo - props.changes.winner} to {props.winner.elo} elo
                    (+{props.changes.winner})
                </div>
                <br/>
                <div>{props.loser.name} moves
                    from {props.loser.elo} to {props.loser.elo - props.changes.loser} elo
                    (-{props.changes.loser})
                </div>
                <br/>
            </div>
        </div>
    )
}

function shouldRender(props)
{
    return !(
        WinnerLoserValidator.isLoserAndWinnerNotSet(props)
        || WinnerLoserValidator.areOpponentsEqual(props)
        || props.changes === undefined
    );
}
