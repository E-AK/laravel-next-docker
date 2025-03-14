import React from 'react';

interface ButtonProps {
    onClick: () => void;
    children: React.ReactNode;
}

export const Button: React.FC<ButtonProps> = ({ onClick, children }) => (
    <button
        onClick={onClick}
        type="button"
        className="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
    >
        {children}
    </button>
);
