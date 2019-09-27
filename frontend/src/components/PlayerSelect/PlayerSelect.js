import React from 'react';
import CustomSelect from "../BaseElements/Select";

export default function PlayerSelect({players, label, ...rest}) {
    const generateOptions = () => {
        return players.map((x) => {
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
                    {label}:
                </label>
            </div>
            <div>
                <CustomSelect
                    {...rest}
                    options={generateOptions()}
                />
            </div>
        </div>
    );
}