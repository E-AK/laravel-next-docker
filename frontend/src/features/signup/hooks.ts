import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { signUp, fetchUserProfile } from './api';

export const useSignup = () => {
    const router = useRouter();
    const [formData, setFormData] = useState({
        email: '',
        password: '',
        repeatPassword: '',
    });
    const [errors, setErrors] = useState({
        email: '',
        password: '',
        repeatPassword: '',
    });

    const handleChange = (field: 'email' | 'password' | 'repeatPassword', value: string) => {
        setFormData((prev) => ({
            ...prev,
            [field]: value,
        }));
    };

    const handleSubmit = async () => {
        try {
            const token = await signUp(formData.email, formData.password, formData.repeatPassword);
            localStorage.setItem('token', token);
            router.push('/'); // Успешная регистрация
        } catch (error: any) {
            setErrors({
                email: error?.response?.data?.errors?.login || '',
                password: error?.response?.data?.errors?.password || '',
                repeatPassword: error?.response?.data?.errors?.repeat_password || '',
            });
        }
    };

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (token) {
            fetchUserProfile(token)
                .then(() => router.push('/')) // Пользователь уже авторизован
                .catch(() => localStorage.removeItem('token')); // Удаление недействительного токена
        }
    }, [router]);

    return { formData, errors, handleChange, handleSubmit };
};
