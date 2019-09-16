import React from 'react';
import Config from "../../Config";
import EloChangeDisplay from "../views/EloChangeDisplay/EloChangeDisplay";
import WinnerSelect from "../PlayerSelect/WinnerSelect";
import LoserSelect from "../PlayerSelect/LoserSelect";
import TextInput from "../styling/TextInput";
import Label from "../styling/Label";
import SubmitInput from "../styling/SubmitInput";
import CustomForm from "../styling/Form";

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
        const type = e.target.attributes.getNamedItem('type').value;
        const value = e.target.value;
        const change = {
            [type]: JSON.parse(value),
            changes: undefined
        };
        this.setState(change);
    }

    render() {
        return (
            <CustomForm
                onSubmit={this.handleSubmit}
                formFields={this.generateFormFields()}
            />
        );
    }

    generateFormFields() {
        return <div>
            <WinnerSelect
                defaultValue={JSON.stringify(this.state.winner)}
                players={this.state.players}
                onChange={this.handleSelectChange}
            />

            <LoserSelect
                defaultValue={JSON.stringify(this.state.loser)}
                players={this.state.players}
                onChange={this.handleSelectChange}
            />

            <Label
                text='Proof:'
                formField={<TextInput required pattern="https?://.+\..+" onChange={this.handleProofUrlChange}/>}
            />
            <SubmitInput value='Add History'/>
            {
                this.state.changes !== undefined
                    ? <EloChangeDisplay {...this.state} />
                    : null
            }
        </div>
    }
}

function isLoserAndWinnerNotSet(state) {
    return !(state.winner.id !== null && state.loser.id !== null);
}

function areOpponentsEqual(state) {
    return (state.winner.id === state.loser.id);
}