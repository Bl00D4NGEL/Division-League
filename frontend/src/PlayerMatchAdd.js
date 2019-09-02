import React from 'react';
import PlayerSelect from './PlayerSelect';

export default class PlayerMatchAdd extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            players: props.players,
            winner: props.players[0],
            loser: props.players[1]
        };

        this.loadData = props.reloadData;
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleSelectChange = this.handleSelectChange.bind(this);
    }

    handleSubmit(e) {
        e.preventDefault();
        if (isLoserAndWinnerNotSet(this.state)) {
            alert("Please select a winner and a loser!");
        }
        else if (areOpponentsEqual(this.state)) {
            alert("Invalid matchup! Player cannot compete against themself: " + this.state.winner.name);
        }
        else {
            this.addHistory();
        }
    }

    addHistory() {
        const data = {
            winnerId: this.state.winner.id,
            loserId: this.state.loser.id,
        };
        const url = "http://localhost:8000/history/add";
        this.sendRequestWithDataToUrl(data, url);
    }

    sendRequestWithDataToUrl(data, url) {
        const self = this;
        const req = new Request(url, { method: "POST", body: JSON.stringify(data) });
        fetch(req).then(function (data) {
            return data.json();
        }).then(function (data) {
            const winner = self.state.winner;
            winner.elo += data.changes.winner;

            const loser = self.state.loser;
            loser.elo += data.changes.loser;
            self.setState({
                winner: winner,
                loser: loser,
                changes: data.changes,
            });
            self.loadData();
        });
    }

    handleSelectChange(e) {
        const type = e.target.attributes.winorlose.value;
        const value = e.target.value;
        const change = {
            [type]: JSON.parse(value),
            changes: undefined
        };
        this.setState(change);
    }

    render() {
        return (
            <form onSubmit={this.handleSubmit}>
                <PlayerSelect
                    defaultValue={JSON.stringify(this.state.winner)}
                    type="winner"
                    label="Winner"
                    players={this.state.players}
                    onChange={this.handleSelectChange}
                />

                <PlayerSelect
                    defaultValue={JSON.stringify(this.state.loser)}
                    type="loser"
                    label="Loser"
                    players={this.state.players}
                    onChange={this.handleSelectChange}
                />
                <input type="submit" value="Submit" />
                {
                    this.state.changes !== undefined
                        ? <EloChangeDisplay {...this.state} />
                        : null
                }
            </form>
        );
    }
}

class EloChangeDisplay extends React.Component {
    constructor(props) {
        super(props);
        this.state = { ...props };
    }

    render() {
        if (!this.shouldRender()) {
            return null;
        }
        return (
            <div>
                <span>Results:</span>
                <div>
                    <div>{this.state.winner.name} wins against {this.state.loser.name}</div><br />
                    <div>{this.state.winner.name} moves from {this.state.winner.elo - this.state.changes.winner} to {this.state.winner.elo} elo (+{this.state.changes.winner})</div><br />
                    <div>{this.state.loser.name} moves from {this.state.loser.elo - this.state.changes.loser} to {this.state.loser.elo} elo ({this.state.changes.loser})</div><br />
                </div>
            </div>
        )
    }

    shouldRender() {
        return !(isLoserAndWinnerNotSet(this.state) || areOpponentsEqual(this.state) || this.state.changes === undefined);
    }
}



function isLoserAndWinnerNotSet(state) {
    return !(state.winner.id !== null && state.loser.id !== null);
}

function areOpponentsEqual(state) {
    return (state.winner.id === state.loser.id);
}

