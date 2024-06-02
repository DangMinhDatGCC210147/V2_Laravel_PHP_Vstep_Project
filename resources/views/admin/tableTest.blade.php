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
                        <li class="breadcrumb-item active">List of Tests</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- end page title -->
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <a href="{{ route('test.create') }}" class="btn btn-info">Create</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Tests</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Test Name</th>
                                <th>Duration</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Lecturer Name</th>
                                <th>Test Code</th>
                                <th>Test Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tests as $test)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $test->test_name }}</td>
                                    <td>{{ $test->duration }}</td>
                                    <td>{{ $test->start_date }}</td>
                                    <td>{{ $test->end_date }}</td>
                                    <td>{{ $test->instructor->name }}</td>
                                    <td>{{ $test->test_code }}</td>
                                    @if ($test->test_status == "Active")
                                        <td><div class="badge bg-success">{{ $test->test_status }}</div></td>
                                    @else
                                        <td><div class="badge bg-warning">{{ $test->test_status }}</div></td>
                                    @endif
                                    
                                    <td>
                                        <a href="{{ route('test.edit', $test->slug) }}"><i
                                                class="mdi mdi-lead-pencil mdi-24px"></i></a>
                                        <a href="{{ route('test.destroy', $test->slug) }}"
                                            onclick="event.preventDefault();
                                                    if(confirm('Are you sure you want to delete this test?')) {
                                                        document.getElementById('delete-form-{{ $test->slug }}').submit();
                                                    }">
                                            <i class="mdi mdi-delete-empty mdi-24px" style="color: rgb(206, 25, 25)"></i>
                                        </a>
                                        <form id="delete-form-{{ $test->slug }}"
                                            action="{{ route('test.destroy', $test->slug) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a href="{{ route('testSkills.show', $test->slug) }}"><i
                                                class="mdi mdi-layers-plus mdi-24px"
                                                style="color: rgb(198, 198, 24)"></i></a>
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
