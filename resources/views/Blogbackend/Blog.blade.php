@extends('Blogbackend.components.layout')
@section('content')
<link rel="stylesheet" href='{{asset('css/blog.css')}}'>
<div class="container mt-4">
    <div class="addnews">
        <h2>Blogs List</h2>
        <div>
            <a href="Blog_website/Home"><button>View Site</button></a>
            <a href="/AddBlog"><button>Add Blog</button></a>
        </div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="table-responsive">
        <table id="user-table" class="user-table">
            <div class="filter-container">
                <h4>Filter</h4>
                <div class="filter">
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate">
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate">
                    <button id="filterButton">Filter</button>
                </div>
                </div>
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    
</div>


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        
        const table = $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/getblogAjax',
                type: 'POST',
                data: function (d) {
                    d.startDate = $('#startDate').val(); 
                   d.endDate = $('#endDate').val(); 
                } 
            },
            pageLength: 5, 
            columns: [
                {
                    data: null, // This column is for the auto-incrementing number
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1 + meta.settings._iDisplayStart; // Calculate row number
                    }
                },
                { data: 'slug', name: 'slug' },
                { data: 'userid', name: 'userid' },
                { data: 'title', name: 'title' },
                { data: 'authorname', name: 'authorname' },
                { data: 'category', name: 'category' },
                { data: 'created_at', name: 'created_at' },
                { data: 'edit', orderable: false, searchable: false },
                { data: 'delete', orderable: false, searchable: false },
            ],
        });
        $('#filterButton').on('click', function () {
            table.ajax.reload();
        });
    });
</script>
@endsection
