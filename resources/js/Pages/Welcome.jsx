import { Head, Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import 'swiper/css/bundle';
import { Swiper as SwiperComponent, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation, Pagination } from 'swiper/modules'; // Import Autoplay, Navigation and Pagination


export default function Welcome({ posts, featuredPosts, comments, authors, categories }) {
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
            <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'></link>

            <Head title="Welcome" />
            <div className="bg-gray-50 dark:bg-gray-900">
            <header className="bg-purple-800 text-white">
                <nav className="flex items-center justify-between p-4">
                    <img className="w-14" src="/img/purplechronicleslogo.png" alt="Logo" />
                    <div className="font-bold text-xl">PURPLE CHRONICLES</div>
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

                <main className="mt-6 container mx-auto px-4">
                    {/* Latest Articles */}
                    <section className="mb-16 mt-6">
                        <h2 className="text-5xl font-bold text-center text-purple-800 mb-4">LATEST ARTICLES</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {posts && posts.data && posts.data.map((post) => (
                                <div key={post.id} className="bg-white dark:bg-current shadow rounded-lg overflow-hidden">
                                    <img
                                        className="w-full h-48 object-cover"
                                        src={`/storage/${post.featured_image}`}
                                        alt={post.title}
                                    />
                                    <div className="p-4">
                                        <h3 className="text-xl dark:text-white font-semibold mb-2">{post.title}</h3>
                                        <p className="text-gray-600 dark:text-white ">{post.meta_description}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* Most Seen Articles */}
                    <section className="container mx-auto mb-8 px-4">
                        <h2 className="text-5xl text-center font-bold mb-4 text-purple-800">MOST SEEN ARTICLES</h2>
                            <SwiperComponent
                                spaceBetween={30}
                                slidesPerView={1}
                                loop
                                breakpoints={{
                                        768: {
                                            slidesPerView: 2,
                                        },
                                        1024: {
                                            slidesPerView: 3,
                                        }
                                    }}
                                navigation={{
                                        clickable: true,
                                        prevEl: '.swiper-button-prev',
                                       nextEl: '.swiper-button-next',

                                 }}
                                      pagination={{ clickable: true,  el:'.swiper-pagination',  type: 'bullets'}}
                                    modules={[Autoplay, Navigation, Pagination]}

                                >
                                {featuredPosts && featuredPosts.map((post) => (
                                <SwiperSlide key={post.id}>
                                    <div className="p-4 mb-12 bg-white dark:bg-current rounded-md overflow-hidden shadow-md">
                                        <img
                                                    className="w-full rounded-md object-cover h-60"
                                                    src={`/storage/${post.featured_image}`}
                                                    alt={post.title}
                                                />
                                        <h3 className="text-xl font-semibold mt-2 text-current dark:text-white">{post.title}</h3>
                                    </div>
                                </SwiperSlide>
                                ))}
                                <div className="swiper-button-prev text-gray-100"></div>
                                <div className="swiper-button-next text-gray-100"></div>
                                <div className="swiper-pagination mt-8 text-purple-800"></div>
                            </SwiperComponent>
                    </section>

                    {/* Latest Comments */}
                    <section className="mb-16">
                        <h2 className="text-5xl text-center font-bold text-purple-800 mb-4">LATEST COMMENTS</h2>
                        <div className="space-y-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            {comments && comments.map((comment) => (
                                <div key={comment.id} className="bg-white dark:bg-black shadow rounded-lg p-4">
                                    <div className='flex flex-row gap-2'>
                                    <p className="font-bold dark:text-white">{comment.user.name}</p>
                                    <p className="text-gray-500 text-sm dark:text-white">{comment.created_at_human}</p>
                                    </div>
                                    <p className="text-gray-600 dark:text-white">Commented on <span className='font-bold'><a href="/posts/{post:slug}">{comment.post.title}</a></span></p>
                                    <p className="mt-2 dark:text-white">{comment.content}</p>
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* Blog Authors */}
                    <section className="mb-16">
                        <div className="max-w-screen-lg mx-auto">
                            <h2 className="text-5xl text-center font-bold text-purple-800 mb-4">BLOG AUTHORS</h2>
                            <div className="flex overflow-x-auto gap-4 justify-center mt-10">
                                {authors && authors.map((author) => (
                                    <div key={author.id} className="w-64 bg-white dark:bg-black shadow rounded-lg text-center p-10">
                                        <div className="w-24 h-24 mx-auto rounded-full bg-gray-200"></div>
                                        <h3 className="mt-2 text-xl font-semibold dark:text-white">{author.name}</h3>
                                        <p className="text-gray-500 text-sm dark:text-white">Since: {author.created_at.substring(0, 10)}</p>
                                        <p className="mt-2 text-lg dark:text-white">Articles: {author.posts_count}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </section>

                </main>

                <footer className="bg-gray-100 dark:bg-black dark:text-white py-6 text-center text-sm">
                    Mohammed Orca © 2025
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
    );
}