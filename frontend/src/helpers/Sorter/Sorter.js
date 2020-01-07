export default function Sorter(data, sortKey, reverse = false, sortFunc = defaultSort) {
    return sortKey === undefined || !Array.isArray(data) ? data : data.sort((a, b) => sortFunc(a, b, sortKey, reverse));
}

export const defaultSort = (a, b, sortKey, reverse) => {
    if (nullOrUndefined(a[sortKey]) || nullOrUndefined(b[sortKey])) {
        return 0;
    }
    if (reverse) {
        [b, a] = [a, b];
    }
    if (isNumber(a[sortKey]) && isNumber(b[sortKey])) {
        return compareInt(a[sortKey], b[sortKey]);
    }
    if (isObject(a[sortKey]) && isObject(b[sortKey])) {
        if (hasSortBy(a[sortKey]) && hasSortBy(b[sortKey])) {
            return compareObjectSortBy(a[sortKey], b[sortKey]);
        }
        return defaultSort(a[sortKey], b[sortKey], 'key');
    }
    return compareString(a[sortKey], b[sortKey]);
};

const nullOrUndefined = value => value === null || value === undefined;

const isNumber = (value) => !isNaN(parseInt(value));
const isObject = (value) => typeof value === 'object';
const hasSortBy = (object) => object.props !== undefined && object.props.sortBy !== undefined;

export const compareInt = (val1, val2) => val1 < val2 ? -1 : 1;
export const compareString = (val1, val2) => val1.localeCompare(val2);
export const compareObjectSortBy = (val1, val2) => defaultSort(val1.props, val2.props, 'sortBy');
