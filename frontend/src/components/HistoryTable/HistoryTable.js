import React from 'react';
import Config from '../../Config';
import Table from '../BaseElements/Table';
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Loader from "../BaseElements/Loader";

export default class HistoryTable extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            historyEntries: []
        };
        this.load();
    }


    load() {
        new CustomRequest(
            Config.recentHistoryEndpoint(),
            (result) => {
                this.setState(
                    {
                        historyEntries: result.data,
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
            content={<Table tableHead={['ID', 'Winner', 'Loser', 'Proof']}
                            tableData={this.generateHistoryTableRows()}/>}
        />
    }

    generateHistoryTableRows() {
        return this.state.historyEntries.map((entry) => {
            return [
                entry.id,
                formatWinnerEntry(entry),
                formatLoserEntry(entry),
                <a href={entry.proofUrl} target="_blank" rel="noopener noreferrer">Link</a>
            ];
        });
    }
}

function formatWinnerEntry(entry) {
    return formatEntry(entry.winner.name, entry.winner.elo, entry.winnerEloWin);
}

function formatLoserEntry(entry) {
    return formatEntry(entry.loser.name, entry.loser.elo, -entry.loserEloLose);
}

function formatEntry(name, elo, gain) {
    return name + ' (' + (elo - gain) + ' => ' + elo + ' [' + (gain > 0 ? '+' : '' ) + gain + '])';
}