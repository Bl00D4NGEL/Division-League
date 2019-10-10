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
    ['commanders', 'vices'].map(function (role) {
        if (division[role] !== undefined) {
            division[role].map(player => players.push(player));
        }
    });

    division.teams
        .map(
            team => getMembersFromTeam(team)
        )
        .map(
            teamPlayers => teamPlayers.map(
                player => players.push(player)
            )
        );
    return players;
}

function getMembersFromTeam(team) {
    const players = [];
    ['tls', 'twoics'].map(function (role) {
        if (team[role] !== undefined) {
            team[role].map(player => players.push(player));
        }
    });
    if (team.Members !== undefined) {
        team.Members.map(val => players.push(val));
    }
    if (team.Probation !== undefined) {
        team.Probation.map(val => players.push(val));
    }
    team.rosters
        .map(
            roster => getMembersFromRoster(roster)
        )
        .map(
            rosterPlayers => rosterPlayers.map(
                player => players.push(player)
            )
        );
    return players;
}

function getMembersFromRoster(roster) {
    const players = [];
    ['rls'].map(function (role) {
        if (roster[role] !== undefined) {
            for (let i = 0; i < roster[role].length; i++) {
                players.push(roster[role][i]);
            }
        } else {
            console.log("Role " + role + " is undefined in roster");
        }

    });
    if (roster.members !== undefined) {
        roster.members.map(player => players.push(player));
    }
    return players;
}