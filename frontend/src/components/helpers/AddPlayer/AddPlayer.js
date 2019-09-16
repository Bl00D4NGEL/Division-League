import React from 'react';
import Config from "../../../Config";
import TextInput from "../../styling/TextInput";
import Label from "../../styling/Label";
import SubmitInput from "../../styling/SubmitInput";
import CustomForm from "../../styling/Form";

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
        debugger;
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
        this.sendRequestWithDataToUrl(data, Config.addPlayerEndPoint());
    }


    sendRequestWithDataToUrl(data, endpoint) {
        const req = new Request(endpoint.url(), {method: endpoint.method(), body: JSON.stringify(data)});
        fetch(req).then(function (data) {
            return data.json();
        }).then(function (responseData) {
            console.log(responseData);
        });
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