import React from 'react';
import Config from "../../Config";
import TextInput from "../BaseElements/TextInput";
import Label from "../BaseElements/Label";
import CustomForm from "../BaseElements/Form";
import SubmitInput from "../BaseElements/SubmitInput";
import CustomRequest from "../CustomRequest/CustomRequest";

export default class AddPlayer extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            name: undefined,
            division: undefined,
            playerId: 0
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
        const data = {
            name: this.state.name,
            division: this.state.division,
            playerId: this.state.playerId
        };
        new CustomRequest(Config.addPlayerEndPoint()).execute(data);
    }

    generateFormFields() {
        return <div>
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
            <SubmitInput value="Add Player"/>
        </div>
    }

    generateTextInput(key) {
        return <TextInput data={JSON.stringify({type: key})} required onChange={this.handleInputChange}/>;
    }
}