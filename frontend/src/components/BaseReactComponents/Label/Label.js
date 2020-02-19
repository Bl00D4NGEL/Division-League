import React, {Fragment} from 'react';
import './label.scss';

export default function Label({text, formField}) {
    return <Fragment>
        <div>
            <label>{text}</label>
        </div>
        <div>{formField}</div>
    </Fragment>
}
