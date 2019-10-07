import React, {useState} from 'react';
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Config from "../../Config";
import WinnerLoserValidator from "../../helpers/Validators/WinnerLoserValidator";
import EloChangeDisplay from "../EloChangeDisplay/EloChangeDisplay";
import WinnerSelect from "../PlayerSelect/WinnerSelect";
import LoserSelect from "../PlayerSelect/LoserSelect";
import TextInput from "../BaseElements/TextInput";
import CustomForm from "../BaseElements/Form";
import Label from "../BaseElements/Label";
import SubmitInput from "../BaseElements/SubmitInput";
import Loader from "../BaseElements/Loader";
import DifferentLeagueWarning from "../Warning/DifferentLeagueWarning";
import SelectWinnerAndLoserWarning from "../Warning/SelectWinnerAndLoserWarning";
import EqualPlayerWarning from "../Warning/EqualPlayerWarning";

export default function AddHistoryForm({players, winner, setWinner, loser, setLoser}) {
    const [proofUrl, setProofUrl] = useState(undefined);
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);
    const [changes, setChanges] = useState(undefined);

    const generateFormFields = () => {
        return <div>
            <div>
                <WinnerSelect
                    defaultValue={winner}
                    players={players}
                    onChange={(e) => setWinner(JSON.parse(e.target.value))}
                />
            </div>
            <div>
                <LoserSelect
                    defaultValue={loser}
                    players={players}
                    onChange={(e) => setLoser(JSON.parse(e.target.value))}
                />
            </div>
            <div style={{marginBottom: 2 + 'vw'}}>
                <Label
                    text='Proof:'
                    formField={<TextInput name='proofUrl' required pattern=".+\..+"
                                          onChange={(e) => setProofUrl(e.target.value)}/>}
                />
            </div>
            {!WinnerLoserValidator.isLeagueEqualFor({winner, loser}) ? <DifferentLeagueWarning/> : null}
            {!WinnerLoserValidator.isLoserAndWinnerSet({winner, loser}) ? <SelectWinnerAndLoserWarning/>: null}
            {WinnerLoserValidator.areOpponentsEqual({winner, loser}) ? <EqualPlayerWarning/> : null}
            <div>
                <SubmitInput value='Add History'/>
            </div>
            <div>
                <Loader
                    error={error}
                    isLoaded={isLoaded}
                    content={<EloChangeDisplay winner={winner} loser={loser} changes={changes}/>}
                />
            </div>
        </div>
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (shouldSubmit()) {
            addHistory();
        }
    };

    const shouldSubmit = () => {
        return WinnerLoserValidator.isLeagueEqualFor({winner, loser})
        && WinnerLoserValidator.isLoserAndWinnerSet({winner, loser})
        && !WinnerLoserValidator.areOpponentsEqual({winner, loser})
    };

    const addHistory = () => {
        setIsLoaded(false);
        const formData = {
            winner: winner.id,
            loser: loser.id,
            proofUrl: proofUrl
        };
        CustomRequest(
            Config.addHistoryEndPoint(),
            (responseData) => {
                if (responseData.status === 'success') {
                    const changes = {
                        'winner': responseData.data.winner.elo - winner.elo,
                        'loser': responseData.data.loser.elo - loser.elo
                    };
                    setWinner(responseData.data.winner);
                    setLoser(responseData.data.loser);
                    setChanges(changes);
                    setIsLoaded(true);
                }
            }, (error) => {
                setIsLoaded(true);
                setError(error);
            },
            formData
        );
    };

    return <CustomForm
        onSubmit={handleSubmit}
        formFields={generateFormFields()}
    />
}