import React from 'react';
import './App.css';
import AddHistory from './components/AddHistory/AddHistory';
import PlayerTable from './components/PlayerTable/PlayerTable';
import HistoryTable from './components/HistoryTable/HistoryTable';
import Config from "./Config";
import AddPlayer from "./components/AddPlayer/AddPlayer";
import CustomRequest from "./helpers/CustomRequest/CustomRequest";
import Loader from "./components/BaseElements/Loader";

class App extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            error: null,
            isLoaded: false,
            players: []
        };
        this.loadData = this.loadData.bind(this);
    }

    componentDidMount() {
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
            error={this.state.error}
            isLoaded={this.state.isLoaded}
            content={
                <div className="App">
                    <PlayerTable players={this.state.players}/>
                    <AddHistory onAdd={this.loadData} players={this.state.players}/>

                    <HistoryTable/>
                    <AddPlayer/>
                </div>
            }
        />
    }
}

export default App;
