import React from 'react';
import Config from "../Config";

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
        const type = e.target.attributes.playerValue.value;
        const value = e.target.value;
        const change = {
            [type]: value,
        };
        this.setState(change);
    }

    render() {
        return (
            <form onSubmit={this.handleSubmit}>
                <label>Name:
                    <input playerValue="name" type="text" required onChange={this.handleInputChange}/>
                </label>
                <label>Division:
                    <input playerValue="division" type="text" required onChange={this.handleInputChange}/>
                </label>
                <label>Player ID:
                    <input playerValue="playerId" type="text" required onChange={this.handleInputChange}/>
                </label>
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