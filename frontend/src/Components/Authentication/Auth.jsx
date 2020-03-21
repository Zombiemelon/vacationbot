import axios from '../Axios/Axios';

export default async function Auth () {
    return await axios.get('/api/user');
}