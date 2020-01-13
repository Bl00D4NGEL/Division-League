import React, {useEffect, useState} from 'react';
import Button from "../BaseReactComponents/Button/Button";
import Warning from "../Warning/Warning";

const MAX_ELO_DIFFERENCE = 300;
export default function MultiPlayerSelect({RenderComponent, players, setSelectedPlayers}) {
    const [selectedPlayers, _setSelectedPlayers] = useState([]);
    const setSelectedPlayersBoth = val => {
        _setSelectedPlayers(val);
        if (setSelectedPlayers !== undefined) {
            setSelectedPlayers(val);
        }
    };

    const setDefaultPlayer = () => {
        if (players.length > 0 && selectedPlayers.length === 0) {
            setSelectedPlayersBoth([players[0].id]);
        }
    };

    useEffect(setDefaultPlayer, [players]);

    const addPlayerSelect = () => {
        setSelectedPlayersBoth(
            [
                ...selectedPlayers,
                players[0].id
            ]
        );
    };

    const renderSelects = () => {
        return <div style={{display: 'flex'}}>
            {
                selectedPlayers.map((p, i) => <div key={i} style={{margin: '0 20px'}}>
                        <RenderComponent
                            value={p}
                            players={players}
                            onChange={
                                e => {
                                    setSelectedPlayersBoth(
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
                    </div>
                )
            }
        </div>
    };

    const generateWarnings = () => {
        const min = getMinElo(selectedPlayers, players);
        const max = getMaxElo(selectedPlayers, players);

        if (max - min > MAX_ELO_DIFFERENCE) {
            return <Warning
                message={"Elo difference too big. Max elo difference: " + MAX_ELO_DIFFERENCE + " Current difference: " + (max - min)}/>
        } else {
            return <div/>;
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
        Player average Elo = {getAverageElo()}
        {generateWarnings()}
        {renderSelects()}
        <Button onClick={addPlayerSelect} text='+'/>
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
