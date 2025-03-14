import axios from '@/shared/lib/axiosInstance';

export const signIn = async (email: string, password: string): Promise<string> => {
    const response = await axios.post('/auth/signin', { email, password });
    return response.data.data.token; // Возвращаем только токен
};

export const fetchUserProfile = async (token: string): Promise<void> => {
    await axios.get('/user/me', {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
};
