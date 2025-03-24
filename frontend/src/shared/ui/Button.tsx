import React from 'react';

interface ButtonProps {
    onClick: () => void;
    children: React.ReactNode;
}

export const Button: React.FC<ButtonProps> = ({ onClick, children }) => (
    <button
        onClick={onClick}
        type="button"
        className="mt-4 bg-blue-500 text-white px-6 py-2 rounded-lg disabled:opacity-50"
    >
        {children}
    </button>
);
