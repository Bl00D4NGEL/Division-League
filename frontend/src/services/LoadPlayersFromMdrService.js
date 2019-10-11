import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function LoadPlayersFromMdrService({division, setIsLoaded, setError, setPlayers}) {
    CustomRequest(
        Config.mdrDivisionEndpoint(division),
        (result) => {
            setPlayers(getMembersFromDivision(result));
            setIsLoaded(true);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );
}

function getMembersFromDivision(division) {
    const players = [];
    getPlayersForRolesFromObject(['commanders', 'vices'], division).forEach(player => players.push(player));

    if (division.teams !== undefined) {
        division.teams
            .map(team => getMembersFromTeam(team))
            .forEach(teamPlayers => teamPlayers.forEach(player => players.push(player)));
    }
    return players;
}

function getMembersFromTeam(team) {
    const players = [];
    getPlayersForRolesFromObject(['tls', 'twoics', 'Members', 'Probation'], team).forEach(player => players.push(player));

    if (team.rosters !== undefined && team.rosters.length > 0) {
        team.rosters
            .map(roster => getMembersFromRoster(roster))
            .forEach(rosterPlayers => rosterPlayers.forEach(player => players.push(player)));
    }
    return players;
}

function getMembersFromRoster(roster) {
    const players = [];
    getPlayersForRolesFromObject(['rls', 'members'], roster).forEach(player => players.push(player));
    return players;
}

function getPlayersForRolesFromObject(roles, object) {
    const players = [];
    roles.forEach((role) => {
        if (object[role] !== undefined) {
            object[role].forEach(player => players.push(player));
        }
    });
    return players;
}