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
                entry.winner.name + ' [+' + entry.winnerEloWin + ']',
                entry.loser.name + ' [-' + entry.loserEloLose + ']',
                <a href={entry.proofUrl} target="_blank" rel="noopener noreferrer">Link</a>
            ];
        });
    }
}