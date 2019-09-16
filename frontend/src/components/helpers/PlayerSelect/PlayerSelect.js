import React from 'react';
import CustomSelect from "../../styling/Select";

export default class PlayerSelect extends React.Component {
    constructor(props) {
        super(props);
        this.labelText = props.label;
        this.players = props.players;
        this.onChange = props.onChange;
        this.defaultValue = props.defaultValue;

        this.state = {data: props.data};

        this.selectChange = this.selectChange.bind(this);
    }

    selectChange(e) {
        this.setState({
            defaultValue: e.target.value
        });
        this.onChange(e);
    }

    render() {
        return (
            <label>
                {this.labelText}:
                <CustomSelect
                    {...this.state}
                    onChange={this.selectChange}
                    defaultValue={this.defaultValue}
                    options={this.generateOptions()}
                />
            </label>
        );
    }

    generateOptions() {
        return this.players.map((x) => {
            return {
                key: x.id,
                value: JSON.stringify(x),
                name: x.name,
            }
        });
    }
}
