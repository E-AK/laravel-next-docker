import type { NextConfig } from "next";

const nextConfig: NextConfig = {
    images: {
        remotePatterns: [
            {
                protocol: 'http',
                hostname: 'localhost',
                port: '',
                pathname: '/avatar/uploads/**',
                search: '',
            },
        ],
    },
};

export default nextConfig;
