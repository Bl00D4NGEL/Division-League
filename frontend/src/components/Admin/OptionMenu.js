import React from 'react';
import Label from "../BaseElements/Label";
import RadioInput from "../BaseElements/RadioInput";
import {IMPORT_PLAYER} from './ImportPlayersWidget/ImportPlayersWidget';
import {CREATE_USER} from "./RegisterUserWidget/RegisterUserWidget";

export default function OptionsMenu({setOption}) {
    return <div>
        <Label formField={
            <RadioInput name='admin-menu-options' onChangeSetter={setOption} value={IMPORT_PLAYER}/>
        } text='Import players'/>
        <Label formField={
            <RadioInput name='admin-menu-options' onChangeSetter={setOption} value={CREATE_USER}/>
        } text='Create user'/>
    </div>
}