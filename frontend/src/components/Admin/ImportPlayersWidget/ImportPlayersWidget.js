import React, {useState} from 'react';
import LoadPlayerWidget from "./LoadPlayerWidget";
import Label from "../../BaseReactComponents/Label/Label";
import TextInput from "../../BaseReactComponents/TextInput/TextInput";

export default function () {
    const [division, setDivision] = useState('');
    const [defaultLeague, setDefaultLeague] = useState('Unknown');
    return <div>
        <Label text='Division to load' formField={
            <TextInput onChangeSetter={setDivision}/>
        } />
        <Label text='Default league for player' formField={
            <TextInput value={defaultLeague} onChangeSetter={setDefaultLeague}/>
        } />
        <LoadPlayerWidget divisionToLoad={division} defaultLeague={defaultLeague}/>
    </div>
}

export const IMPORT_PLAYER = 'import_player_widget';
