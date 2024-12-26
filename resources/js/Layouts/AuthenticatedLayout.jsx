import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import { Link, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export default function AuthenticatedLayout({ header, children }) {
    const user = usePage().props.auth.user;
    const [sidebarOpen, setSidebarOpen] = useState(false);

    const toggleSidebar = () => {
        setSidebarOpen(!sidebarOpen);
    };
    const [isDarkMode, setIsDarkMode] = useState(false);

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
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'></link>

        <div className="min-h-screen bg-gray-100 dark:bg-gray-900">
            <header className="">
                <nav className="flex items-center justify-between p-4">
                    <img className='w-14' src="/img/purplechronicleslogo.png" alt="" />
                            <div>
                                <p>PURPLE CHRONICLES</p>
                            </div>
                            <div className="flex items-center">
                        {/* Theme Toggle */}
                        <div className="ms-3 relative">
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
                        </div>
                        </div>

                    <i className='bx bx-menu-alt-right text-2xl cursor-pointer' onClick={toggleSidebar}></i>

                </nav>
            </header>

            <main>{children}</main>
            <footer className="py-16 text-center text-sm text-black dark:text-white/70">
                Mohammed Orca © 2025
            </footer>


               {/* Sidebar */}
               <div
                    className={`fixed top-0 right-0 h-full w-64 bg-gray-800 text-white shadow-xl transform transition-transform duration-300 ease-in-out ${
                        sidebarOpen ? 'translate-x-0' : 'translate-x-full'
                    }  z-50`} // z-50 ensures sidebar is on top
                >
                    <div className="p-4">
                    <div className="flex justify-end">
                        <i class='bx bx-x text-3xl cursor-pointer' onClick={toggleSidebar}></i>
            
                     </div>
                        <h2 className="text-xl font-bold mb-4">Menu</h2>
                        <div >
                            <Link href="/" className="block p-2 hover:bg-gray-700">Home</Link>
                            <Link href="/about" className="block p-2 hover:bg-gray-700">About</Link>
                            <Link href="/contact" className="block p-2 hover:bg-gray-700">Contact</Link>
                        </div>
                        <h2 className="text-xl font-bold mt-12 mb-4">{user.name}</h2>
                        <div className="mt-2">
                            <Link className={`block p-2 ${route().current("profile.edit") ? 'bg-purple-800' : ''}`} href={route("profile.edit")}>Profile</Link>
                            <Link className='block p-2 ' href={route("logout")} method="post" as="button">Log Out</Link>
                        </div>
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
    );
}
