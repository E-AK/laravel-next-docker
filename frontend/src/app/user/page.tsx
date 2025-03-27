'use client';

import { useEffect, useState } from 'react';
import axios from '@/shared/lib/axiosInstance';

export default function AvatarUpload() {
    const [image, setImage] = useState<string | null>(null);
    const [file, setFile] = useState<File | null>(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        const fetchAvatar = async () => {
            const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null;
            if (!token) return;

            try {
                const userResponse = await axios.get('http://localhost/api/user/me', {
                    headers: { 'Authorization': `Bearer ${token}` },
                });

                const avatarId = userResponse?.data?.data?.avatar_id;
                if (!avatarId) return;

                const fileResponse = await axios.get(`http://localhost/api/file/thumbnail?file_id=${avatarId}`, {
                    headers: { 'Authorization': `Bearer ${token}` },
                });

                setImage(fileResponse?.data?.thumbnail);
            } catch (err) {
                setError('Failed to load avatar');
            }
        };

        fetchAvatar();
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
        formData.append('image', file);

        try {
            const token = localStorage.getItem('token');
            await axios.post('http://localhost/api/file/image?avatar=1', formData, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'multipart/form-data',
                },
            });

            window.location.reload();
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
                        <img src={image} alt="Avatar" />
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
