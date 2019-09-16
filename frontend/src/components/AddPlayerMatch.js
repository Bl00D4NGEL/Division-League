import React from 'react';
import PlayerSelect from './PlayerSelect';
import Config from "../Config";
import EloChangeDisplay from "./EloChangeDisplay";

export default class AddPlayerMatch extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            players: props.players,
            winner: props.players[0],
            loser: props.players[1],
            proofUrl: undefined
        };

        this.loadData = props.reloadData;
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleProofUrlChange = this.handleProofUrlChange.bind(this);
        this.handleSelectChange = this.handleSelectChange.bind(this);
    }

    handleSubmit(e) {
        e.preventDefault();
        if (isLoserAndWinnerNotSet(this.state)) {
            alert("Please select a winner and a loser!");
        } else if (areOpponentsEqual(this.state)) {
            alert("Invalid matchup! Player cannot compete against themself: " + this.state.winner.name);
        } else {
            this.addHistory();
        }
    }

    addHistory() {
        const data = {
            winner: this.state.winner.id,
            loser: this.state.loser.id,
            proofUrl: this.state.proofUrl
        };
        this.sendRequestWithDataToUrl(data, Config.addHistoryEndPoint());
    }

    sendRequestWithDataToUrl(data, endpoint) {
        const self = this;
        const req = new Request(endpoint.url(), {method: endpoint.method(), body: JSON.stringify(data)});
        fetch(req).then(function (data) {
            return data.json();
        }).then(function (responseData) {
            const data = responseData.data;
            if (responseData.status === 'success') {
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
            } else {
                // TODO: Set error state
            }
        });
    }

    handleProofUrlChange(e) {
        this.setState({proofUrl: e.target.value});
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
                <label>Proof:
                    <input type="text" required pattern="https?://.+\..+" onChange={this.handleProofUrlChange}/>
                </label>
                <input type="submit" value="Submit"/>
                {
                    this.state.changes !== undefined
                        ? <EloChangeDisplay {...this.state} />
                        : null
                }
            </form>
        );
    }
}

function isLoserAndWinnerNotSet(state) {
    return !(state.winner.id !== null && state.loser.id !== null);
}

function areOpponentsEqual(state) {
    return (state.winner.id === state.loser.id);
}