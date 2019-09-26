import React from 'react';

export default function CustomSelect({defaultValue, data, options, onChange}) {
    const convertData = (data) => typeof (data) === 'object' ? JSON.stringify(data) : data;
    return (
        <select
            onChange={onChange}
            defaultValue={convertData(defaultValue)}
            {...data}
        >
            {
                options.map((x) => {
                    return <option key={x.key} value={convertData(x.value)}>{x.name}</option>
                })
            }
        </select>
    );
}