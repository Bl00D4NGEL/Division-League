import React from 'react';
import Table from "../BaseReactComponents/Table/Table";

export default function LeagueDisplay({leagueName, players}) {

    const generateRows = () => {
        return players.map((entry) => {
            return [
                <a key={entry.name} target="_blank" rel="noopener noreferrer"
                   href={"https://di.community/profile/" + entry.playerId + "-" + entry.name}>{entry.name}</a>,
                entry.elo,
                entry.division,
                entry.wins,
                entry.loses,
                getWinRate(entry) + ' %',
            ];
        });
    };

    const getWinRate = (entry) => {
        if (parseInt(entry.wins) === 0) {
            return 0;
        }
        return (parseInt(entry.wins) / (parseInt(entry.wins) + parseInt(entry.loses)) * 100).toPrecision(4);
    };
    return <div style={{paddingBottom: 20 + 'px'}}>
        <h1>League {leagueName}</h1>
        <Table
            sortable={true}
            defaultSortKey={1}
            defaultReverseSort={true}
            tableHead={['Player', 'Elo', 'Division', 'Wins', 'Loses', 'Win rate']}
            extraClassNames={{2: 'pw-hide'}}
            tableData={generateRows()}
        />
    </div>
}
