import React, {useState} from 'react';
import SubmitInput from "../BaseElements/SubmitInput";
import Label from "../BaseElements/Label";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Config from "../../Config";
import TextInput from "../BaseElements/TextInput";
import CustomForm from "../BaseElements/Form";
import Loader from "../BaseElements/Loader";

export default function AddPlayerForm() {
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);
    const [result, setResult] = useState(undefined);
    const [name, setName] = useState(undefined);
    const [division, setDivision] = useState(undefined);
    const [playerId, setPlayerId] = useState(undefined);
    const [league, setLeague] = useState(undefined);

    const generateLabels = () => {
        return (
            <div>
                <div>
                    <Label
                        text='Name:'
                        formField={generateTextInput('name', setName)}
                    />
                </div>
                <div>
                    <Label
                        text='Division:'
                        formField={generateTextInput('division', setDivision)}
                    />
                </div>
                <div>
                    <Label
                        text='Player ID:'
                        formField={generateTextInput('playerId', setPlayerId)}
                    />
                </div>
                <div>
                    <Label
                        text='League:'
                        formField={generateTextInput('league', setLeague)}
                    />
                </div>
            </div>
        );
    };

    const generateTextInput = (key, setter) => {
        return <TextInput name={key} required onChange={(e) => setter(e.target.value)}/>;
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (areRequiredFieldsSet()) {
            addPlayer();
        } else {
            alert("Please enter all required fields");
        }
    };

    const areRequiredFieldsSet = () => {
        return (
            name !== undefined &&
            division !== undefined &&
            parseInt(playerId) > 0 &&
            league !== undefined
        );
    };

    const addPlayer = () => {
        setIsLoaded(false);
        CustomRequest(
            Config.addPlayerEndPoint(), (res) => {
                setResult(JSON.stringify(res));
                setIsLoaded(true);
            }, (error) => {
                setError(error);
                setIsLoaded(true);
            },
            {name, division, playerId, league}
        );
    };


    return <div>
        <CustomForm
            onSubmit={handleSubmit}
            formFields={
                <div>
                    {generateLabels()}
                    <SubmitInput value="Add Player"/>
                </div>
            }
        />
        <Loader
            error={error}
            isLoaded={isLoaded}
            content={result}
        />
    </div>
}