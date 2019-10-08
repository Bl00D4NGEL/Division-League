import React, {useState} from 'react';
import CustomForm from "../BaseElements/Form";
import AddHistoryService from "../../services/AddHistoryService";
import AddHistoryFormFields from "./AddHistoryFormFields";
import AddHistoryValidator from "../../helpers/Validators/AddHistoryValidator";

export default function AddHistoryForm({players, winner, setWinner, loser, setLoser}) {
    const [proofUrl, setProofUrl] = useState(undefined);
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);
    const [changes, setChanges] = useState(undefined);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (AddHistoryValidator.isValid({winner, loser})) {
            AddHistoryService({setIsLoaded, winner, loser, proofUrl, setError, setLoser, setWinner, setChanges});
        }
    };

    return <CustomForm
        onSubmit={handleSubmit}
        formFields={
            <AddHistoryFormFields
                setProofUrl={setProofUrl}
                setLoser={setLoser}
                setWinner={setWinner}
                winner={winner}
                changes={changes}
                error={error}
                isLoaded={isLoaded}
                players={players}
                loser={loser}
            />
        }
    />
}