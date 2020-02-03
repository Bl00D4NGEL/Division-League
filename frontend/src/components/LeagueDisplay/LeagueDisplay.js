import React from 'react';
import Table from "../BaseReactComponents/Table/Table";
import UserRoles from "../../UserRoles";
import Button from "../BaseReactComponents/Button/Button";
import DeletePlayerService from "../../services/DeletePlayerService";

export default function LeagueDisplay({leagueName, players, ...props}) {
    const deletePlayer = p => {
        DeletePlayerService({player: p});
    };

    const generateRows = () => players.map(p => {
        const baseData = [
            p.rank,
            <a key={p.name} target="_blank" rel="noopener noreferrer"
               href={"https://dmginc.gg/profile/" + p.playerId + "-" + p.name}>{p.name}</a>,
            p.elo,
            p.division,
            p.wins,
            p.loses,
            getWinRate(p) + ' %',
        ];

        if (props.isLoggedIn && UserRoles[props.user.role] > 0) {
            console.log("Add action");
            baseData.push(
                <Button text="Delete player" onClick={() => deletePlayer(p)}/>
            );
        }
        return baseData;
    });

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
