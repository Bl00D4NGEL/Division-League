import {useState} from 'react';

export const useOnChangeSetter = (defaultValue, preSetterFunc) => {
    const [val, _setVal] = useState(defaultValue);

    const setVal = val => {
        if (preSetterFunc !== undefined) {
            _setVal(preSetterFunc(val));
        } else {
            _setVal(val);
        }
    };

    const onChangeSetter = e => {
        if (typeof e === 'object' && e.target !== undefined) {
            setVal(e.target.value);
        } else {
            setVal(e);
        }
    };
    return [val, onChangeSetter];
};
