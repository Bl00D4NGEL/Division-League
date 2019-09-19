import React from 'react';
import Config from "../../Config";
import EloChangeDisplay from "../EloChangeDisplay/EloChangeDisplay";
import WinnerSelect from "../PlayerSelect/WinnerSelect";
import LoserSelect from "../PlayerSelect/LoserSelect";
import TextInput from "../BaseElements/TextInput";
import CustomForm from "../BaseElements/Form";
import Label from "../BaseElements/Label";
import SubmitInput from "../BaseElements/SubmitInput";
import WinnerLoserValidator from "../../helpers/Validators/WinnerLoserValidator";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Loader from "../BaseElements/Loader";

export default class AddHistory extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            players: props.players,
            proofUrl: undefined,
            isLoaded: false,
            error: undefined,
            winner: undefined,
            loser: undefined
        };

        new CustomRequest(
            Config.getAllPlayersEndpoint(),
            (result) => {
                this.setState(
                    {
                        players: result.data,
                        isLoaded: true,
                        winner: result.data[0],
                        loser: result.data[1]
                    }
                );
            },
            (error) => {
                this.setState({
                    isLoaded: true,
                    error
                });
            }
        )
            .execute();

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
            <Loader
                isLoaded={this.state.isLoaded}
                error={this.state.error}
                content={
                    <CustomForm
                        onSubmit={this.handleSubmit}
                        formFields={this.generateFormFields()}
                    />
                }
            />
        );
    }

    generateFormFields() {
        return <div>
            <div>
                <WinnerSelect
                    defaultValue={JSON.stringify(this.state.winner)}
                    players={this.state.players}
                    onChange={this.handleSelectChange}
                />
            </div>
            <div>
                <LoserSelect
                    defaultValue={JSON.stringify(this.state.loser)}
                    players={this.state.players}
                    onChange={this.handleSelectChange}
                />
            </div>
            <div style={{marginBottom: 2 + 'vw'}}>
                <Label
                    text='Proof:'
                    formField={<TextInput required pattern=".+\..+" onChange={this.handleProofUrlChange}/>}
                />
            </div>
            <div>
                <SubmitInput value='Add History'/>
            </div>
            <div>
                <Loader
                    error={this.state.error}
                    isLoaded={this.state.isLoaded}
                    content={<EloChangeDisplay {...this.state} />}
                />
            </div>
        </div>
    }
}