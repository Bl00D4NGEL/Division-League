import React, {useState} from 'react';
import AddPlayerService from "../../services/AddPlayerService";
import AddPlayerValidator from "../../helpers/Validators/AddPlayerValidator";
import AddPlayerFormFields from "./AddPlayerFormFields";
import CustomForm from "../BaseReactComponents/Form/Form";
import Loader from "../BaseReactComponents/Loader/Loader";
import {useOnChangeSetter} from "../../customHooks/useOnChangeSetter";

export default function AddPlayerForm() {
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);
    const [result, setResult] = useState(undefined);
    const [name, setName] = useOnChangeSetter(undefined);
    const [division, setDivision] = useOnChangeSetter(undefined);
    const [playerId, setPlayerId] = useOnChangeSetter(undefined);
    const [league, setLeague] = useOnChangeSetter(undefined);

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

    const handleSubmit = e => {
        e.preventDefault();
        if (AddPlayerValidator.isValid({name, division, playerId, league})) {
            AddPlayerService({setIsLoaded, setResult, setError, name, division, playerId, league});
        }
    };
    return <div>
        <CustomForm
            onSubmit={handleSubmit}
            formFields={
                <AddPlayerFormFields
                    labelConfig={labelConfig}
                />
            }
        />
        <Loader
            error={error}
            isLoaded={isLoaded}
            content={result}
        />
    </div>
}
