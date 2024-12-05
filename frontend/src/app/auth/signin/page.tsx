'use client';

import { Input } from '@/shared/ui/Input';
import { Button } from '@/shared/ui/Button';
import { useSignin } from '@/features/signin/hooks';

export default function SigninPage() {
    const { formData, errors, handleChange, handleSubmit } = useSignin();

    return (
        <div className="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
            <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 className="mt-10 text-center text-2xl font-bold tracking-tight text-gray-900">
                    Аутентификация
                </h2>
            </div>

            <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form className="space-y-6">
                    <Input
                        type="email"
                        value={formData.email}
                        onChange={(value) => handleChange('email', value)}
                        error={errors.email}
                        label="Email"
                        required
                    />
                    <Input
                        type="password"
                        value={formData.password}
                        onChange={(value) => handleChange('password', value)}
                        error={errors.password}
                        label="Пароль"
                        required
                    />
                    <Button onClick={handleSubmit}>Войти</Button>
                </form>
            </div>
        </div>
    );
}
