export default function Sorter(data, sortKey, reverse, sortFunc) {
    if (sortKey === undefined) {
        return data;
    }
    if (sortFunc === undefined) {
        sortFunc = (a, b) => {
            if (reverse) {
                [b, a] = [a, b];
            }
            if (typeof (a[sortKey]) === 'string' || typeof (b[sortKey]) === 'string') {
                return a[sortKey].localeCompare(b[sortKey]);
            }
            else if (typeof (a[sortKey]) === 'object' && typeof (b[sortKey]) === 'object') {
                return a[sortKey].key.localeCompare(b[sortKey].key);
            }
            return (a[sortKey] > b[sortKey]) ? -1 : 1;
        }
    }
    return data.sort((a, b) => sortFunc(a, b, sortKey, reverse));
}