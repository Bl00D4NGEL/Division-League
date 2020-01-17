import React from 'react';
import CustomSelect from "../BaseReactComponents/Select/Select";

export default function LeagueSelect({leagues, setLeague}) {
    return <div>
        <div>Select a League</div>
        <CustomSelect
            onChange={setLeague}
            options={generateOptions(leagues)}
        />
    </div>
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
