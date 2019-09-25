import React from 'react';
import CustomSelect from "../BaseElements/Select";

export default function PlayerSelect(props) {
    const [defaultValue, setDefaultValue] = React.useState(props.defaultValue);

    const generateOptions = () => {
        return props.players.map((x) => {
            return {
                key: x.id,
                value: JSON.stringify(x),
                name: '[' + x.division + '] ' + x.name + ' (' + x.elo + ')',
            }
        });
    };

    return (
        <div className="custom-select flex">
            <div>
                <label>
                    {props.label}:
                </label>
            </div>
            <div>
                <CustomSelect
                    {...props}
                    onChange={(e) => {setDefaultValue(e.target.value); props.onChange(e)}}
                    defaultValue={defaultValue}
                    options={generateOptions()}
                />
            </div>
        </div>
    );
}