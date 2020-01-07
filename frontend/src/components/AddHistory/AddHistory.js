import React from 'react';
import {usePlayers} from "../../customHooks/usePlayers";
import {useLeagueFilter} from "../../customHooks/useLeagueFilter";
import LeagueSelect from "../LeagueSelect/LeagueSelect";
import AddHistoryForm from "./AddHistoryForm";

export default function AddHistory() {
    const {players} = usePlayers();
    const {league, setLeague, leagues, filteredPlayers} = useLeagueFilter({players});

    if (league === undefined && leagues.length > 0) {
        setLeague(leagues[0]);
    }

    return <div>
        <LeagueSelect leagues={leagues} setLeague={setLeague}/>
        <AddHistoryForm players={filteredPlayers}/>
    </div>
}
