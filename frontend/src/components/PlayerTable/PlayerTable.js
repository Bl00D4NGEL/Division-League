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
        new CustomRequest(
            Config.getAllPlayersEndpoint(),
            (result) => {
                this.setState(
                    {
                        players: result.data,
                        isLoaded: true
                    }
                )
            },
            (error) => {
                this.setState({
                    isLoaded: true,
                    error
                });
            }
        )
            .execute();
    }

    render() {
        return <Loader
            isLoaded={this.state.isLoaded}
            error={this.state.error}
            content={
                <Table
                    tableHead={['Player', 'Elo', 'Wins', 'Loses', 'Win rate']}
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
                entry.name,
                entry.elo,
                entry.wins,
                entry.loses,
                winRate + ' %'
            ];
        });
    }
}