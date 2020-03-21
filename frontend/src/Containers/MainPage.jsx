import React, { useState }  from 'react';
import { ThemeProvider, useThemeValue, ThemeContext} from '../Context/ThContext';
import MainCard from "../Components/Basic/MainCard";

export default function SignIn(props) {
    const [{ palette }] = useThemeValue();

    return (
        <MainCard>
            <div style={{
                gridRow: '4 / span 6',
                gridColumn: '4 / span 6',
                display: 'grid',
                justifyContent: 'center',
                alignItems: 'center',
                background: 'linear-gradient(90deg, #00C9FF 0%, #92FE9D 100%)',
                borderRadius: '5px'
            }}>
                Main Page
            </div>
        </MainCard>
    );
}