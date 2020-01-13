import React from "react";

export default function EloChangeDisplay({winner, loser}) {
    return <div style={{marginTop: 25 + 'px'}}>
        <div>Results:</div>
        <div>{winner.name} wins against {loser.name}</div>
        {renderChange(winner.players, winner.change)}
        {renderChange(loser.players, loser.change)}
    </div>
}

const renderChange = (players, change) => {
    return <div>
        {players.map((p, i) => <div key={i}>
            {p.name} moves from {p.elo} to {parseInt(p.elo) + parseInt(change)} elo
        </div>)}
    </div>
};
