import React from "react";
import Label from "../BaseReactComponents/Label/Label";
import TextInput from "../BaseReactComponents/TextInput/TextInput";
import SubmitButton from "../BaseReactComponents/SubmitButton/SubmitButton";

export default function AddPlayerFormFields({labelConfig}) {
    const generateLabels = () => {
        return (
            <div>
                {labelConfig.map(config => {
                    return <div key={config.key}>
                        <Label
                            text={config.text}
                            formField={generateTextInput(config.key, config.setter)}
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
