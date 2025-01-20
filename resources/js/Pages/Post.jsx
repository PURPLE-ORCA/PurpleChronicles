import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { usePage } from '@inertiajs/react';
import React from 'react'

export default function Post() {
  const { post , featuredPosts } = usePage().props;

  return (
    <AuthenticatedLayout 
      header={
        <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Post
        </h2>
      }>

      <div>
        <h1>this is the post page</h1>
        <h2>{post.title}</h2>
          <img
          src={`/storage/${post.featured_image}`}
          alt={post.title}
          />
        <p>{post.content}</p>
      </div>
    </AuthenticatedLayout>
  )
}