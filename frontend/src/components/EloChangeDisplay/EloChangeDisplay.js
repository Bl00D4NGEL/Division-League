import React from "react";

export default function EloChangeDisplay({winner, loser}) {
    return <div style={{marginTop: 25 + 'px'}}>
        <div>Results:</div>
        <div>{winner.name} wins against {loser.name}</div>
        {renderChange(winner)}
        {renderChange(loser)}
    </div>
}

const renderChange = (player = {name: 'Unknown', fromElo: 0, toElo: 0}) => {
    const change = player.toElo - player.fromElo;
    return <div>{player.name} moves
        from {player.fromElo} to {player.toElo} elo
        ({change > 0 ? '+' + change : change})
    </div>
};
