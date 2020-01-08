import PlayerSelect from "./PlayerSelect";
import React from "react";

export default function WinnerSelect({...props}) {
    return <PlayerSelect
        {...props}
        label="Winner"
    />
}
