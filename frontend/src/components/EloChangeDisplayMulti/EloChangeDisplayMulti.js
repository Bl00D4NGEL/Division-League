import React from "react";

export default function EloChangeDisplay({winner, loser}) {
    return <div style={{marginTop: 25 + 'px'}}>
        <div>Results:</div>
        <div>{winner.name !== '' ? winner.name : winner.players.map(p => p.name).join(", ")} wins against {loser.name !== '' ? loser.name : loser.players.map(p => p.name).join(", ")}</div>
        {renderChange(winner.players, winner.change)}
        {renderChange(loser.players, loser.change)}
    </div>
}

const renderChange = (players, change) => {
    return <div>
        {players.map((p, i) => <div key={i}>
            {p.name} moves from {parseInt(p.elo) - parseInt(change)} to {p.elo} elo
        </div>)}
    </div>
};
