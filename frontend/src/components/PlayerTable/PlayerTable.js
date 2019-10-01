import React, {useState, useEffect} from 'react';
import Table from "../BaseElements/Table";
import CustomRequest from "../../helpers/CustomRequest/CustomRequest";
import Config from "../../Config";
import Loader from "../BaseElements/Loader";

export default function PlayerTable() {
    const [isLoaded, setIsLoaded] = useState(false);
    const [error, setError] = useState(undefined);
    const [players, setPlayers] = useState([]);

    const loadPlayerData = () => CustomRequest(
        Config.getAllPlayersEndpoint(),
        (result) => {
            setIsLoaded(true);
            setPlayers(result.data);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );
    useEffect(loadPlayerData, []);


    const getWinRate = (entry) => {
        if (parseInt(entry.wins) === 0) {
            return 0;
        }
        return (parseInt(entry.wins) / (parseInt(entry.wins) + parseInt(entry.loses)) * 100).toPrecision(4);
    };

    const generateRows = () => {
        return players.map((entry) => {
            return [
                <a key={entry.name} target="_blank" rel="noopener noreferrer"
                   href={"https://di.community/profile/" + entry.playerId + "-" + entry.name}>{entry.name}</a>,
                entry.elo,
                entry.division,
                entry.wins,
                entry.loses,
                getWinRate(entry) + ' %'
            ];
        });
    };

    return <Loader
        isLoaded={isLoaded}
        error={error}
        content={
            <Table
                sortable={true}
                sortKey={1}
                tableHead={['Player', 'Elo', 'Division', 'Wins', 'Loses', 'Win rate']}
                extraClassNames={{2: 'pw-hide'}}
                tableData={generateRows()}
            />
        }
    />
}