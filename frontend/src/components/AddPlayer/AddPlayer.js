import React from 'react';
import Config from "../../Config";
import TextInput from "../BaseElements/TextInput";
import Label from "../BaseElements/Label";
import CustomForm from "../BaseElements/Form";
import SubmitInput from "../BaseElements/SubmitInput";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Loader from "../BaseElements/Loader";

export default class AddPlayer extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            name: undefined,
            division: undefined,
            playerId: 0,
            newPlayerData: undefined,
            isLoaded: true,
            error: undefined
        };
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleInputChange = this.handleInputChange.bind(this);
    }

    handleSubmit(e) {
        e.preventDefault();
        if (this.areRequiredFieldsSet()) {
            this.addPlayer();
        } else {
            alert("Please enter all required fields");
        }
    }

    handleInputChange(e) {
        const key = JSON.parse(e.target.attributes.getNamedItem('data').value).type;
        const value = e.target.value;
        const change = {
            [key]: value,
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

    areRequiredFieldsSet() {
        return (
            this.state.name !== undefined &&
            this.state.division !== undefined &&
            parseInt(this.state.playerId) > 0
        );
    }

    addPlayer() {
        this.setState({isLoaded: false});
        const data = {
            name: this.state.name,
            division: this.state.division,
            playerId: this.state.playerId
        };
        new CustomRequest(Config.addPlayerEndPoint(), (result) => {
            this.setState({
                isLoaded: true,
                newPlayerData: JSON.stringify(result)
            })
        }).execute(data);
    }

    generateFormFields() {
        return <div>
            {this.generateLabels()}
            <SubmitInput value="Add Player"/>

            <Loader
                error={this.state.error}
                isLoaded={this.state.isLoaded}
                content={this.state.newPlayerData}
            />
        </div>
    }

    generateLabels() {
        return (
            <div>
                <Label
                    text='Name:'
                    formField={this.generateTextInput('name')}
                />
                <Label
                    text='Division:'
                    formField={this.generateTextInput('division')}
                />
                <Label
                    text='Player ID:'
                    formField={this.generateTextInput('playerId')}
                />
            </div>
        );
    }

    generateTextInput(key) {
        return <TextInput data={JSON.stringify({type: key})} required onChange={this.handleInputChange}/>;
    }
}