'use client';

import axios from 'axios';
import { useState, useEffect  } from 'react';
import { redirect } from 'next/navigation';


export default function Signup() {
    const [data, setData] = useState({
        login: null,
        password: null,
        repeat_password: null,
    });

    const [errors, setError] = useState({
        login: '',
        password: '',
        repeat_password: '',
    });

    function setLogin(login) {
        setData(prev => {
            return {
                ...prev,
                login: login,
            }
        })
    }

    function setPassword(password) {
        setData(prev => {
            return {
                ...prev,
                password: password,
            }
        })
    }

    function setRepeatPassword(repeat_password) {
        setData(prev => {
            return {
                ...prev,
                repeat_password: repeat_password,
            }
        })
    }

    let loaded = false;

    useEffect(() => {
        if (loaded) {
            return;
        }

        let token = undefined;

        if (typeof window !== "undefined") {
            token = localStorage.getItem('token');
        }

        if (token !== undefined) {
            axios({
                method: 'get',
                url: `http://localhost:8080/api/user/me`,
                headers: {
                    'Authorization': `Bearer ${token}`,
                },
            })
                .then(function (response) {
                    redirect('/');
                })
        }

        loaded = true;
    }, []);


    function signUp() {
        axios({
            method: 'post',
            url: `http://localhost:8080/api/auth/signup`,
            data: {
                login: data.login,
                password: data.password,
                repeat_password: data.repeat_password,
            }
        })
            .then(function (response) {
                window.localStorage.setItem("token", response.data.data.token);
                redirect('/');
            })
            .catch(function (error) {
                if (error?.message?.includes('NEXT_REDIRECT')) {
                    redirect('/');
                }

                setError(prev => {
                    return {
                        ...prev,
                        login: error?.response?.data?.errors?.login ?? '',
                    }
                });
                setError(prev => {
                    return {
                        ...prev,
                        password: error?.response?.data?.errors?.password ?? '',
                    }
                });
                setError(prev => {
                    return {
                        ...prev,
                        repeat_password: error?.response?.data?.errors?.repeat_password ?? '',
                    }
                });
            })
    }

    return (
        <>
            <div className="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
                <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                    <h2 className="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">
                        Регистрация
                    </h2>
                </div>

                <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                    <form action="#" method="POST" className="space-y-6">
                        <div>
                            <label htmlFor="email" className="block text-sm/6 font-medium text-gray-900">
                                Email
                            </label>
                            <div className="text-sm">
                                <label className="font-semibold text-red-500">
                                    {errors.login}
                                </label>
                            </div>
                            <div className="mt-2">
                                <input
                                    onChange={(event) => setLogin(event.target.value)}
                                    id="email"
                                    name="email"
                                    type="email"
                                    required
                                    autoComplete="email"
                                    className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        <div>
                            <div className="flex items-center justify-between">
                                <label htmlFor="password" className="block text-sm/6 font-medium text-gray-900">
                                Пароль
                                </label>
                            </div>
                            <div className="text-sm">
                                <label className="font-semibold text-red-500">
                                    {errors.password}
                                </label>
                            </div>
                            <div className="mt-2">
                                <input
                                    onChange={(event) => setPassword(event.target.value)}
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autoComplete="current-password"
                                    className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        <div>
                            <div className="flex items-center justify-between">
                                <label htmlFor="password" className="block text-sm/6 font-medium text-gray-900">
                                Повторите пароль
                                </label>
                            </div>
                            <div className="text-sm">
                                <label className="font-semibold text-red-500">
                                    {errors.repeat_password}
                                </label>
                            </div>
                            <div className="mt-2">
                                <input
                                    onChange={(event) => setRepeatPassword(event.target.value)}
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autoComplete="current-password"
                                    className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6"
                                />
                            </div>
                        </div>

                        <div>
                            <button
                                type="button"
                                className="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                onClick={signUp}
                            >
                                Войти
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </>
    )
}