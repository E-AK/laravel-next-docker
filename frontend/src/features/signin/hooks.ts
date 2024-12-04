import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { signIn, fetchUserProfile } from './api';

export const useSignin = () => {
    const router = useRouter();
    const [formData, setFormData] = useState({ email: '', password: '' });
    const [errors, setErrors] = useState({ email: '', password: '' });

    const handleChange = (field: 'email' | 'password', value: string) => {
        setFormData((prev) => ({
            ...prev,
            [field]: value,
        }));
    };

    const handleSubmit = async () => {
        try {
            const token = await signIn(formData.email, formData.password);
            localStorage.setItem('token', token);
            router.push('/'); // Редирект после успешного входа
        } catch (error: any) {
            setErrors({
                email: error?.response?.data?.errors?.email || '',
                password: error?.response?.data?.errors?.password || '',
            });
        }
    };

    useEffect(() => {
        const token = localStorage.getItem('token');
        if (token) {
            fetchUserProfile(token)
                .then(() => router.push('/')) // Если пользователь уже авторизован
                .catch(() => localStorage.removeItem('token')); // Если токен недействителен
        }
    }, [router]);

    return { formData, errors, handleChange, handleSubmit };
};
