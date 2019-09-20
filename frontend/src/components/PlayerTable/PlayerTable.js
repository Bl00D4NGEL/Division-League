import React from 'react';
import Table from "../BaseElements/Table";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Config from "../../Config";
import Loader from "../BaseElements/Loader";

export default class PlayerTable extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            error: undefined,
            players: []
        };

        this.loadData();
    }

    loadData() {
        const self = this;
        new CustomRequest(
            Config.getAllPlayersEndpoint(),
            (result) => {
                setter({players: result.data});
            },
            setter
        )
            .execute();

        function setter(data) {
            self.setState({
                isLoaded: true,
                ...data
            });
        }
    }

    render() {
        return <Loader
            isLoaded={this.state.isLoaded}
            error={this.state.error}
            content={
                <Table
                    sortable={true}
                    sortKey={1}
                    tableHead={['Player', 'Elo', 'Division', 'Wins', 'Loses', 'Win rate']}
                    tableData={this.generateRows()}
                />
            }
        />
    }

    generateRows() {
        return this.state.players.map((entry) => {
            const matches = entry.loses + entry.wins || 1;
            const winRate = ((entry.wins || 1) / matches * 100).toPrecision(4);
            return [
                <a key={entry.name} target="_blank" rel="noopener noreferrer" href={"https://di.community/profile/" + entry.playerId + "-" + entry.name}>{entry.name}</a>,
                entry.elo,
                entry.division,
                entry.wins,
                entry.loses,
                winRate + ' %'
            ];
        });
    }

    sort() {
        return this.sorter();
    }
}