import React, {useState} from 'react';
import Config from "../../Config";
import TextInput from "../BaseElements/TextInput";
import Label from "../BaseElements/Label";
import CustomForm from "../BaseElements/Form";
import SubmitInput from "../BaseElements/SubmitInput";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Loader from "../BaseElements/Loader";
import FakeLoader from "../BaseElements/FakeLoader";

export default function AddPlayer() {
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);
    const [result, setResult] = useState(undefined);
    const [newPlayerData, _setNewPlayerData] = useState({
        name: undefined,
        division: undefined,
        playerId: undefined
    });

    const setNewPlayerData = (key, value) => {
        let newData = newPlayerData;
        newData[key] = value;
        _setNewPlayerData(newData);
    };

    const generateFormFields = () => {
        return <FakeLoader
            content={
                <div>
                    {generateLabels()}
                    <SubmitInput value="Add Player"/>

                    <Loader
                        error={error}
                        isLoaded={isLoaded}
                        content={result}
                    />
                </div>
            }
        />
    };

    const generateLabels = () => {
        return (
            <div className="add-player">
                <div>
                    <Label
                        text='Name:'
                        formField={generateTextInput('name')}
                    />
                </div>
                <div>
                    <Label
                        text='Division:'
                        formField={generateTextInput('division')}
                    />
                </div>
                <div>
                    <Label
                        text='Player ID:'
                        formField={generateTextInput('playerId')}
                    />
                </div>
            </div>
        );
    };

    const generateTextInput = (key) => {
        return <TextInput name={key} required onChange={(e) => setNewPlayerData(e.target.name, e.target.value)}/>;
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
            newPlayerData.name !== undefined &&
            newPlayerData.division !== undefined &&
            parseInt(newPlayerData.playerId) > 0
        );
    };

    const addPlayer = () => {
        setIsLoaded(false);
        new CustomRequest(Config.addPlayerEndPoint(), (res) => {
            setResult(JSON.stringify(res));
            setIsLoaded(true);
        }, setError).execute(newPlayerData);
    };

    return (
        <CustomForm
            onSubmit={handleSubmit}
            formFields={generateFormFields()}
        />
    );
}