import RouteConfig from "./RouteConfig";
import {BrowserRouter as Router, NavLink, Redirect, Route} from "react-router-dom";
import React from "react";
import UserRoles from "./UserRoles";
import Navigation from "./components/Navigation/Navigation";

export default function AppRouter() {
    const [isLoggedIn, setIsLoggedIn] = React.useState(false);
    const [user, setUser] = React.useState({role: UserRoles.normal});

    const generateRoutes = () => {
        return RouteConfig.getAll().map((route) => {
            if (route.shouldRender === true) {
                return <Route key={route.path} path={route.path} exact
                              render={() => <route.component isLoggedIn={isLoggedIn} setIsLoggedIn={setIsLoggedIn}
                                                             setUserData={setUser}/>}/>;
            }
            return <Route key={route.path} path={route.path} exact component={route.component}/>;
        });
    };

    return <Router>
        <Redirect to={RouteConfig.getDefaultPath()}/>
        <div className="main">
            <Navigation isLoggedIn={isLoggedIn} user={user}/>
            <div>
                {generateRoutes()}
            </div>
        </div>
    </Router>
}
