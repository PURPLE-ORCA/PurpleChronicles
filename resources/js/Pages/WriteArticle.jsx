import React, { useState } from 'react';
import { Editor } from '@tinymce/tinymce-react';
import { X } from 'lucide-react';
import { createInertiaApp } from '@inertiajs/react';

const WriteArticle = ({ onClose }) => {

  const [title, setTitle] = useState('');
  const [content, setContent] = useState('');
  const [category, setCategory] = useState('');
  const [tags, setTags] = useState('');
  const [featuredImage, setFeaturedImage] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();
  
    const formData = new FormData();
    formData.append('title', title);
    formData.append('content', content);
    formData.append('category', category);
    formData.append('tags', tags.split(','));
    formData.append('featured_image', featuredImage);
  
    Inertia.post('/posts', formData, {
      onSuccess: () => {
        console.log('Post created successfully!');
        onClose(); // Close the modal after a successful submission
      },
      onError: (errors) => {
        console.error('Error creating post:', errors);
      },
    });
  };

  return (
    <div className="fixed inset-0 bg-gray-800 bg-opacity-50 h-full w-full">
      <div className="relative top-0 mx-auto p-5 w-full h-full bg-white dark:bg-gray-900">
        <div className="flex justify-between h-full items-center mb-4">
          <h2 className="text-2xl font-bold dark:text-white mb-4">Write New Article</h2>
          <button 
            onClick={onClose}
            className="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
          >
            <X size={24} />
          </button>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          {/* Title Input */}
          <div>
            <input
              type="text"
              placeholder="Article Title"
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              className="w-full p-2 border rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white"
            />
          </div>

          {/* WYSIWYG Editor */}
          <div className="h-96">
            <Editor
              apiKey="aad3upt6n4gt91bedabxss8ll4v1oas906hkz8x151zebmwg"
              value={content}
              onEditorChange={(newContent) => setContent(newContent)}
              init={{
                height: '100%',
                menubar: true,
                plugins: [
                  'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                  'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                  'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                  'bold italic backcolor | alignleft aligncenter ' +
                  'alignright alignjustify | bullist numlist outdent indent | ' +
                  'removeformat | help',
                skin: document.documentElement.classList.contains('dark') ? 'oxide-dark' : 'oxide',
                content_css: document.documentElement.classList.contains('dark') ? 'dark' : 'default'
              }}
            />
          </div>

          {/* Category Selection */}
          <div>
            <input
              type="text"
              placeholder="Category"
              value={category}
              onChange={(e) => setCategory(e.target.value)}
              className="w-full p-2 border rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white"
            />
          </div>

          {/* Tags Input */}
          <div>
            <input
              type="text"
              placeholder="Tags (comma-separated)"
              value={tags}
              onChange={(e) => setTags(e.target.value)}
              className="w-full p-2 border rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white"
            />
          </div>

          {/* Featured Image Upload */}
          <div>
            <input
              type="file"
              accept="image/*"
              onChange={(e) => setFeaturedImage(e.target.files[0])}
              className="w-full p-2 border rounded-md dark:bg-gray-800 dark:border-gray-700 dark:text-white"
            />
          </div>

          {/* Submit Button */}
          <div className="flex justify-end gap-4">
            <button
              type="button"
              onClick={onClose}
              className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
            >
              Cancel
            </button>
            <button
              type="submit"
              className="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700"
            >
              Publish
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default WriteArticle;