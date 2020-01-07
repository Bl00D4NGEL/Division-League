import React from 'react';
import CustomSelect from "../BaseReactComponents/Select/Select";

export default function LeagueSelect({leagues, setLeague}) {
    return <CustomSelect
        onChange={setLeague}
        options={generateOptions(leagues)}
    />
}

const generateOptions = leagues => {
    return leagues.map((x, i) => {
        return {
            key: i,
            value: x,
            name: x
        }
    });
};
