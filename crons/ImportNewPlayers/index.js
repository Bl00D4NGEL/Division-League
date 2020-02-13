#!/usr/bin/env node

// Since our dev-localhost has a self-signed cert (symfony local server)
// we need to disable checking for the cert
process.env["NODE_TLS_REJECT_UNAUTHORIZED"] = 0;

import LoadPlayersFromMdrService from "./modules/LoadPlayersFromMdrService.js";
import LoadPlayersService from "./modules/LoadPlayersService.js";
import AddPlayerService from "./modules/AddPlayerService.js";

const DEFAULT_LEAGUE = 'Trial';
const DEFAULT_DIVISION = 'XIX';

const loadPlayers = () => {
    return new Promise((resolve, reject) => {
        LoadPlayersService()
            .then(players => resolve({players}))
            .catch(error => reject(error));
    });
};

const loadMembers = () => {
    return new Promise((resolve, reject) => {
        LoadPlayersFromMdrService({division: DEFAULT_DIVISION})
            .then(members => resolve({members}))
            .catch(error => reject(error));
    });
};

const addMissingPlayers = async (data) => {
    const {members} = data[0];
    const {players} = data[1];
    const playerNames = players.map(p => p.name);
    const promises = members
        .filter(m => !playerNames.includes(m.name))
        .map(p => addMissingPlayer(p));

    Promise.all(promises).then(() => console.log("Added all players"));
};

const addMissingPlayer = async player => {
    AddPlayerService({
        name: player.name,
        division: player.division.replace('DI-', ''),
        playerId: player.id,
        league: DEFAULT_LEAGUE
    })
        .catch(error => console.error("Adding player failed: ", error))
};

Promise.all([loadMembers(), loadPlayers()]).then(addMissingPlayers);
