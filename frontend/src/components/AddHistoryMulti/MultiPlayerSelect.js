import React, {useEffect} from 'react';
import Button from "../BaseReactComponents/Button/Button";
import Warning from "../Warning/Warning";

const MAX_ELO_DIFFERENCE = 300;
export default function MultiPlayerSelect({RenderComponent, players, setSelectedPlayers, selectedPlayers}) {
    const setDefaultPlayer = () => {
        if (players.length > 0 && selectedPlayers.length === 0) {
            setSelectedPlayers([players[0].id]);
        }
    };

    useEffect(setDefaultPlayer, [players]);

    const addPlayerSelect = () => {
        setSelectedPlayers(
            [
                ...selectedPlayers,
                players[0].id
            ]
        );
    };

    const removePlayerSelect = i => {
        setSelectedPlayers(
            selectedPlayers.filter((p, j) => i !== j)
        );
    };

    const renderSelects = () => {
        return <div style={{display: 'flex'}}>
            {
                selectedPlayers.map((p, i) => <div key={i} style={{margin: '0 20px 0 0'}}>
                        <RenderComponent
                            value={p}
                            players={players.sort((a,b) => a.name.localeCompare(b.name))}
                            onChange={
                                e => {
                                    setSelectedPlayers(
                                        selectedPlayers.map((x, j) => {
                                            if (j === i) {
                                                return parseInt(e.target.value);
                                            }
                                            return x;
                                        })
                                    );
                                }
                            }
                        />
                    {selectedPlayers.length > 1 ? <Button text="Remove Player" onClick={() => removePlayerSelect(i)}/> : <div/>}
                    </div>
                )
            }
        </div>
    };

    const generateWarnings = () => {
        const min = getMinElo(selectedPlayers, players);
        const max = getMaxElo(selectedPlayers, players);

        const warnings = [];
        if (max - min > MAX_ELO_DIFFERENCE) {
            warnings.push(
                <Warning key='ELO_DIFFERENCE'
                    message={"Elo difference too big. Max elo difference: " + MAX_ELO_DIFFERENCE + " Current difference: " + (max - min)}/>
            );
        }
        if (selectedPlayers.length === 0) {
            warnings.push(
                <Warning key='NO_0_PLAYERS'
                    message={"You cannot have 0 players. Please add at least one player to proceed"}/>
            );
        }

        if (warnings.length === 0) {
            return <div/>;
        } else {
            return warnings;
        }
    };

    const getAverageElo = () => {
        if (selectedPlayers.length === 0) {
            return 0;
        }
        return Math.round(selectedPlayers.reduce((prev, curr) => {
            const player = getPlayerById(players, parseInt(curr));
            if (prev === undefined) {
                return player.elo;
            }
            return parseInt(prev) + parseInt(player.elo);
        }, undefined) / selectedPlayers.length);
    };

    return <div>
        <div>
            Player average Elo = {getAverageElo()}
            <Button style={{marginLeft: 10 + 'px'}} onClick={addPlayerSelect} text='Add Player'/>
        </div>
        {generateWarnings()}
        {renderSelects()}
    </div>
}

const getPlayerById = (players, id) => {
    return players.filter(p => p.id === id)[0];
};

const getMinElo = (selectedPlayers, players) => selectedPlayers.reduce((prev, curr) => {
    const player = getPlayerById(players, parseInt(curr));
    if (prev === undefined || prev > player.elo) {
        return player.elo;
    }
    return prev;
}, undefined);

const getMaxElo = (selectedPlayers, players) => selectedPlayers.reduce((prev, curr) => {
    const player = getPlayerById(players, parseInt(curr));
    if (prev === undefined || prev < player.elo) {
        return player.elo;
    }
    return prev;
}, 0);
