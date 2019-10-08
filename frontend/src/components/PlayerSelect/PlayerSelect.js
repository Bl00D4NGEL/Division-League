import React from 'react';
import CustomSelect from "../BaseElements/Select";
import Label from "../BaseElements/Label";

export default function PlayerSelect({players, label, ...rest}) {
    const generateOptions = () => {
        return players.map((x) => {
            return {
                key: x.id,
                value: x,
                name: '[' + x.league + '] ' + x.name + ' (' + x.elo + ')',
            }
        });
    };

    return (
        <div>
            <Label text={label} formField={
                <CustomSelect
                    {...rest}
                    options={generateOptions()}
                />
            } />
        </div>
    );
}