import React, {useState} from 'react';
import OptionMenu from "./OptionMenu";
import WidgetMenu from "./WidgetMenu";

export default function Admin() {
    const [option, setOption] = useState(undefined);
    return <div>
        Admin
        <OptionMenu setOption={setOption}/>
        <WidgetMenu option={option}/>
    </div>;
}