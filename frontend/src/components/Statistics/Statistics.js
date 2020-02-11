import React, {useEffect, useState} from 'react';
import Loader from "../BaseReactComponents/Loader/Loader";
import LoadStatisticsService from "../../services/LoadStatisticsService";
import Table from "../BaseReactComponents/Table/Table";
import {usePlayers} from "../../customHooks/usePlayers";

const REQUIRED_GAMES_PER_WEEK = 4;

export default function Statistics() {
    const [statistics, setStatistics] = useState({});
    const {players} = usePlayers();

    useEffect(() => {
        LoadStatisticsService({
            setStatistics, setError: () => {
            }, setIsLoaded: () => {
            }
        });
    }, []);

    const renderStatistics = () => {
        const groupedStatistics = {};
        for (const player in statistics) {
            const dates = statistics[player];
            for (const date in dates) {
                if (!groupedStatistics[date]) {
                    groupedStatistics[date] = {};
                }
                groupedStatistics[date][player] = statistics[player][date];
            }
        }
        return Object.keys(groupedStatistics).sort().reverse().map(date => {
            return <div style={{marginBottom: 20 + 'px'}}>
                <div>Date: {date.split(':')[0]} - {date.split(':')[1]}</div>
                <Table
                    tableHead={['Name', 'Games Played']}
                    tableData={
                        players
                            .map(p => {
                                if (undefined === groupedStatistics[date][p.name]) {
                                    groupedStatistics[date][p.name] = 0;
                                }
                                return p.name;
                            })
                            .sort((a, b) => groupedStatistics[date][a] < groupedStatistics[date][b] ? 1 : -1)
                            .map(player => {
                                return [
                                    player,
                                    renderGamesPlayedStatus(groupedStatistics[date][player])
                                ];
                            })
                    }
                />
            </div>
        });
    };

    const renderGamesPlayedStatus = gamesPlayed => {
        if (gamesPlayed === undefined || gamesPlayed === 0) {
            return <div style={{color: 'red'}}>0 / {REQUIRED_GAMES_PER_WEEK}</div>;
        }
        if (gamesPlayed < REQUIRED_GAMES_PER_WEEK) {
            return <div style={{color: 'orange'}}>{gamesPlayed} / {REQUIRED_GAMES_PER_WEEK}</div>;
        }
        return <div style={{color: 'green'}}>{gamesPlayed} / {REQUIRED_GAMES_PER_WEEK}</div>;
    };

    return <Loader isLoaded={Object.keys(statistics).length} content={renderStatistics()}/>;
}
