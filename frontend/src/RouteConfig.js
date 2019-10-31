import PlayerTable from "./components/PlayerTable/PlayerTable";
import HistoryTable from "./components/HistoryTable/HistoryTable";
import AddHistory from "./components/AddHistory/AddHistory";
import AddPlayer from "./components/AddPlayer/AddPlayer";
import Login from "./components/Login/Login";
import UserRoles from "./UserRoles";
import Admin from "./components/Admin/Admin";

export default class RouteConfig {
    static config = [
        {
            'path': '/players',
            'component': PlayerTable,
            'name': 'Players',
            'requiresLogin': false,
            'default': true
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
            'requiredRole': UserRoles.MODERATOR
        },
        {
            'path': '/add/player',
            'component': AddPlayer,
            'name': 'Add Player',
            'requiresLogin': true,
            'requiredRole': UserRoles.MODERATOR
        },
        {
            'path': '/login',
            'component': Login,
            'name': 'Login',
            'requiresLogin': false,
            'requiredRole': UserRoles.NORMAL,
            'shouldRender': true
        },
        {
            'path': '/admin',
            'component': Admin,
            'name': 'Admin',
            'requiresLogin': true,
            'requiredRole': UserRoles.ADMIN,
        }
    ];

    static getAll() {
        return RouteConfig.config;
    }

    static getDefaultPath() {
        const defaultConfig = RouteConfig.config.filter(d => {return d.default});
        if (defaultConfig !== undefined && defaultConfig.length === 1) {
            return defaultConfig[0].path;
        }
        return '/';
    }
}