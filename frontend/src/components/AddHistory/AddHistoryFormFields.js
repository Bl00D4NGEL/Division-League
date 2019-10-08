import React from 'react';
import WinnerSelect from "../PlayerSelect/WinnerSelect";
import WinnerLoserValidator from "../../helpers/Validators/WinnerLoserValidator";
import DifferentLeagueWarning from "../Warning/DifferentLeagueWarning";
import SelectWinnerAndLoserWarning from "../Warning/SelectWinnerAndLoserWarning";
import EqualPlayerWarning from "../Warning/EqualPlayerWarning";
import LoserSelect from "../PlayerSelect/LoserSelect";
import Label from "../BaseElements/Label";
import TextInput from "../BaseElements/TextInput";
import SubmitInput from "../BaseElements/SubmitInput";
import Loader from "../BaseElements/Loader";
import EloChangeDisplay from "../EloChangeDisplay/EloChangeDisplay";

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
                                      onChange={(e) => setProofUrl(e.target.value)}/>}
            />
        </div>
        {generateWarnings()}
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
}