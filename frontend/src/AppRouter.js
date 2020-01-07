import RouteConfig from "./RouteConfig";
import {BrowserRouter, Redirect, Route} from "react-router-dom";
import React, {useState} from "react";
import UserRoles from "./UserRoles";
import Navigation from "./components/Navigation/Navigation";
import Footer from "./components/Footer/Footer";

export default function AppRouter() {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [user, setUser] = useState({role: UserRoles.normal});

    const generateRoutes = () => {
        return RouteConfig.getAll().map((route) => {
            if (route.shouldRender === true) {
                return <Route key={route.path} path={route.path}
                              render={() => <route.component isLoggedIn={isLoggedIn} setIsLoggedIn={setIsLoggedIn}
                                                             setUserData={setUser}/>}/>;
            }
            return <Route key={route.path} path={route.path} exact component={route.component}/>;
        });
    };

    return <BrowserRouter>
        <Redirect to={RouteConfig.getDefaultPath()}/>
        <div className="main">
            <Navigation isLoggedIn={isLoggedIn} user={user}/>
            <div className="content">
                {generateRoutes()}
            </div>
            <Footer/>
        </div>
    </BrowserRouter>
}
