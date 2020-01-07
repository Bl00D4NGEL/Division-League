import React from 'react';
import Label from "../BaseReactComponents/Label/Label";
import CustomSelect from "../BaseReactComponents/Select/Select";

export default function PlayerSelect({players, label, onChange, ...rest}) {
    return <Label text={label} formField={
        <CustomSelect
            {...rest}
            onChange={onChange}
            options={mapPlayersToOptions(players)}
        />
    }/>
}

const mapPlayersToOptions = players => players.map(p => ({
    key: p.id,
    value: p.id,
    name: `${p.name} (${p.elo})`
}));
