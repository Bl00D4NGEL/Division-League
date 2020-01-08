import PlayerSelect from "./PlayerSelect";
import React from "react";

export default function LoserSelect({...props}) {
    return <PlayerSelect
        {...props}
        label="Loser"
    />
}
