@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Role List</h2>
    <a href="{{ route('web-m-roles.create') }}" class="btn btn-primary mb-3">Add New Role</a>
    
    <table class="table" id="roles-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Code</th>
                <th>Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#roles-table tbody');
        const API_URL = '/api/m-roles';

        // Fetch and Render Roles
        function fetchRoles() {
            axios.get(API_URL)
                .then(response => {
                    tableBody.innerHTML = '';
                    response = response.data;
                    response.data.forEach(role => {
                        const row = tableBody.insertRow();
                        row.innerHTML = `
                            <td>${role.id}</td>
                            <td>${role.name}</td>
                            <td>${role.code}</td>
                            <td>${role.level}</td>
                            <td>
                                <a href="/web/m-roles/detail/${role.id}" class="btn btn-sm btn-info">Detail</a>
                                <a href="/web/m-roles/edit/${role.id}" class="btn btn-sm btn-info">Edit</a>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${role.id}">Delete</button>
                            </td>
                        `;
                    });
                })
                .catch(error => console.error('Error fetching roles:', error));
        }

        // Handle Delete
        tableBody.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-btn')) {
                const roleId = e.target.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this role?')) {
                    // Send DELETE request to the API
                    axios.delete(`${API_URL}/${roleId}`)
                        .then(() => {
                            alert('Role deleted successfully!');
                            fetchRoles(); // Refresh the list
                        })
                        .catch(error => {
                            console.error('Error deleting role:', error);
                            alert('Failed to delete role.');
                        });
                }
            }
        });

        fetchRoles(); // Initial fetch
    });
</script>
@endsection