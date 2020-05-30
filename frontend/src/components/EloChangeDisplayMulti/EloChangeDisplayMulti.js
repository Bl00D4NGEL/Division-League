import React from "react";

export default function EloChangeDisplay({changes}) {
    return <div style={{marginTop: 25 + 'px'}}>
        <div>Results:</div>
        {renderChange(changes.winner)}
        {renderChange(changes.loser)}
    </div>
}

const renderChange = (players) => {
    return <div>
        {players.map(p => <div key={p.name}>
            {p.name} {(p.eloChange > 0 ? 'gains' : 'loses')} {Math.abs(p.eloChange)} elo
        </div>)}
    </div>
};
