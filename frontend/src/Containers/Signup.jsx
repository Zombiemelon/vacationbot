import React, {useState} from 'react';
import LockOutlinedIcon from '@material-ui/icons/LockOutlined';
import { ThemeProvider, useThemeValue, ThemeContext} from '../Context/ThContext';
import { UserProvider, useUserValue, UserContext} from '../Context/UserContext';
import MainCard from "../Components/Basic/MainCard";
import Avatar from "@material-ui/core/Avatar";
import Button from '../Components/Basic/Button';
import axios from "../Components/Axios/Axios";
import Input from "../Components/Basic/Input";

export default function SignUp(props) {
    const [{ palette }] = useThemeValue();
    const [{ user }] = useUserValue();
    const [name, setName] = useState();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const login = () => {
        axios.post('api/signup', {
            name,
            email,
            password
        }).then(function (response) {
            console.log(response);
            localStorage.setItem('api_token', JSON.stringify(response.data.api_token));
            localStorage.setItem('id', JSON.stringify(response.data.id));
            localStorage.setItem('email', JSON.stringify(response.data.email));
            localStorage.setItem('roles', JSON.stringify(response.data.roles));
            setTimeout(() => props.history.push("/"), 0.3);
        }).catch(function (error) {
        });
    };

    return (
        <MainCard>
            <div style={{
                display: 'grid',
                justifyContent: 'center',
                alignItems: 'center',
                gridRow: '1 / span 2',
                gridColumn: '4 / span 6'
            }}>
                <Avatar style={{
                    backgroundColor: palette.primary.main
                }}>
                    <LockOutlinedIcon/>
                </Avatar>
            </div>
            <Input style={{
                borderColor: palette.primary.main,
                color: palette.primary.main,
                gridRow: '4 / span 2',
                gridColumn: '2 / span 10'
            }}
                   type='text'
                   placeholder="Name"
                   onBlur={e => {setName(e.target.value)}}
            />
            <Input style={{
                borderColor: palette.primary.main,
                color: palette.primary.main,
                gridRow: '7 / span 2',
                gridColumn: '2 / span 10'
            }}
                   type='text'
                   placeholder="Email"
                   onBlur={e => {setEmail(e.target.value)}}
            />
            <Input style={{
                borderColor: palette.primary.main,
                color: palette.primary.main,
                gridRow: '10 / span 2',
                gridColumn: '2 / span 10'
            }}
                   type='password'
                   placeholder="Password"
                   onBlur={e => {setPassword(e.target.value)}}
            />
            <Button style={{
                backgroundColor: palette.primary.main,
                color: palette.primary.contrastText,
                width: '100%',
                gridRow: '13 / span 2',
                gridColumn: '2 / span 10',
            }} hover={`${palette.primary.dark} !important`}
                active={`${palette.secondary.light} !important`}
                onClick={login}
            >
                Sign Up
            </Button>
        </MainCard>
    );
}
