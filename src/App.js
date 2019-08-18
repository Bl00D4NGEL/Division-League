import React from 'react';
import './App.css';

function PlayerTable(props) {
  const players = props.players;
  players.sort(function (a, b) {
    return b.elo - a.elo;
  });

  const tableRows = players.map((x) => {
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
  );
}

class EloChangeCalculator {
  constructor(props) {
    this.playerOne = props.playerOne;
    this.playerTwo = props.playerTwo;
    this.kFactor = parseInt((this.playerOne.elo + this.playerTwo.elo) / 100);
    if (this.kFactor < 16) {
      this.kFactor = 16;
    }
  }

  calculateEloChanges() {
    const qpointPlayerOne = this.getQPointsForElo(this.playerOne.elo);
    const qpointPlayerTwo = this.getQPointsForElo(this.playerTwo.elo);
    const winChancePlayerOneAgainstPlayerTwo = this.getWinChanceWithQPoints(qpointPlayerOne, qpointPlayerTwo);
    const winChancePlayerTwoAgainstPlayerOne = this.getWinChanceWithQPoints(qpointPlayerTwo, qpointPlayerOne);
    const eloChanges = {
      'PlayerOneWinAgainstPlayerTwo': {
        'PlayerOneEloGain': parseInt(this.getEloChangeOnWinForWinFactorKFactor(winChancePlayerOneAgainstPlayerTwo, this.kFactor)) || 1,
        'PlayerTwoEloGain': parseInt(this.getEloChangeOnLoseForWinFactorKFactor(winChancePlayerTwoAgainstPlayerOne, this.kFactor)) || -1
      },
      'PlayerTwoWinAgainstPlayerOne': {
        'PlayerOneEloGain': parseInt(this.getEloChangeOnLoseForWinFactorKFactor(winChancePlayerOneAgainstPlayerTwo, this.kFactor)) || -1,
        'PlayerTwoEloGain': parseInt(this.getEloChangeOnWinForWinFactorKFactor(winChancePlayerTwoAgainstPlayerOne, this.kFactor)) || 1
      }
    }
    return eloChanges;
  }
  getQPointsForElo(elo) {
    return 10 ** (elo / 400);
  }

  getWinChanceWithQPoints(qpointPlayerOne, qpointPlayerTwo) {
    return qpointPlayerOne / (qpointPlayerOne + qpointPlayerTwo);
  }

  getEloChangeOnWinForWinFactorKFactor(winFactor, kFactor) {
    const change = kFactor * (1 - winFactor);
    return change;
  }

  getEloChangeOnLoseForWinFactorKFactor(winFactor, kFactor) {
    const change = kFactor * (0 - winFactor);
    return change;
  }
}

function EloChangeDisplay(props) {
  const changes = props.changes;
  const playerOne = props.playerOne;
  const playerTwo = props.playerTwo;
  return (
    <div>
      <span>Results:</span>
      <div>
        <div>{playerOne.name} wins against {playerTwo.name}</div><br />
        <div>{playerOne.name} moves from {playerOne.elo} to {playerOne.elo + changes.PlayerOneWinAgainstPlayerTwo.PlayerOneEloGain} elo (+{changes.PlayerOneWinAgainstPlayerTwo.PlayerOneEloGain})</div><br />
        <div>{playerTwo.name} moves from {playerTwo.elo} to {playerTwo.elo + changes.PlayerOneWinAgainstPlayerTwo.PlayerTwoEloGain} elo ({changes.PlayerOneWinAgainstPlayerTwo.PlayerTwoEloGain})</div><br />
        <br />
        <div>{playerTwo.name} wins against ${playerOne.name}</div><br />
        <div>{playerOne.name} moves from {playerOne.elo} to {playerOne.elo + changes.PlayerTwoWinAgainstPlayerOne.PlayerOneEloGain} elo ({changes.PlayerTwoWinAgainstPlayerOne.PlayerOneEloGain})</div><br />
        <div>{playerTwo.name} moves from {playerTwo.elo} to {playerTwo.elo + changes.PlayerTwoWinAgainstPlayerOne.PlayerTwoEloGain} elo (+{changes.PlayerTwoWinAgainstPlayerOne.PlayerTwoEloGain})</div><br />
        <br />;
    </div>
    </div>
  )
}

class PlayerMatchSimulation extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      changes: {},
      simulationData: props.simulationData,
      simulationExecuted: false
    }
    this.simulatePlayerMatch = this.simulatePlayerMatch.bind(this);
  }

  simulatePlayerMatch() {
    const eloCalculator = new EloChangeCalculator(this.state.simulationData);
    const eloChanges = eloCalculator.calculateEloChanges();
    this.setState({
      changes: eloChanges,
      simulationExecuted: true
    });
  }

  render() {
    return (
      <div>
        <button onClick={this.simulatePlayerMatch}>Simulate Player Match</button>
        {this.state.simulationExecuted ? <EloChangeDisplay changes={this.state.changes} playerOne={this.state.simulationData.playerOne} playerTwo={this.state.simulationData.playerTwo} /> : ''}
      </div>
    )
  }
}

class PlayerMatchAdd extends React.Component {
  constructor(props) {
    super(props);

    this.players = props.players;

    this.state = {
      winner: { id: this.players[0].id, name: this.players[0].name },
      loser: { id: this.players[1].id, name: this.players[1].name },
      submittingForm: false,
      submittingFormError: undefined
    }

    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleWinnerSelectChange = this.handleWinnerSelectChange.bind(this);
    this.handleLoserSelectChange = this.handleLoserSelectChange.bind(this);
  }

  handleSubmit(e) {
    e.preventDefault();
    if (this.state.winner.id === this.state.loser.id || !this.state.winner.id || !this.state.loser.id) {
      alert("Invalid matchup! Player cannot compete against themself: " + this.state.winner.name);
    }
    else {
      const toSendObject = {
        winnerId: this.state.winner.id,
        loserId: this.state.loser.id,
      };
      
      const req = new Request("http://localhost:8000/leaderboard/add/history", { method: "POST", body: JSON.stringify(toSendObject) });
      fetch(req);
    }
  }
  
  handleWinnerSelectChange(e) {
    const target = e.target;
    const value = JSON.parse(target.value);
    this.setState({
      winner: value
    });
  }

  handleLoserSelectChange(e) {
    const target = e.target;
    const value = JSON.parse(target.value);

    this.setState({
      loser: value
    });
  }

  render() {
    const playerSelect = this.players.map((player) => {
      const p = {
        id: player.id,
        name: player.name
      }
      return (
        <option key={p.id} value={JSON.stringify(p)}>{player.name}</option>
      );
    });
    return (
      <form onSubmit={this.handleSubmit}>
        <label>
          Winner:
          <select value={JSON.stringify(this.state.winner)} onChange={this.handleWinnerSelectChange}>
            {playerSelect}
          </select>
        </label>
        <label>
          Loser:
          <select value={JSON.stringify(this.state.loser)} onChange={this.handleLoserSelectChange}>
            {playerSelect}
          </select>
        </label>
        <input type="submit" value="Submit" />
      </form>
    );
  }
}

class App extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      error: null,
      isLoaded: false,
      players: []
    }
    this.simulationData = {
      playerOne: {
        name: 'Player 1',
        elo: 877
      },
      playerTwo: {
        name: 'Player 2',
        elo: 1941
      }
    }
  }


  componentDidMount() {
    fetch('http://localhost:8000')
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
      return <div>Loading...</div>;
    } else {
      console.log(this.state);
      return (
        <div className="App">
          <PlayerTable players={this.state.players} />
          <PlayerMatchSimulation simulationData={this.simulationData} />
          <PlayerMatchAdd players={this.state.players} />
        </div>
      );
    }
  }
}

export default App;
