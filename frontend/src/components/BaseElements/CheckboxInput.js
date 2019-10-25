import React from 'react';

export default function CheckboxInput({value, name, isDisabled}) {
    return <div className="div-checkbox flex">
        <div>
            <input type="checkbox" name={name} value={value} disabled={isDisabled ? 'disabled' : ''}/>
            <span className="checkmark" onClick={(e) => e.currentTarget.parentNode.childNodes[0].click()}/>
        </div>
    </div>;
}