import React from 'react';
import WinnerSelect from "../PlayerSelect/WinnerSelect";
import WinnerLoserValidator from "../../helpers/Validators/WinnerLoserValidator";
import DifferentLeagueWarning from "../Warning/DifferentLeagueWarning";
import SelectWinnerAndLoserWarning from "../Warning/SelectWinnerAndLoserWarning";
import EqualPlayerWarning from "../Warning/EqualPlayerWarning";
import LoserSelect from "../PlayerSelect/LoserSelect";
import EloChangeDisplay from "../EloChangeDisplay/EloChangeDisplay";
import Label from "../BaseReactComponents/Label/Label";
import TextInput from "../BaseReactComponents/TextInput/TextInput";
import SubmitButton from "../BaseReactComponents/SubmitButton/SubmitButton";
import Loader from "../BaseReactComponents/Loader/Loader";

export default function AddHistoryFormFields({winner, loser, players, setWinner, setLoser, setProofUrl, error, isLoaded, changes}) {
    const generateWarnings = () => {
        return <div>
            {!WinnerLoserValidator.isLeagueEqualFor({winner, loser}) ? <DifferentLeagueWarning/> : null}
            {!WinnerLoserValidator.isLoserAndWinnerSet({winner, loser}) ? <SelectWinnerAndLoserWarning/> : null}
            {WinnerLoserValidator.areOpponentsEqual({winner, loser}) ? <EqualPlayerWarning/> : null}
        </div>
    };
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
                                      onChangeSetter={setProofUrl}/>}
            />
        </div>
        {generateWarnings()}
        <div>
            <SubmitButton value='Add History'/>
        </div>
        <div>
            <Loader
                error={error}
                isLoaded={isLoaded}
                content={<EloChangeDisplay winner={winner} loser={loser} changes={changes}/>}
            />
        </div>
    </div>
}
