import React from 'react';
import Table from "../BaseElements/Table";

export default class PlayerTable extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <Table tableHead={['Player', 'Elo', 'Wins', 'Loses', 'Win rate']} tableData={this.generateRows()}/>
        );
    }

    generateRows() {
        return this.props.players.map((entry) => {
            const matches = entry.loses + entry.wins || 1;
            const winRate = ((entry.wins || 1) / matches * 100).toPrecision(4);
            console.log(entry);
            return [
                entry.name,
                entry.elo,
                entry.wins,
                entry.loses,
                winRate + ' %'
            ];
        });
    }
}