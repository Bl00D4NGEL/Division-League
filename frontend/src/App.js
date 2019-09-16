import React from 'react';
import './App.css';
import AddPlayerMatch from './AddPlayerMatch';
import PlayerTable from './PlayerTable';
import HistoryTable from './HistoryTable';
import Config from "./Config";
import AddPlayer from "./AddPlayer";

class App extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            players: []
        };
        this.loadData = this.loadData.bind(this);
        this.getPlayers = this.getPlayers.bind(this);
    }

    componentDidMount() {
        this.loadData();
    }

    loadData() {
        fetch(Config.getAllPlayersEndpoint().url())
            .then(res => res.json())
            .then(
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
    }

    getPlayers() {
        return this.state.players;
    }

    render() {
        const {error, isLoaded} = this.state;
        if (error) {
            return <div>Error: {error.message}</div>;
        } else if (!isLoaded) {
            return (
                <div className="App">
                    Loading...
                </div>
            );
        } else {
            return (
                <div className="App">
                    <PlayerTable players={this.state.players} reloadData={this.getPlayers}/>
                    <AddPlayerMatch players={this.state.players} reloadData={this.loadData}/>

                    <HistoryTable/>
                    <AddPlayer/>
                </div>
            );
        }
    }
}

export default App;
