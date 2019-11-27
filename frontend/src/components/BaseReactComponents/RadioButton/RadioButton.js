import React from 'react';

export default function RadioButton({onChangeSetter, ...props}) {
    return <input {...props} type="radio" onChange={e => onChangeSetter(e.target.value)}/>;
}
