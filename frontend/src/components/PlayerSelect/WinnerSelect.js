import PlayerSelect from "./PlayerSelect";
import React from "react";

export default function WinnerSelect(props) {
    return <PlayerSelect
        defaultValue={props.defaultValue}
        data={{type: 'winner'}}
        label="Winner"
        players={props.players}
        onChange={props.onChange}
    />
}