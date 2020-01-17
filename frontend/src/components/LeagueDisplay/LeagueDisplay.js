import React from 'react';
import Table from "../BaseReactComponents/Table/Table";

export default function LeagueDisplay({leagueName, players}) {

    const generateRows = () => players.map(p => (
        [
            p.rank,
            <a key={p.name} target="_blank" rel="noopener noreferrer"
               href={"https://di.community/profile/" + p.playerId + "-" + p.name}>{p.name}</a>,
            p.elo,
            p.division,
            p.wins,
            p.loses,
            getWinRate(p) + ' %',
        ]
    ));

    const getWinRate = (entry) => {
        if (parseInt(entry.wins) === 0) {
            return 0;
        }
        return (parseInt(entry.wins) / (parseInt(entry.wins) + parseInt(entry.loses)) * 100).toPrecision(4);
    };
    return <div style={{paddingBottom: 20 + 'px'}}>
        <h1>League {leagueName} ({players.length} players)</h1>
        <Table
            sortable={true}
            defaultSortKey={0}
            tableHead={['Rank', 'Player', 'Elo', 'Division', 'Wins', 'Loses', 'Win rate']}
            extraClassNames={{2: 'pw-hide'}}
            tableData={generateRows()}
        />
    </div>
}
