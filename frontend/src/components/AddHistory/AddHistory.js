import React from 'react';
import Config from "../../Config";
import EloChangeDisplay from "../EloChangeDisplay/EloChangeDisplay";
import WinnerSelect from "../PlayerSelect/WinnerSelect";
import LoserSelect from "../PlayerSelect/LoserSelect";
import TextInput from "../BaseElements/TextInput";
import CustomForm from "../BaseElements/Form";
import Label from "../BaseElements/Label";
import SubmitInput from "../BaseElements/SubmitInput";
import WinnerLoserValidator from "../Validators/WinnerLoserValidator";
import CustomRequest from "../CustomRequest/CustomRequest";
import Loader from "../BaseElements/Loader";

export default class AddHistory extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            players: props.players,
            winner: props.players[0],
            loser: props.players[1],
            proofUrl: undefined,
            isLoaded: true,
            error: undefined
        };

        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleProofUrlChange = this.handleProofUrlChange.bind(this);
        this.handleSelectChange = this.handleSelectChange.bind(this);
    }

    handleSubmit(e) {
        e.preventDefault();
        if (WinnerLoserValidator.isLoserAndWinnerNotSet(this.state)) {
            alert("Please select a winner and a loser!");
        } else if (WinnerLoserValidator.areOpponentsEqual(this.state)) {
            alert("Invalid matchup! Player cannot compete against themself: " + this.state.winner.name);
        } else {
            this.addHistory();
        }
    }

    addHistory() {
        this.setState({isLoaded: false});
        const data = {
            winner: this.state.winner.id,
            loser: this.state.loser.id,
            proofUrl: this.state.proofUrl
        };
        new CustomRequest(
            Config.addHistoryEndPoint(),
            (responseData) => {
                const data = responseData.data;
                if (responseData.status === 'success') {
                    const winner = this.state.winner;
                    winner.elo += data.changes.winner;

                    const loser = this.state.loser;
                    loser.elo += data.changes.loser;
                    this.setState({
                        winner: winner,
                        loser: loser,
                        changes: data.changes,
                        isLoaded: true,
                    });

                    this.props.onAdd();
                } else {
                    this.setState({
                        isLoaded: true,
                        error: {
                            message: 'Status = ' + responseData.status
                        }
                    })
                }
            }
        )
            .execute(data);
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
                players={this.props.players}
                onChange={this.handleSelectChange}
            />

            <LoserSelect
                defaultValue={JSON.stringify(this.state.loser)}
                players={this.props.players}
                onChange={this.handleSelectChange}
            />

            <Label
                text='Proof:'
                formField={<TextInput required pattern=".+\..+" onChange={this.handleProofUrlChange}/>}
            />
            <SubmitInput value='Add History'/>
            <Loader
                error={this.state.error}
                isLoaded={this.state.isLoaded}
                content={<EloChangeDisplay {...this.state} />}
            />
        </div>
    }
}