import React from 'react';
import Label from "../BaseReactComponents/Label/Label";
import CustomSelect from "../BaseReactComponents/Select/Select";

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
