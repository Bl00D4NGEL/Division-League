import React from 'react';
import Config from '../Config';
import Table from './styling/Table';

export default class HistoryTable extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            historyEntries: []
        };
        this.load();
    }


    load() {
        fetch(Config.recentHistoryEndpoint().url())
            .then(res => res.json())
            .then(
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
    }

    render() {
        const {error, isLoaded} = this.state;
        if (error) {
            return <div>Error: {error.message}</div>;
        } else if (!isLoaded) {
            return (
                <div className="App">Loading...</div>
            );
        } else {
            return <div>{this.generateHistoryTable()}</div>
        }
    }

    generateHistoryTable() {
        const rows = this.state.historyEntries.map((entry) => {
            return [
                entry.id,
                entry.winner.name,
                entry.loser.name,
                <a href={entry.proofUrl} target="_blank" rel="noopener noreferrer">Link</a>
            ];
        });

        return (
            <Table tableHead={['ID', 'Winner', 'Loser', 'Proof']} tableData={rows}/>
        );
    }
}