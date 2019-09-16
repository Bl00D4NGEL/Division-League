import React from 'react';
import Table from "./styling/Table";

export default class PlayerTable extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            players: props.players.sort(function (a, b) {
                return b.elo - a.elo;
            })
        };

        this.reloadDataFunction = props.reloadData;
    }

    render() {
        const rows = this.reloadDataFunction().map((entry) => {
            const matches = entry.loses + entry.wins || 1;
            const winRate = ((entry.wins || 1) / matches * 100).toPrecision(4);

            return [
                entry.name,
                entry.elo,
                entry.wins,
                entry.loses,
                winRate + ' %'
            ];
        });

        return (
            <Table tableHead={['Player', 'Elo', 'Wins', 'Loses', 'Win rate']} tableData={rows}/>
        );
    }
}
