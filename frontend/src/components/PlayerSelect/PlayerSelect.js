import React from 'react';
import CustomSelect from "../BaseElements/Select";

export default class PlayerSelect extends React.Component {
    constructor(props) {
        super(props);
        this.labelText = props.label;
        this.players = props.players;
        this.onChange = props.onChange;
        this.defaultValue = props.defaultValue;

        this.state = {
            data: props.data,
            defaultValue: this.defaultValue
        };

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
            <div className="custom-select flex">
                <div>
                    <label>
                        {this.labelText}:
                    </label>
                </div>
                <div>
                    <CustomSelect
                        {...this.state}
                        onChange={this.selectChange}
                        defaultValue={this.defaultValue}
                        options={this.generateOptions()}
                    />
                </div>
            </div>
        );
    }

    generateOptions() {
        return this.players.map((x) => {
            return {
                key: x.id,
                value: JSON.stringify(x),
                name: '[' + x.division + '] ' + x.name + ' (' + x.elo + ')',
            }
        });
    }
}
