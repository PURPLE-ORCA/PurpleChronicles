import { usePage , Link, Head} from '@inertiajs/react';
import React, { useEffect, useState } from 'react'

export default function SCategory() {
    const { auth, category , categories } = usePage().props; // Get the auth and category props
    const user = auth.user;

    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [isDarkMode, setIsDarkMode] = useState(false);

    const toggleSidebar = () => {
        setSidebarOpen(!sidebarOpen);
    };

    useEffect(() => {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setIsDarkMode(savedTheme === 'dark');
        } else {
            // Default to system preference if no saved theme
            setIsDarkMode(window.matchMedia('(prefers-color-scheme: dark)').matches);
        }
    }, []);
    // Update theme and save preference whenever isDarkMode changes
    useEffect(() => {
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    }, [isDarkMode]);

    const toggleTheme = () => {
        setIsDarkMode(!isDarkMode);
    };

  return (
    <>
    <div className='bg-gray-50 dark:bg-gray-900'>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'></link>
        <Head title={category.name}/>

        <header className="bg-purple-800 text-white">
                    <nav className="flex items-center justify-between p-4">
                        <img className="w-14" src="/img/ChroniclesOwl.png" alt="Logo" />
                        <Link href="/">
                            <div className="font-bold text-xl">PURPLE CHRONICLES</div>
                        </Link>
                            {/* Theme Toggle */}
                            <div>
                            <button
                                onClick={toggleTheme}
                                className="p-2 mt-2"
                                aria-label="Toggle Dark Mode"
                            >
                                {isDarkMode ? (
                                    <i className="bx bx-sun text-xl text-white-500"></i>
                                ) : (
                                    <i className="bx bx-moon text-xl text-gray-300"></i>
                                )}
                            </button>
                            <i className="bx bx-menu-alt-right text-2xl cursor-pointer" onClick={toggleSidebar}></i>
                        </div>
                    </nav>
                    <div className="bg-gray-100 py-2 overflow-x-auto dark:bg-black">
                    <div className="flex justify-center space-x-4 px-4 ">
                            {categories && categories.map((category) => (
                                <Link
                                    key={category.id}
                                    href={`/categories/${category.slug}`}
                                    className="bg-purple-800 text-white text-sm font-bold py-1 px-3 rounded whitespace-nowrap"
                                >
                                    {category.name}
                                </Link>
                            ))}
                        </div>
                    </div>
        </header>

        <main>
            <div key={category.id} className="container mx-auto my-8 sm:my-16 px-4">
                <h2 className="text-4xl font-bold text-center text-purple-800 mb-4">Category: {category.name}</h2>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                    {category.posts.map((article) => (
                        <div key={article.id} className="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md">
                            <img src={article.featured_image} alt={article.title} className="w-full h-36 object-cover" />
                            <div className="p-4">
                                <h3 className="text-xl dark:text-white font-semibold mb-2">{article.title}</h3>
                                <Link href={`/post/${article.slug}`} className="block mt-2 text-purple-600 hover:underline">Read More</Link>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </main>

        <footer className="bg-gray-100 dark:bg-black dark:text-white py-6 text-center text-sm">
                    under construction Mohammed Orca © 2025
        </footer>

        {/* Sidebar */}
        <div
                    className={`fixed top-0 right-0 h-full w-64 bg-purple-800 text-white shadow-xl transform transition-transform duration-300 ease-in-out ${
                        sidebarOpen ? 'translate-x-0' : 'translate-x-full'
                    } z-50`}
                >
                    <div className="p-4">
                        <div className="flex justify-end">
                            <i className='bx bx-x text-3xl cursor-pointer' onClick={toggleSidebar}></i>
                        </div>
                        <h2 className="text-xl font-bold mb-4">Menu</h2>
                        <nav>
                            <Link href="/" className="block py-2 hover:bg-purple-700">Home</Link>
                            {user && (user.role === 'admin' || user.role === 'author') && (
                            <Link href="/dashboard" className="block p-2 hover:bg-purple-700">Dashboard</Link>
                            )}
                            <Link href="/about" className="block py-2 hover:bg-purple-700">About</Link>
                            <Link href="/contact" className="block py-2 hover:bg-purple-700">Contact</Link>
                        </nav>
                        <h2 className="text-xl font-bold mt-6 mb-4">Account</h2>
                        <nav>
                            <Link href="/login" className="block py-2 hover:bg-purple-700">Login</Link>
                            <Link href="/register" className="block py-2 hover:bg-purple-700">Register</Link>
                        </nav>
                    </div>
        </div>

        {/* Sidebar Overlay */}
        {sidebarOpen && (
            <div
                className="fixed inset-0 bg-black opacity-50 z-40"
                onClick={toggleSidebar}
            ></div>
        )}
    </div>
    </>
  )
}