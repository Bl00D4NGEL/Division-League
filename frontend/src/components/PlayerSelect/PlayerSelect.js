import React from 'react';
import CustomSelect from "../BaseElements/Select";

export default function PlayerSelect(props) {
    console.log("render loser", props);
    const generateOptions = () => {
        return props.players.map((x) => {
            return {
                key: x.id,
                value: x,
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
                    options={generateOptions()}
                />
            </div>
        </div>
    );
}