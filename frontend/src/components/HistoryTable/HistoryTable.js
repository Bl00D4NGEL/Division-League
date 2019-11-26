import React, {useState, useEffect} from 'react';
import LoadHistoriesService from "../../services/LoadHistoriesService";
import Loader from "../BaseReactComponents/Loader/Loader";
import Table from "../BaseReactComponents/Table/Table";

export default function HistoryTable() {
    const [isLoaded, setIsLoaded] = useState(false);
    const [error, setError] = useState(undefined);
    const [historyData, setHistoryData] = useState([]);

    useEffect(() => LoadHistoriesService({setIsLoaded, setError, setHistoryData}), []);

    const generateHistoryTableRows = () => {
        return historyData.map((entry) => {
            return [
                entry.id,
                entry.winner.league,
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
