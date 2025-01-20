import WriteArticle from './WriteArticle';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage, router } from '@inertiajs/react';
import { useState, useMemo, useEffect } from 'react';

const Modal = ({ isOpen, onClose, children }) => {
    if (!isOpen) return null;

    return (
        <div className="mt-2 w-full h-full bg-gray-900 bg-opacity-90 transform transition-transform duration-300 ease-in-out translate-x-0 z-50">
            <div className="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg relative">
                <button onClick={onClose} className="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                    <i className='bx bx-x text-2xl'></i>
                </button>
                {children}
            </div>
        </div>
    );
};

export default function Dashboard() {
    const { auth, toComments, allPosts, teamMembers } = usePage().props;
    const user = auth.user;
    const [activeModal, setActiveModal] = useState(null);
    const [showCreateUserForm, setShowCreateUserForm] = useState(false);
    const [loading, setLoading] = useState({});
    const [newUser, setNewUser] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'author', // Default role
    });
    const [selectedCategory, setSelectedCategory] = useState('all');
    const [selectedStatus, setSelectedStatus] = useState('all');
    const [filterByUser, setFilterByUser] = useState(false);
    const [filteredPosts, setFilteredPosts] = useState(allPosts);
    const [articlesLoading, setArticlesLoading] = useState(false);

    useEffect(() => {
        setFilteredPosts(allPosts);
         // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [allPosts])

    const handleModalOpen = (modalName) => {
        setActiveModal(modalName);
        setShowCreateUserForm(false); // Close the form when modal is opened
         if (modalName === 'articles') {
              setSelectedCategory('all');
               setSelectedStatus('all');
                setFilterByUser(false);
                setFilteredPosts(allPosts);
        }
    };

    const handleModalClose = () => {
        setActiveModal(null);
        setShowCreateUserForm(false); // Close the form when modal is closed
          if(activeModal === 'articles') {
                setArticlesLoading(false);
            }
    };

    const handleCommentStatus = async (commentId, status) => {
        setLoading(prev => ({ ...prev, [commentId]: true }));
        
        router.patch(route('comments.update', { comment: commentId }), {
            status: status
        }, {
            preserveScroll: true,
            onSuccess: () => {
                setLoading(prev => ({ ...prev, [commentId]: false }));
            },
            onError: () => {
                setLoading(prev => ({ ...prev, [commentId]: false }));
            }
        });
    };

    const handleUpdateUserRole = async (memberId, newRole) => {
        setLoading(prev => ({ ...prev, [memberId]: true }));
    
        router.patch(`/admin/users/${memberId}/role`, {
          role: newRole,
        }, {
            preserveScroll: true,
          onSuccess: () => {
            setLoading(prev => ({ ...prev, [memberId]: false }));
          },
          onError: () => {
            setLoading(prev => ({ ...prev, [memberId]: false }));
          }
        });
    };

    const handleCreateUser = async (e) => {
        e.preventDefault();
        setLoading(prev => ({ ...prev, 'createUser': true }));

        router.post('/admin/users', newUser, {
                preserveScroll: true,
                onSuccess: () => {
                setNewUser({
                        name: '',
                        email: '',
                        password: '',
                        password_confirmation: '',
                        role: 'author'
                });
                setLoading(prev => ({ ...prev, 'createUser': false }));
                    setShowCreateUserForm(false);
                },
                onError: () => {
                setLoading(prev => ({ ...prev, 'createUser': false }));
                }
        });
    };
    const handleInputChange = (e) => {
        setNewUser(prev => ({ ...prev, [e.target.name]: e.target.value }));
    };

    const handleCategoryChange = (e) => {
            setSelectedCategory(e.target.value);
            filterPosts(e.target.value, selectedStatus, filterByUser);
    };

    const handleStatusChange = (e) => {
            setSelectedStatus(e.target.value);
        filterPosts(selectedCategory, e.target.value, filterByUser);
    };
    const handleUserFilterChange = (e) => {
        setFilterByUser(e.target.checked);
           filterPosts(selectedCategory, selectedStatus, e.target.checked);
    };
     const filterPosts = (category, status, filterByUser) => {
            setArticlesLoading(true);
            let filtered = allPosts;

            if (category !== 'all') {
                filtered = filtered.filter(post => post.category.name === category);
            }
            if (status !== 'all') {
                filtered = filtered.filter(post => post.status === status);
            }
            if (filterByUser) {
                filtered = filtered.filter(post => post.user.id === user.id)
            }
            setFilteredPosts(filtered);
           setArticlesLoading(false);
    };

    const categories = useMemo(() => {
      if (!allPosts) return [];
      return ['all', ...new Set(allPosts.map(post => post.category.name))];
    }, [allPosts])

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Dashboard
                </h2>
            }
        >
            <Head title="Dashboard" />

            <div className=''>
                <h2 className='text-center text-5xl mt-10 mb-10 text-black dark:text-white'>Hello {user.name}</h2>
                <div className='relative flex flex-col sm:flex-row'>
                    <div className='flex-wrap flex sm:flex-col justify-center'>

                        <button className='flex flex-col mx-2 my-2 md:' onClick={() => handleModalOpen('comments')}>
                            <div className='bg-white p-4 rounded-md sm:w-40 sm:h-32 shadow-md dark:bg-gray-800 flex flex-col items-center justify-center'>
                                <i className=' text-black dark:text-white bx bxs-chat text-4xl mb-4'></i>
                                <p className='text-gray-600 dark:text-gray-300 text-2xl'>
                                    Comments
                                </p>
                            </div>
                        </button>

                        <button className='flex flex-col mx-2 my-2 md:' onClick={() => handleModalOpen('articles')}>
                            <div className='bg-white p-4 rounded-md sm:w-40 sm:h-32 shadow-md dark:bg-gray-800 flex flex-col items-center justify-center'>
                                <i className=' text-black dark:text-white bx bxs-news text-4xl mb-4'></i>
                                <p className='text-gray-600 dark:text-gray-300 text-2xl'>
                                    Articles
                                </p>
                            </div>
                        </button>

                        <button className='flex flex-col mx-2 my-2' onClick={() => handleModalOpen('write')}>
                            <div className='bg-white p-4 flex flex-col rounded-md sm:w-40 sm:h-32 shadow-md dark:bg-gray-800 items-center justify-center'>
                                <i className=' text-black dark:text-white bx bxs-edit-alt text-4xl mb-4'></i>
                                <p className='text-gray-600 dark:text-gray-300 text-2xl'>
                                    Write
                                </p>
                            </div>
                        </button>

                        {user && (user.role === 'admin') && (
                            <button className='flex flex-col mx-2 my-2 md:' onClick={() => handleModalOpen('Team')}>
                                <div className='bg-white p-4 rounded-md sm:w-40 sm:h-32 shadow-md dark:bg-gray-800 flex flex-col items-center justify-center'>
                                    <i className=' text-black dark:text-white bx bxs-user text-4xl mb-4'></i>
                                    <p className='text-gray-600 dark:text-gray-300 text-2xl'>
                                        Team
                                    </p>
                                </div>
                            </button>
                        )}

                    </div>

                    {/* WRITE MODAL */}
                    <Modal isOpen={activeModal === 'write'} onClose={handleModalClose} className="w-full h-full">
                        <WriteArticle onClose={handleModalClose} />
                    </Modal>
                        
                    {/* COMMENTS MODAL */}
                    <Modal isOpen={activeModal === 'comments'} onClose={handleModalClose}>
                        <h2 className="text-2xl font-bold mb-4 text-black dark:text-white">Comments Panel</h2>
                        {toComments && toComments.map((comment) => (
                            comment.user && comment.post && ( // Only render if comment.user and comment.post exist
                                <div key={comment.id} className="bg-gray-300 dark:bg-gray-700 shadow rounded-lg p-4 text-black dark:text-white">
                                    <div className='flex justify-between'>
                                        <div className='flex flex-row gap-2'>
                                            <p className="font-bold text-black dark:text-white">{comment.user.name}</p>
                                            <p className="text-sm text-black dark:text-white">{comment.created_at_human}</p>
                                        </div>
                                        <div className="flex gap-2">
                                            <button 
                                                onClick={() => handleCommentStatus(comment.id, 'approved')}
                                                disabled={loading[comment.id]}
                                                className="transition-opacity hover:opacity-75 disabled:opacity-50"
                                            >
                                                <i className='bx bx-check text-4xl text-green-500 dark:text-green-400'></i>
                                            </button>
                                            <button 
                                                onClick={() => handleCommentStatus(comment.id, 'rejected')}
                                                disabled={loading[comment.id]}
                                                className="transition-opacity hover:opacity-75 disabled:opacity-50"
                                            >
                                                <i className='bx bx-x text-4xl text-red-500 dark:text-red-400'></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p className="text-gray-600 dark:text-white">
                                        Commented on <span className='font-bold'>
                                            <a href={`/posts/${comment.post.slug}`}>{comment.post.title}</a>
                                        </span>
                                    </p>
                                    <p className="dark:text-white">{comment.content}</p>
                                </div>
                            )
                        ))}
                    </Modal>

                    {/* POSTS MODAL */}
                   <Modal isOpen={activeModal === 'articles'} onClose={handleModalClose}>
                        <h2 className="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Articles Panel</h2>
                        <div className='mb-4 flex justify-end gap-3 flex-wrap'>
                            <select 
                                value={selectedCategory}
                                onChange={handleCategoryChange}
                                className="shadow appearance-none border rounded py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700"
                            >
                                  {categories.map(category => (
                                      <option key={category} value={category}>{category}</option>
                                 ))}
                            </select>

                             <select 
                                value={selectedStatus}
                                onChange={handleStatusChange}
                                className="shadow appearance-none border rounded py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700"
                                >
                                   <option value="all">All Statuses</option>
                                 <option value="published">Published</option>
                                 <option value="draft">Draft</option>
                                </select>

                           <div className="flex items-center">
                                  <label className="text-gray-700 dark:text-gray-300 mr-2 text-sm">
                                      My Posts
                                  </label>
                                   <input
                                    type="checkbox"
                                    checked={filterByUser}
                                    onChange={handleUserFilterChange}
                                    className="form-checkbox h-4 w-4 text-purple-600"
                                 />
                           </div>
                        </div>
                     {articlesLoading ? (
                              <p className='text-gray-500 dark:text-gray-300'>Loading articles...</p>
                        ) : filteredPosts && filteredPosts.length === 0 ? (
                                <p className='text-gray-500 dark:text-gray-300'>No articles to display.</p>
                            ) : (
                            <div className="space-y-4">
                                {filteredPosts && filteredPosts.map((post) => (
                                    post.user && post.category && ( // Only render if post.user and post.category exist
                                        <div key={post.id} className="bg-gray-300 dark:bg-gray-700 shadow rounded-lg p-4 text-black dark:text-white">
                                            <div className='flex justify-between'>
                                                <p className='font-bold text-xl dark:text-white'>{post.title}</p>
                                                <span className={`inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ${post.status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-400'}`}>
                                                    {post.status}
                                                </span>
                                            </div>

                                            <div className='flex gap-20'>
                                                <p className='text-gray-600 dark:text-white'>Category: <span className='font-bold'>{post.category.name}</span></p>
                                                <p className='text-gray-600 dark:text-white'>Author: <span className='font-bold'>{post.user.name}</span></p>
                                                <p className='text-gray-600 dark:text-white'>Views: <span className='font-bold'>{post.views}</span></p>
                                            </div>
                                            <div className='flex justify-between'>
                                                <p className='text-gray-600 dark:text-white'>{post.content.substring(0, 150)}...</p>
                                                {user && (user.role === 'admin') && (
                                                    <div className='flex gap-2'>
                                                        <i className='bx bx-edit text-2xl text-green-600'></i>
                                                        <i className='bx bxs-trash-alt text-2xl text-red-700'></i>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    )
                                ))}
                            </div>
                        )}
                    </Modal>

                    {/* TEAM MODAL */}
                    {user && (user.role === 'admin') && (
                        <Modal isOpen={activeModal === 'Team'} onClose={handleModalClose}>
                            <h2 className="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Team Panel</h2>
                            <button className='bg-purple-800 p-2 rounded mb-4 text-white dark:text-white' onClick={() => setShowCreateUserForm(!showCreateUserForm)}>
                                {showCreateUserForm ? "Close Form" : "Register new Admin/Author"}
                            </button>

                            {showCreateUserForm && (
                                <form onSubmit={handleCreateUser} className="space-y-4 mb-4">
                                    <div>
                                    <label className="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        value={newUser.name}
                                        onChange={handleInputChange}
                                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700"
                                        required
                                    />
                                    </div>
                                    <div>
                                    <label className="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email</label>
                                        <input
                                        type="email"
                                        name="email"
                                            value={newUser.email}
                                        onChange={handleInputChange}
                                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700"
                                        required
                                        />
                                    </div>
                                        <div>
                                        <label className="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Password</label>
                                            <input
                                                type="password"
                                                name="password"
                                                value={newUser.password}
                                                onChange={handleInputChange}
                                                className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700"
                                                required
                                            />
                                        </div>
                                        <div>
                                        <label className="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Confirm Password</label>
                                                <input
                                                    type="password"
                                                name="password_confirmation"
                                                    value={newUser.password_confirmation}
                                                    onChange={handleInputChange}
                                                    className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700"
                                                    required
                                                />
                                        </div>
                                        <div>
                                            <label className="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Role</label>
                                            <select
                                                    name="role"
                                                    value={newUser.role}
                                                    onChange={handleInputChange}
                                                    className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 leading-tight focus:outline-none focus:shadow-outline dark:bg-gray-700"
                                                    required
                                            >
                                            <option value="admin">Admin</option>
                                                <option value="author">Author</option>
                                            </select>
                                        </div>
                                            <button
                                            type="submit"
                                            disabled={loading['createUser']}
                                                className="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50"
                                            >
                                            Create User
                                            </button>
                                    </form>
                                        )}
                                <div className="mt-4">
                                    {teamMembers && teamMembers.length > 0 ? (
                                        <ul className="divide-y divide-gray-200 dark:divide-gray-700">
                                            {teamMembers.map(member => (
                                                <li key={member.id} className="py-4">
                                                    <div className="flex items-center justify-between">
                                                        <p className="text-black dark:text-white font-medium">{member.name}</p>
                                                        <span className={`inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ${member.role === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-800/20 dark:text-blue-400'}`}>
                                                                {member.role}
                                                        </span>
                                                            <div className="flex gap-2">
                                                                <p>Change role to :</p>
                                                                <button 
                                                                    onClick={() => handleUpdateUserRole(member.id, 'admin')}
                                                                    disabled={loading[member.id]}
                                                                    className="transition-opacity hover:opacity-75 disabled:opacity-50 text-sm bg-purple-600 px-2 py-1 rounded text-white dark:bg-purple-900"
                                                                >
                                                                    Admin
                                                                </button>
                                                                <button
                                                                    onClick={() => handleUpdateUserRole(member.id, 'author')}
                                                                        disabled={loading[member.id]}
                                                                        className="transition-opacity hover:opacity-75 disabled:opacity-50 text-sm bg-blue-600 px-2 py-1 rounded text-white dark:bg-blue-900"
                                                                    >
                                                                        Author
                                                                </button>
                                                            </div>

                                                    </div>
                                                    <p className="text-gray-600 dark:text-gray-300">
                                                            Joined: {new Date(member.created_at).toLocaleDateString()}
                                                        </p>
                                                </li>
                                            ))}
                                        </ul>
                                    ) : (
                                            <p className="text-gray-500 dark:text-gray-300">No team members to display.</p>
                                    )}
                                </div>
                        </Modal>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}