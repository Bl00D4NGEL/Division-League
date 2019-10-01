import React, {useState, useEffect} from 'react';
import Config from '../../Config';
import Table from '../BaseElements/Table';
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Loader from "../BaseElements/Loader";

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
            return [
                entry.id,
                entry.winner.name + ' [+' + entry.winnerEloWin + ']',
                entry.loser.name + ' [' + entry.loserEloLose + ']',
                <a href={entry.proofUrl} target="_blank" rel="noopener noreferrer">Link</a>
            ];
        });
    };

    return <Loader
        isLoaded={isLoaded}
        error={error}
        content={<Table tableHead={['ID', 'Winner', 'Loser', 'Proof']}
                        tableData={generateHistoryTableRows()}/>}
    />

}