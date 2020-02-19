import React from 'react';
import {usePlayers} from "../../customHooks/usePlayers";
import {useLeagueFilter} from "../../customHooks/useLeagueFilter";
import LeagueSelect from "../LeagueSelect/LeagueSelect";
import AddHistoryMultiForm from "./AddHistoryMultiForm";

export default function AddHistoryMulti() {
    const {players} = usePlayers();
    const {league, setLeague, leagues, filteredPlayers} = useLeagueFilter({players});

    if (league === undefined && leagues.length > 0) {
        setLeague(leagues[0]);
    }

    return <div>
        {leagues.length > 1 ? <LeagueSelect leagues={leagues} setLeague={setLeague}/> : null}
        <AddHistoryMultiForm players={filteredPlayers}/>
    </div>
}
