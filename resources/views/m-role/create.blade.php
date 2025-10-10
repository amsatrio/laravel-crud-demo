@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Create New Role</h2>
    
    <form id="create-role-form">
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
        <button type="submit" class="btn btn-primary">Create Role</button>
        <a href="{{ route('web-m-roles.index') }}" class="btn btn-secondary">Cancel</a>
        <div id="error-messages" class="text-danger mt-2"></div>
    </form>
</div>

<script>
    document.getElementById('create-role-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const API_URL = '/api/m-roles';
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        const errorDiv = document.getElementById('error-messages');
        errorDiv.innerHTML = ''; // Clear previous errors

        axios.post(API_URL, data)
            .then(response => {
                alert('Role created successfully!');
                window.location.href = '{{ route('web-m-roles.index') }}'; // Redirect to list
            })
            .catch(error => {
                console.error('Error creating role:', error.response);
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
</script>
@endsection