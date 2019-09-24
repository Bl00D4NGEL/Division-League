import PlayerTable from "./components/PlayerTable/PlayerTable";
import HistoryTable from "./components/HistoryTable/HistoryTable";
import AddHistory from "./components/AddHistory/AddHistory";
import AddPlayer from "./components/AddPlayer/AddPlayer";
import Login from "./components/Login/Login";
import UserRoles from "./UserRoles";

export default class RouteConfig {
    static config = [
        {
            'path': '/players',
            'component': PlayerTable,
            'name': 'Players',
            'default': true,
            'requiresLogin': false,
        },
        {
            'path': '/history',
            'component': HistoryTable,
            'name': 'Histories',
            'requiresLogin': false,
        },
        {
            'path': '/add/history',
            'component': AddHistory,
            'name': 'Add History',
            'requiresLogin': true,
            'requiredRole': UserRoles.moderator
        },
        {
            'path': '/add/player',
            'component': AddPlayer,
            'name': 'Add Player',
            'requiresLogin': true,
            'requiredRole': UserRoles.admin
        },
        {
            'path': '/login',
            'component': Login,
            'name': 'Login',
            'requiresLogin': false,
            'requiredRole': UserRoles.normal
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