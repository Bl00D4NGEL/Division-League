import React, {Component} from 'react';
import classNames from "classnames"
import Sorter from "../../helpers/Sorter/Sorter";

export default class CustomTable extends Component {
    constructor(props) {
        super(props);
        this.state = {
            sortable: props.sortable,
            tableData: props.tableData,
            reverseSort: false,
            ...props
        };

        this.onSort = this.onSort.bind(this);
    }

    render() {
        let {tableHead} = this.props;

        return (
            <table>
                <thead>
                <tr>
                    {tableHead.map((prop, key) => {
                        const tdClass = classNames({
                            'sort-order': this.state.sortKey === key,
                            'reverse': this.state.reverseSort
                        });
                        return (
                            <td className={tdClass} onClick={e => this.onSort(e, key)} key={key}>
                                {prop}
                            </td>
                        );
                    })}
                </tr>
                </thead>
                <tbody>
                {this.sortData().map((prop, key) => {
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

    onSort(e, key) {
        if(!this.state.sortable) {
            return;
        }

        this.setState({
            sortKey: key,
            reverseSort: !this.state.reverseSort
        });
    }

    sortData() {
        return Sorter(this.state.tableData, this.state.sortKey, this.state.reverseSort)
    }
}

