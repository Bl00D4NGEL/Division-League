import PlayerTable from "./components/PlayerTable/PlayerTable";
import HistoryTable from "./components/HistoryTable/HistoryTable";
import AddHistory from "./components/AddHistory/AddHistory";
import AddPlayer from "./components/AddPlayer/AddPlayer";
import Login from "./components/Login/Login";

export default class RouteConfig {
    static config = [
        {
            'path': '/players',
            'component': PlayerTable,
            'name': 'Players',
            'default': true
        },
        {
            'path': '/history',
            'component': HistoryTable,
            'name': 'Histories'
        },
        {
            'path': '/add/history',
            'component': AddHistory,
            'name': 'Add History'
        },
        {
            'path': '/add/player',
            'component': AddPlayer,
            'name': 'Add Player'
        },
        {
            'path': '/login',
            'component': Login,
            'name': 'Login'
        }
    ];

    static get(keys) {
        if (keys === undefined || keys.length === 0) {
            return [];
        }

        return RouteConfig.config.map((route) => {
            return keys.map((key) => {
                return route[key];
            });
        });
    }

    static getDefaultPath() {
        const defaultConfig = RouteConfig.config.filter(d => {return d.default});
        if (defaultConfig !== undefined && defaultConfig.length === 1) {
            return defaultConfig[0].path;
        }
        return '/';
    }
}