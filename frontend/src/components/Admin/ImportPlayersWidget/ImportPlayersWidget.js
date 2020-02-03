import React from 'react';
import LoadPlayerWidget from "./LoadPlayerWidget";
import Label from "../../BaseReactComponents/Label/Label";
import TextInput from "../../BaseReactComponents/TextInput/TextInput";
import {useOnChangeSetter} from "../../../customHooks/useOnChangeSetter";

export default function () {
    const [division, setDivision] = useOnChangeSetter('');
    const [defaultLeague, setDefaultLeague] = useOnChangeSetter('Unknown');
    return <div>
        <Label text='Division to load' formField={
            <TextInput onChange={setDivision}/>
        } />
        <Label text='Default league for player' formField={
            <TextInput value={defaultLeague} onChange={setDefaultLeague}/>
        } />
        <LoadPlayerWidget divisionToLoad={division} defaultLeague={defaultLeague}/>
    </div>
}

export const IMPORT_PLAYER = 'import_player_widget';
