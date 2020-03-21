import React from 'react';
import { Route, Redirect } from "react-router-dom";

export default function ProtectedRoute ({component: Component, allowedRoles: allowedRoles, ...rest}) {
    const roles = (JSON.parse(window.localStorage.getItem('roles')));
    let isAuthorized = false;
    if(roles) {
        roles.forEach((role) => {
            if(
                allowedRoles.includes(role)
            ) {
                isAuthorized = true;
            }
        });
    }

    console.log(isAuthorized);
    return(
        <Route {...rest} render={
            (props) => {
                if(isAuthorized) {
                    return <Component {...props}/>
                } else {
                    return <Redirect to="/signin" />
                }
            }
        }/>
    )
}