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
        return historyData.map(entry => (
                [
                    entry.id,
                    new Date(entry.creationTime * 1000).toLocaleString(),
                    entry.winner[0].league,
                    <div>
                        {
                            entry.winner.map(formatPlayer)
                        }
                    </div>,
                    <div>
                        {
                            entry.loser.map(formatPlayer)
                        }
                    </div>,
                    entry.isSweep ? '✓' : 'X',
                    entry.proofs.map(p => <a key={p} href={p} style={{paddingRight: 5 + 'px'}} target="_blank"
                                             rel="noopener noreferrer">Link</a>)
                ]
            )
        );
    };

    return <Loader
        isLoaded={isLoaded}
        error={error}
        content={<Table
            defaultReverseSort={true}
            sortable={true}
            defaultSortKey={0}
            tableHead={['ID', 'Creation time', 'League', 'Winner', 'Loser', 'Sweep?', 'Proof']}
            tableData={generateHistoryTableRows()}
        />}
    />

}

const formatPlayer = p => {
    const label = p.name + ' [' + (p.eloChange > 0 ? '+' : '') + p.eloChange + ']';
    return (
        <span key={p.name} title={label}>{label}&nbsp;</span>
    )
};
