import React from 'react';
import './App.css';
import PlayerMatchAdd from './PlayerMatchAdd';
import PlayerTable from './PlayerTable';
import HistoryTable from './HistoryTable';

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
    fetch('http://localhost:8000/player/get/all')
      .then(res => res.json())
      .then(
        (result) => {
          this.setState(
            {
              players: result,
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
        <div className="App">
          Loading...
      </div>
      );
    } else {
      return (
        <div className="App">
          <PlayerTable players={this.state.players} />
          <PlayerMatchAdd players={this.state.players} reloadData={this.loadData} />

          <HistoryTable />
        </div>
      );
    }
  }
}

export default App;
