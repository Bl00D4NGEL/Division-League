import React from 'react';

export default class PlayerSelect extends React.Component {
    constructor(props) {
        super(props);
        this.labelText = props.label;
        this.type = props.type;
        this.players = props.players;
        this.changeHandler = props.onChange;
        this.defaultValue = props.defaultValue;

        this.selectChange = this.selectChange.bind(this);
    }

    selectChange(e) {
        this.setState({
            defaultValue: e.target.value
        });
        this.changeHandler(e);
    }
    render() {
        return (
            <label>
                {this.labelText}:
                <select
                    winorlose={this.type}
                    onChange={this.selectChange}
                    defaultValue={this.defaultValue}
                >
                    {this.generatePlayerSelectOptions()}
                </select>
            </label>
        );
    }

    generatePlayerSelectOptions() {
        return this.players.map((p) => {
            return (
                <option key={p.id} value={JSON.stringify(p)}>{p.name}</option>
            );
        });
    }
}
