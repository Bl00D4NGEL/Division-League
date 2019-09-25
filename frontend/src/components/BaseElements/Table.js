import React, {useState} from 'react';
import classNames from "classnames"
import Sorter from "../../helpers/Sorter/Sorter";

export default function CustomTable({sortable, tableData, tableHead}) {
    const [sortKey, setSortKey] = useState(undefined);
    const [reverseSort, setReverseSort] = useState(false);

    const setSort = (e, key) => {
        if(sortable !== true) {
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
                    const tdClass = classNames({
                        'sort-order': sortKey === key,
                        'reverse': reverseSort
                    });
                    return (
                        <td className={tdClass} onClick={e => setSort(e, key)} key={key}>
                            {prop}
                        </td>
                    );
                })}
            </tr>
            </thead>
            <tbody>
            {sortData().map((prop, key) => {
                return (
                    <tr key={key}>
                        {prop.map((prop, key) => {
                            return (
                                <td key={key}>
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