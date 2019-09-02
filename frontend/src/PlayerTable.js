import React from 'react';

export default class PlayerTable extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            players: props.players.sort(function (a, b) {
                return b.elo - a.elo;
            })
        };
    }

    render() {
        const tableRows = this.state.players.map((x) => {
            x.wins = parseInt(x.wins);
            x.loses = parseInt(x.loses);

            const matches = x.loses + x.wins || 1;
            const winRate = ((x.wins || 1) / matches * 100).toPrecision(4);
            return <tr key={x.id}>
                <td>{x.name}</td>
                <td>{x.elo}</td>
                <td>{x.wins}</td>
                <td>{x.loses}</td>
                <td>{winRate} %</td>
            </tr>
        })
        return (
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Playername</th>
                            <th>Elo</th>
                            <th>Wins</th>
                            <th>Loses</th>
                            <th>Winrate</th>
                        </tr>
                    </thead>
                    <tbody>
                        {tableRows}
                    </tbody>
                </table>
            </div>
        );
    }
}
