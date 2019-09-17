import React from 'react';

export default function CustomSelect(props) {
    return (
        <select
            onChange={props.onChange}
            defaultValue={props.defaultValue}
            {...props.data}
        >
            {
                props.options.map((x) => {
                    return <option key={x.key} value={x.value}>{x.name}</option>
                })
            }
        </select>
    );
}