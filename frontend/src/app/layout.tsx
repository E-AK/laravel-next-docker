'use client';

import localFont from "next/font/local";
import "./globals.css";
import axios from '@/shared/lib/axiosInstance';
import { useState, useEffect } from 'react';

const geistSans = localFont({
  src: "./fonts/GeistVF.woff",
  variable: "--font-geist-sans",
  weight: "100 900",
});
const geistMono = localFont({
  src: "./fonts/GeistMonoVF.woff",
  variable: "--font-geist-mono",
  weight: "100 900",
});

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  let loaded = false;

  const [login, setLogin] = useState(null);

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
        url: 'http://localhost/api/user/me',
        headers: {
          'Authorization': `Bearer ${token}`,
        },
      })
          .then(function (response) {
            setLogin(response?.data?.data?.email);
          });
    }

    loaded = true;
  }, []);

  const logout = (): void => {
      axios.post('/auth/logout')
          .then(() => {
              window.location.href = "/"
          })
  }

  const renderNavbarLinks = () => {
    if (login !== null) {
      return (
          <>
              <div className="ml-10 flex items-center">
                  <a href="/user">{login}</a>
              </div>
              <button onClick={logout}>Logout</button>
          </>
      );
    }

      return (
          <>
              <a href="/auth/signup" className="ml-10 px-3 py-2 rounded-md text-sm font-medium text-gray-900 bg-gray-100 hover:bg-gray-200">
            Register
          </a>
          <a href="/auth/signin" className="ml-8 px-3 py-2 rounded-md text-sm font-medium text-gray-900 bg-gray-100 hover:bg-gray-200">
            Login
          </a>
        </>
    );
  };

  return (
      <html lang="en">
      <body
          className={`${geistSans.variable} ${geistMono.variable} antialiased`}
      >
      <nav className="bg-white shadow-md">
        <div className="container mx-auto px-6 py-4 flex justify-between items-center">
          <h1 className="text-2xl font-bold text-gray-800">Test</h1>
            <a href="/task">Task</a>

          <div className="hidden md:flex space-x-6">
            {renderNavbarLinks()}
          </div>
        </div>
      </nav>

      {children}
      </body>
      </html>
  );
}