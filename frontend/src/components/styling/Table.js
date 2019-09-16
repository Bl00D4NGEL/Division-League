import React from 'react';

export default function CustomTable(props) {
    const { tableHead, tableData } = props;
    return (
        <table>
            <thead>
            <tr>
            {tableHead.map((prop, key) => {
                return (
                    <td key={key}>
                        {prop}
                    </td>
                );
            })}
            </tr>
            </thead>
            <tbody>
            {tableData.map((prop, key) => {
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