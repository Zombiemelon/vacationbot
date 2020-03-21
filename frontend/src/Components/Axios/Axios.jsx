const axios = require('axios');

const apiToken = JSON.parse(localStorage.getItem('api_token'));

const instance = axios.create({
    baseURL: process.env.API_URL,
    timeout: 50000,
    headers: {
        'Access-Control-Allow-Origin': '*',
        'Authorization': `Bearer ${apiToken}`
    },
});

export default instance;