@extends('admin.layouts.layout-admin')

@section('content')
    <!-- start page title -->
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">Dashboard</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">List of Assignments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- end page title -->
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <a href="{{ route('create.assignemnt') }}" class="btn btn-info">Create</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Assignments</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                {{-- <th>Passage</th> --}}
                                <th>Teacher Name</th>
                                <th>Create At </th>
                                <th>Enable</th>
                                <th>Detailed Feedback</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignments as $index => $assignment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $assignment->title }}</td>
                                    {{-- <td>{{ Str::limit($assignment->description, 50) }}</td> --}}
                                    <td>{{ $assignment->teacher->name }}</td>
                                    <td>{{ $assignment->created_at }}</td>
                                    <td>{{ $assignment->isEnable ? 'Yes' : 'No' }}</td>
                                    <td>{{ $assignment->show_detailed_feedback ? 'Yes' : 'No' }}</td>
                                    <td>
                                        <a href="{{ route('editAssignment', $assignment->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('deleteAssignment', $assignment->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this assignment?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
@endsection
