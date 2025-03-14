import React from 'react';

interface InputProps {
    type: string;
    value: string;
    onChange: (value: string) => void;
    error?: string;
    label: string;
    required?: boolean;
}

export const Input: React.FC<InputProps> = ({ type, value, onChange, error, label, required }) => (
    <div>
        <label className="block text-sm font-medium text-gray-900">{label}</label>
        {error && <p className="text-sm text-red-500">{error}</p>}
        <input
            type={type}
            value={value}
            onChange={(e) => onChange(e.target.value)}
            required={required}
            className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
        />
    </div>
);
