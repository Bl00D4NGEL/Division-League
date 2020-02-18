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
        if (historyData.length === 0) {
            return null;
        }
        return historyData.map(entry => {
            return [
                entry.id,
                new Date(entry.creationTime * 1000).toLocaleString(),
                entry.winner[0].league,
                <div>
                    <span title={entry.winner.map(w => w.name).join(", ")}>{entry.winnerTeamName}</span>
                    <span> [+{entry.winnerEloWin}]</span>
                </div>,
                <div>
                    <span title={entry.loser.map(w => w.name).join(", ")}>{entry.loserTeamName}</span>
                    <span> [{entry.loserEloLose}]</span>
                </div>,
                entry.proofs.map(p => <a key={p} href={p} style={{paddingRight: 5 + 'px'}} target="_blank" rel="noopener noreferrer">Link</a>)
            ];
        });
    };

    return <Loader
        isLoaded={isLoaded}
        error={error}
        content={<Table defaultReverseSort={true} sortable={true} defaultSortKey={0} tableHead={['ID', 'Creation time', 'League', 'Winner', 'Loser', 'Proof']}
                        tableData={generateHistoryTableRows()}/>}
    />

}
