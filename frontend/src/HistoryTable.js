import React from 'react';

export default class HistoryTable extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            historyEntries: []
        };
        this.load();
    }


    load() {
        fetch('http://localhost:8000/history/get/all')
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState(
                        {
                            historyEntries: result,
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

        const { error, isLoaded } = this.state;
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
        const historyTableRows = this.state.historyEntries.map((entry) => {
            return (
                <tr key={entry.id}>
                    <td>{entry.id}</td>
                    <td>{entry.winner.name}</td>
                    <td>{entry.loser.name}</td>
                    <td>
                        <a href={entry.proofUrl} target="_blank">Link</a>
                    </td>
                </tr>
            )
        });
        return (
            <table>
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Winner</td>
                        <td>Loser</td>
                        <td>Proof</td>
                    </tr>
                </thead>
                <tbody>
                    {historyTableRows}
                </tbody>
            </table>
        );
    }
}