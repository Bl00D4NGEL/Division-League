import React, {useState} from 'react';
import CustomForm from "../BaseElements/Form";
import Loader from "../BaseElements/Loader";
import AddPlayerService from "../../services/AddPlayerService";
import AddPlayerValidator from "../../helpers/Validators/AddPlayerValidator";
import AddPlayerFormFields from "./AddPlayerFormFields";

export default function AddPlayerForm() {
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);
    const [result, setResult] = useState(undefined);
    const [name, setName] = useState(undefined);
    const [division, setDivision] = useState(undefined);
    const [playerId, setPlayerId] = useState(undefined);
    const [league, setLeague] = useState(undefined);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (AddPlayerValidator.isValid({name, division, playerId, league})) {
            AddPlayerService({setIsLoaded, setResult, setError, name, division, playerId, league});
        } else {
            alert("Please enter all required fields");
        }
    };
    return <div>
        <CustomForm
            onSubmit={handleSubmit}
            formFields={
                <AddPlayerFormFields
                    setDivision={setDivision}
                    setLeague={setLeague}
                    setName={setName}
                    setPlayerId={setPlayerId}
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