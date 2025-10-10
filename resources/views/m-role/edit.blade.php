@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Role</h2>
    
    <form id="edit-role-form">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required maxlength="20">
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Code</label>
            <input type="text" class="form-control" id="code" name="code" required maxlength="20">
        </div>
        <div class="mb-3">
            <label for="level" class="form-label">Level</label>
            <input type="number" class="form-control" id="level" name="level">
        </div>
        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="{{ route('web-m-roles.index') }}" class="btn btn-secondary">Cancel</a>
        <div id="error-messages" class="text-danger mt-2"></div>
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

        // Handle Update Form Submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            errorDiv.innerHTML = ''; // Clear previous errors

            // Use PATCH method for update
            axios.patch(API_URL, data)
                .then(response => {
                    alert('Role updated successfully!');
                    window.location.href = '{{ route('web-m-roles.index') }}'; // Redirect to list
                })
                .catch(error => {
                    console.error('Error updating role:', error.response);
                    if (error.response && error.response.status === 422) {
                        let errorsHtml = '<ul>';
                        // Display validation errors
                        Object.values(error.response.data.errors).forEach(messages => {
                            messages.forEach(message => {
                                errorsHtml += `<li>${message}</li>`;
                            });
                        });
                        errorsHtml += '</ul>';
                        errorDiv.innerHTML = errorsHtml;
                    } else {
                        alert('An unexpected error occurred.');
                    }
                });
        });
    });
</script>
@endsection