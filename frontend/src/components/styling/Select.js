import React from 'react';

export default function CustomSelect(props) {
    console.log(props, props.data);
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