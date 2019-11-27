import React from 'react';
import './label.scss';

export default function Label({text, formField}) {
    return <div className='label-div'>
        <div>
            <label>{text}</label>
        </div>
        <div>{formField}</div>
    </div>
}
