import axios from '@/shared/lib/axiosInstance';

export const signUp = async (email: string, password: string, repeatPassword: string): Promise<string> => {
    const response = await axios.post('/auth/signup', {
        email,
        password,
        repeat_password: repeatPassword,
    });
    return response.data.data.token; // Возвращаем токен
};

export const fetchUserProfile = async (token: string): Promise<void> => {
    await axios.get('/user/me', {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
};
