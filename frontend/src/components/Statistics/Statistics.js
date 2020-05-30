import React, {useEffect, useState} from 'react';
import Loader from "../BaseReactComponents/Loader/Loader";
import LoadStatisticsService from "../../services/LoadStatisticsService";
import Table from "../BaseReactComponents/Table/Table";

const REQUIRED_GAMES_PER_WEEK = 7;

export default function Statistics() {
    const [statistics, setStatistics] = useState([]);

    useEffect(() => {
        LoadStatisticsService({
            setStatistics, setError: () => {
            }, setIsLoaded: () => {
            }
        });
    }, []);

    const renderStatistics = () => {
        if (0 === statistics.length) {
            return null;
        }
        return statistics.map(statistic => {
            return <div style={{marginBottom: 20 + 'px'}}>
                <div>Date: {statistic.from} - {statistic.to}</div>
                <Table
                    tableHead={['Name', 'Games Played']}
                    tableData={
                        Object.keys(statistic.players).map(player => ([
                            player,
                            renderGamesPlayedStatus(statistic.players[player])
                        ]))
                    }
                />
            </div>
        });
    };

    return <Loader isLoaded={Object.keys(statistics).length} content={renderStatistics()}/>;
}

const renderGamesPlayedStatus = gamesPlayed => {
    if (gamesPlayed === undefined || gamesPlayed === 0) {
        return <div style={{color: 'red'}}>0 / {REQUIRED_GAMES_PER_WEEK}</div>;
    }
    if (gamesPlayed < REQUIRED_GAMES_PER_WEEK) {
        return <div style={{color: 'orange'}}>{gamesPlayed} / {REQUIRED_GAMES_PER_WEEK}</div>;
    }
    return <div style={{color: 'green'}}>{gamesPlayed} / {REQUIRED_GAMES_PER_WEEK}</div>;
};
