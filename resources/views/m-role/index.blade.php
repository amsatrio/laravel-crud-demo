@extends('layouts.app')
@section('content')
    <div class="container">
        <h2>Role List</h2>
        <a href="{{ route('web-m-roles.create') }}" class="btn btn-primary mb-3">Add New Role</a>

        <table class="display" id="roles-table">
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

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
            tableBody.addEventListener('click', function (e) {
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

            new DataTable('#roles-table', {
                lengthMenu: [10, 25, 50, -1],
                processing: true,
                serverSide: true,
                ajax: {
                    url: API_URL,
                    type: "GET",
                    data: function (d) {
                        // d.start is the record index (e.g., 0, 10, 20)
                        // d.length is the page size (e.g., 10, 25, 50)

                        // Calculate the 'page' number (0-indexed)
                        const pageNumber = d.start / d.length;

                        // Return the modified object that DataTables sends in the request
                        return {
                            // DataTables requires 'draw' to be echoed back
                            draw: d.draw,

                            // Map DataTables 'start' and 'length' to your 'page' and 'size'
                            page: pageNumber, // Your API page number (0, 1, 2, ...)
                            size: d.length,   // Your API page size (10, 25, ...)

                            // Add search/order parameters if implemented on the backend
                            search: d.search.value,
                            // orderColumn: d.columns[d.order[0].column].data,
                            // orderDir: d.order[0].dir
                        };
                    },
                    dataSrc: function (json) {
                        if (json.data) {
                            return json.data
                        }
                        return []
                    },
                    // "headers": {
                    //     "Authorization": "Bearer YOUR_TOKEN"
                    // }
                },
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'code' },
                    { data: 'level' },
                    {
                        data: null, // Use null because we're not binding to a single data field
                        render: function (data, type, row) {
                            // 'row' contains the full JSON object for the current role

                            const detailUrl = `/web/m-roles/detail/${row.id}`;
                            const editUrl = `/web/m-roles/edit/${row.id}`;

                            return `
                                <a href="${detailUrl}" class="btn btn-sm btn-info">Detail</a>
                                <a href="${editUrl}" class="btn btn-sm btn-info">Edit</a>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
                            `;
                        },
                        orderable: false // Actions column shouldn't be sortable
                    }
                ]
            });
        });
    </script>
@endsection