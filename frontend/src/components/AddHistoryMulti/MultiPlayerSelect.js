import React, {useEffect, useState} from 'react';
import Button from "../BaseReactComponents/Button/Button";

const MAX_ELO_DIFFERENCE = 300;
export default function MultiPlayerSelect({RenderComponent, players}) {
    const [selectedPlayers, setSelectedPlayers] = useState([]);
    const [minElo, setMinElo] = useState(undefined);
    const [maxElo, setMaxElo] = useState(undefined);
    const [filteredPlayers, setFilteredPlayers] = useState([]);

    const setDefaultPlayer = () => {
        if (players.length > 0) {
            setSelectedPlayers([players[0].id]);
        }
    };

    const setMinMaxElo = () => {
        const min = getMinElo(selectedPlayers, players);
        const max = getMaxElo(selectedPlayers, players);
        setMinElo(min - (MAX_ELO_DIFFERENCE - (max - min)));
        setMaxElo(max + (MAX_ELO_DIFFERENCE - (max - min)));
    };


    const filterPlayers = () => {
        setFilteredPlayers(
            players.filter(p => p.elo >= minElo && p.elo <= maxElo)
        );
    };

    useEffect(setDefaultPlayer, [players]);
    useEffect(setMinMaxElo, [selectedPlayers]);
    useEffect(filterPlayers, [minElo, maxElo]);


    const addPlayerSelect = () => {
        setSelectedPlayers(
            [
                ...selectedPlayers,
                filteredPlayers[0].id
            ]
        );
    };

    const renderSelects = () => {
        return <div style={{display: 'flex'}}>
            {
                selectedPlayers.map((p, i) => <div style={{margin: '0 20px'}}>
                        <RenderComponent
                            key={i}
                            value={p}
                            players={filteredPlayers}
                            onChange={
                                e => {
                                    setSelectedPlayers(
                                        selectedPlayers.map((x, j) => {
                                            if (j === i) {
                                                return e.target.value;
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

    return <div>
        Player = {selectedPlayers.join(", ")}
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
