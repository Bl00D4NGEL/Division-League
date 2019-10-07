import React, {useState, useEffect} from 'react';
import Config from '../../Config';
import Table from '../BaseElements/Table';
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Loader from "../BaseElements/Loader";
import WinnerLoserValidator from "../../helpers/Validators/WinnerLoserValidator";

export default function HistoryTable() {
    const [isLoaded, setIsLoaded] = useState(false);
    const [error, setError] = useState(undefined);
    const [historyData, setHistoryData] = useState([]);

    const loadHistories = () => CustomRequest(
        Config.recentHistoryEndpoint(),
        (result) => {
            setHistoryData(result.data);
            setIsLoaded(true);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );

    useEffect(loadHistories, []);

    const generateHistoryTableRows = () => {
        return historyData.map((entry) => {
            const league =
                (WinnerLoserValidator.isLeagueEqualFor({winner: entry.winner, loser: entry.loser}) ?
                    entry.winner.league :
                    'W: ' + entry.winner.league + ' / L: ' + entry.loser.league);
            return [
                entry.id,
                league,
                entry.winner.name + ' [+' + entry.winnerEloWin + ']',
                entry.loser.name + ' [' + entry.loserEloLose + ']',
                <a href={entry.proofUrl} target="_blank" rel="noopener noreferrer">Link</a>
            ];
        });
    };

    return <Loader
        isLoaded={isLoaded}
        error={error}
        content={<Table tableHead={['ID', 'League', 'Winner', 'Loser', 'Proof']}
                        tableData={generateHistoryTableRows()}/>}
    />

}