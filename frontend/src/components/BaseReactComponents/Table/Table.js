import React, {useState} from 'react';
import classNames from "classnames"
import Sorter from "../../../helpers/Sorter/Sorter";

export default function CustomTable({sortable, tableData, tableHead, extraClassNames, defaultSortKey}) {
    const [sortKey, setSortKey] = useState(defaultSortKey);
    const [reverseSort, setReverseSort] = useState(false);

    const setSort = (e, key) => {
        if (sortable !== true) {
            return;
        }
        setSortKey(key);
        setReverseSort(!reverseSort);
    };

    const sortData = () => {
        return Sorter(tableData, sortKey, reverseSort)
    };

    return (
        <table>
            <thead>
            <tr>
                {tableHead.map((prop, key) => {
                    let extra = null;
                    if (extraClassNames !== undefined) {
                        extra = extraClassNames[key]
                    }
                    const tdClass = classNames({
                        'sort-order': sortKey === key,
                        'reverse': reverseSort,
                        [extra]: extra !== undefined
                    });
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
