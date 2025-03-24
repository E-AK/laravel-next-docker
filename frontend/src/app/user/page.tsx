'use client';

import { useEffect, useState } from 'react';
import Image from 'next/image';
import axios from '@/shared/lib/axiosInstance';

export default function AvatarUpload() {
    const [image, setImage] = useState<string | null>(null);
    const [file, setFile] = useState<File | null>(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null;
        if (!token) return;

        axios.get('http://localhost/api/avatar', {
            headers: { 'Authorization': `Bearer ${token}` },
        })
            .then(response => setImage('http://localhost/avatar/' + response?.data?.data?.path))
            .catch(() => setError('Failed to load avatar'));

        console.log(image);
    }, []);

    const handleImageChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const selectedFile = event.target.files?.[0];
        if (selectedFile) {
            const validTypes = ['image/png', 'image/jpeg'];
            if (!validTypes.includes(selectedFile.type)) {
                setError('Invalid file format. Only PNG, JPG, and JPEG are allowed.');
                return;
            }

            setError(null);
            setFile(selectedFile);
            const reader = new FileReader();
            reader.onload = () => setImage(reader.result as string);
            reader.readAsDataURL(selectedFile);
        }
    };

    const upload = async (event: React.FormEvent) => {
        event.preventDefault();
        if (!file) return;

        setLoading(true);
        setError(null);

        const formData = new FormData();
        formData.append('avatar', file);

        try {
            const token = localStorage.getItem('token');
            await axios.post('http://localhost/api/avatar', formData, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'multipart/form-data',
                },
            });
        } catch (err) {
            setError('Failed to upload avatar. Try again later.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="flex flex-col items-center gap-4 p-6">
            <form onSubmit={upload} className="flex flex-col items-center gap-4">
                <h1 className="text-2xl font-semibold">Upload Avatar</h1>
                <label className="cursor-pointer bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Choose File
                    <input
                        type="file"
                        accept="image/png, image/jpeg"
                        onChange={handleImageChange}
                        className="hidden"
                    />
                </label>
                {image && (
                    <div className="relative w-48 h-36 overflow-hidden border-2 border-gray-300">
                        <img src={image} alt="Avatar"/>
                    </div>
                )}
                {error && <p className="text-red-500">{error}</p>}
                <button
                    type="submit"
                    disabled={!file || loading}
                    className="mt-4 bg-blue-500 text-white px-6 py-2 rounded-lg disabled:opacity-50"
                >
                    {loading ? 'Uploading...' : 'Save Avatar'}
                </button>
            </form>
        </div>
    );
}
