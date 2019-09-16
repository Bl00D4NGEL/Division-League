import PlayerSelect from "./PlayerSelect";
import React from "react";

export default function LoserSelect(props) {
    return <PlayerSelect
        defaultValue={props.defaultValue}
        data={{type: 'loser'}}
        label="Loser"
        players={props.players}
        onChange={props.onChange}
    />
}