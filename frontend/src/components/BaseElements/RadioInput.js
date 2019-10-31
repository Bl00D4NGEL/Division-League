import React from 'react';

export default function RadioInput({onChangeSetter, ...props}) {
    return <input {...props} type="radio" onChange={e => onChangeSetter(e.target.value)}/>;
}