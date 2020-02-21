import React, {useState} from 'react';
import AddPlayerService from "../../services/AddPlayerService";
import AddPlayerValidator from "../../helpers/Validators/AddPlayerValidator";
import AddPlayerFormFields from "./AddPlayerFormFields";
import CustomForm from "../BaseReactComponents/Form/Form";
import Loader from "../BaseReactComponents/Loader/Loader";
import {useOnChangeSetter} from "../../customHooks/useOnChangeSetter";
import Button from "../BaseReactComponents/Button/Button";
import AddDeletedPlayerService from "../../services/AddDeletedPlayerService";

export default function AddPlayerForm() {
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);
    const [result, setResult] = useState(undefined);
    const [confirmResult, setConfirmResult] = useState(undefined);
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

    const handleConfirm = e => {
        e.preventDefault();
        if (AddPlayerValidator.isValid({name, division, playerId, league})) {
            AddDeletedPlayerService({setIsLoaded, setResult: setConfirmResult, setError, name, division, playerId, league});
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
        {error && error.message.match(/deleted/) !== null ?
            <div>
                <p>{error.message}</p>
                <p>Please confirm that you want to add him to the league again</p>
                <Button onClick={handleConfirm} text="Confirm"/>
            </div> :
            <Loader
                error={error}
                isLoaded={isLoaded}
                content={result}
            />}
        {confirmResult}
    </div>
}
