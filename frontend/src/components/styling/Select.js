import React from 'react';

export default function CustomSelect(props) {
    return (
        <select
            onChange={props.onChange}
            defaultValue={props.defaultValue}
        >
            {
                props.options.map((x) => {
                    return <option key={x.id} value={JSON.stringify(x)}>{x.name}</option>
                })
            }
        </select>
    );
}