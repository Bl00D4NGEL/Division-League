import React, {useState} from 'react';
import LoadPlayerWidget from "./LoadPlayerWidget";
import TextInput from "../../BaseElements/TextInput";
import Label from "../../BaseElements/Label";

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