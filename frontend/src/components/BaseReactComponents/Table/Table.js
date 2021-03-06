import React, {useState} from 'react';
import classNames from "classnames"
import Sorter from "../../../helpers/Sorter/Sorter";

export default function CustomTable({sortable, tableData, tableHead, extraClassNames, defaultSortKey, defaultReverseSort = false}) {
    const [sortKey, setSortKey] = useState(defaultSortKey);
    const [reverseSort, setReverseSort] = useState(defaultReverseSort);

    const setSort = (e, key) => {
        if (sortable !== true) {
            return;
        }
        setSortKey(key);
        setReverseSort(!reverseSort);
    };

    const sortData = () => {
        return Sorter(tableData, sortKey, reverseSort) || [];
    };

    return (
        <table>
            <thead>
            <tr>
                {tableHead.map((prop, key) => {
                    const classes = {
                        'sort-order': sortKey === key,
                        'reverse': reverseSort,
                    };

                    if (extraClassNames !== undefined) {
                        classes[extraClassNames[key]] = extraClassNames[key]
                    }

                    const tdClass = classNames(classes);
                    return (
                        <th className={tdClass} onClick={e => setSort(e, key)} key={key}>
                            {prop}
                        </th>
                    );
                })}
            </tr>
            </thead>
            <tbody>
            {sortData().map((prop, key) => {
                return (
                    <tr key={key}>
                        {prop.map((prop, key) => {
                            const extra = extraClassNames !== undefined ? extraClassNames[key] : null;
                            return (
                                <td key={key} className={extra}>
                                    {prop}
                                </td>
                            );
                        })}
                    </tr>
                );
            })}
            </tbody>
        </table>
    );
}
