import React from 'react';
import Config from "../Config";
import TextInput from "./styling/TextInput";
import Label from "./styling/Label";

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
        const key = e.target.attributes.getNamedItem('key').value;
        const value = e.target.value;
        const change = {
            [key]: JSON.parse(value),
            changes: undefined
        };
        this.setState(change);
    }

    render() {
        return (
            <form onSubmit={this.handleSubmit}>
                <Label
                    text='Name:'
                    formField={<TextInput data={{key: 'name'}} required onChange={this.handleInputChange}/>}
                />
                <Label
                    text='Division:'
                    formField={<TextInput data={{key: 'division'}} required onChange={this.handleInputChange}/>}
                />
                <Label
                    text='Player ID:'
                    formField={<TextInput data={{key: 'playerId'}} required onChange={this.handleInputChange}/>}
                />
                <input type="submit" value="Add Player"/>
            </form>
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
}