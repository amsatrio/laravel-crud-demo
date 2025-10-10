@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Role</h2>
    
    <form id="edit-role-form">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required maxlength="20" disabled>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" id="code" name="code" required maxlength="20" disabled>
        </div>
        <div class="mb-3">
            <label for="level" class="form-label">Level</label>
            <input type="number" class="form-control" id="level" name="level" disabled>
        </div>
        <a href="{{ route('web-m-roles.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Retrieve the role ID passed from the controller
        const roleId = {{ $roleId }}; 
        const API_URL = `/api/m-roles/${roleId}`;
        const form = document.getElementById('edit-role-form');
        const errorDiv = document.getElementById('error-messages');

        // Fetch current role data
        axios.get(API_URL)
            .then(response => {
                const role = response.data.data;
                document.getElementById('name').value = role.name || '';
                document.getElementById('code').value = role.code || '';
                document.getElementById('level').value = role.level || '';
            })
            .catch(error => {
                console.error('Error fetching role:', error.response);
                alert('Failed to load role data.');
            });
    });
</script>
@endsection