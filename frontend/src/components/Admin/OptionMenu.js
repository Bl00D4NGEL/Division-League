import React from 'react';
import {IMPORT_PLAYER} from './ImportPlayersWidget/ImportPlayersWidget';
import {CREATE_USER} from "./RegisterUserWidget/RegisterUserWidget";
import Label from "../BaseReactComponents/Label/Label";
import RadioButton from "../BaseReactComponents/RadioButton/RadioButton";

export default function OptionsMenu({setOption}) {
    return <div>
        <Label formField={
            <RadioButton name='admin-menu-options' onChangeSetter={setOption} value={IMPORT_PLAYER}/>
        } text='Import players'/>
        <Label formField={
            <RadioButton name='admin-menu-options' onChangeSetter={setOption} value={CREATE_USER}/>
        } text='Create user'/>
    </div>
}
