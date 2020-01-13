import React from "react";
import Label from "../BaseReactComponents/Label/Label";
import TextInput from "../BaseReactComponents/TextInput/TextInput";
import SubmitButton from "../BaseReactComponents/SubmitButton/SubmitButton";

export default function AddPlayerFormFields({setName, setDivision, setPlayerId, setLeague}) {
    const labelConfig = [
        {
            text: 'Name',
            key: 'name',
            setter: setName
        },
        {
            text: 'Division',
            key: 'division',
            setter: setDivision
        }, {
            text: 'Player ID',
            key: 'playerId',
            setter: setPlayerId
        }, {
            text: 'League',
            key: 'league',
            setter: setLeague
        }
    ];

    const generateLabels = () => {
        return (
            <div>
                {labelConfig.map((c) => {
                    return <div>
                        <Label
                            text={c.text}
                            formField={generateTextInput(c.key, c.setter)}
                        />
                    </div>
                })}
            </div>
        );
    };

    const generateTextInput = (key, setter) => {
        return <TextInput name={key} required onChange={setter}/>;
    };

    return <div>
        {generateLabels()}
        <SubmitButton value="Add Player"/>
    </div>

}
