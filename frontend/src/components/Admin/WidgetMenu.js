import React from 'react';
import ImportPlayerWidget, {IMPORT_PLAYER} from './ImportPlayersWidget/ImportPlayersWidget';
import RegisterUserWidget, {CREATE_USER} from "./RegisterUserWidget/RegisterUserWidget";

export default function WidgetMenu({option}) {
    if (option === IMPORT_PLAYER) {
        return <ImportPlayerWidget/>
    }
    if (option === CREATE_USER) {
        return <RegisterUserWidget/>
    }
    return <div/>;
}