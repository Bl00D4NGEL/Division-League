import React from 'react';
import './checkbox.scss';

export default function Checkbox({defaultChecked, description, value, name, isDisabled, onClick}) {
    const triggerCheckbox = (e) => {
        if (!['INPUT', 'LABEL'].includes(e.target.nodeName)) {
            e.currentTarget.childNodes[0].childNodes[0].click()
        }
    };

    return <div className="flex">
        <div className="div-checkbox" onClick={triggerCheckbox}>
            <div>
                <input type="checkbox" id={'checkbox-' + name} defaultChecked={defaultChecked} name={name} value={value}
                       onClick={onClick} disabled={isDisabled ? 'disabled' : ''}/>
                <label htmlFor={'checkbox-' + name}/>
            </div>
        </div>
        <div className='vertical-center'>
            <span style={{cursor: 'pointer'}} onClick={() => document.getElementById('checkbox-' + name).click()}>{description}</span>
        </div>
    </div>;
}

