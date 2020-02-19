import React from 'react';
import CustomSelect from "../BaseReactComponents/Select/Select";

export default function PlayerSelect({players, onChange, ...rest}) {
    return <CustomSelect
        {...rest}
        onChange={onChange}
        options={mapPlayersToOptions(players)}
    />
}

const mapPlayersToOptions = players => players.map(p => ({
    key: p.id,
    value: p.id,
    name: `${p.name} (${p.elo})`
}));
