@extends('layouts.app')

<!-- This was an attempt to make an admin dashboard, however is not finalised -->
@section('content')
    <div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Admin Dashboard</h1>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Users -->
            <div class="bg-blue-500 text-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold">Total Users</h2>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
            </div>

            <!-- Total Posts -->
            <div class="bg-green-500 text-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold">Total Posts</h2>
                <p class="text-3xl font-bold">{{ $totalPosts }}</p>
            </div>

            <!-- Total Comments -->
            <div class="bg-yellow-500 text-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold">Total Comments</h2>
                <p class="text-3xl font-bold">{{ $totalComments }}</p>
            </div>
        </div>

        <!-- User Management -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Manage Users</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-2">Name</th>
                        <th class="border border-gray-300 p-2">Email</th>
                        <th class="border border-gray-300 p-2">Role</th>
                        <th class="border border-gray-300 p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="text-center">
                            <td class="border border-gray-300 p-2">{{ $user->name }}</td>
                            <td class="border border-gray-300 p-2">{{ $user->email }}</td>
                            <td class="border border-gray-300 p-2">{{ $user->role }}</td>
                            <td class="border border-gray-300 p-2">
                                <form action="{{ route('admin.updateRole', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="rounded border-gray-300">
                                        <option value="user" @if($user->role === 'user') selected @endif>User</option>
                                        <option value="admin" @if($user->role === 'admin') selected @endif>Admin</option>
                                    </select>
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">Update</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Post Management -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Manage Posts</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 p-2">Content</th>
                        <th class="border border-gray-300 p-2">Author</th>
                        <th class="border border-gray-300 p-2">Created At</th>
                        <th class="border border-gray-300 p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr class="text-center">
                            <td class="border border-gray-300 p-2">{{ Str::limit($post->content, 50) }}</td>
                            <td class="border border-gray-300 p-2">{{ $post->user->name }}</td>
                            <td class="border border-gray-300 p-2">{{ $post->created_at->format('d M Y') }}</td>
                            <td class="border border-gray-300 p-2">
                                <form action="{{ route('admin.deletePost', $post->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
