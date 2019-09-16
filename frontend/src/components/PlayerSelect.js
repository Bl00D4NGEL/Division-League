import React from 'react';
import CustomSelect from "./styling/Select";

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
                <CustomSelect
                    onChange={this.selectChange}
                    defaultValue={this.defaultValue}
                    options={this.generateOptions()}
                />
            </label>
        );
    }

    generateOptions() {
        return this.players;
    }
}
