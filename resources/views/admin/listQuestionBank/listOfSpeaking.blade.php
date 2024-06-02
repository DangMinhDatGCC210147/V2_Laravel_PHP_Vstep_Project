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
                        <li class="breadcrumb-item active">List of Parts</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-3">
        <a href="{{ route('questionBank.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page
        </a>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            {{-- <a href="{{ route('createStudent.create') }}" class="btn btn-info">Create</a> --}}
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Parts</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Skill Name</th>
                                <th>Slug</th>
                                <th>Time Limit</th>
                                <th>Part Name</th>
                                <th>Creating At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($speakingQuestionBank as $index => $speaking)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $speaking->skill_name }}</td>
                                    <td>{{ $speaking->slug }}</td>
                                    <td>{{ $speaking->time_limit }}</td>
                                    <td>{{ str_replace('_', ' ', $questions[$index]) }}</td>
                                    <td>{{ $speaking->created_at }}</td>
                                    <td>
                                        <a href="{{ route('editQuestionSpeaking', ['test_slug' => $speaking->slug, 'part_name' => $questions[$index]]) }}"><i
                                            class="mdi mdi-lead-pencil mdi-24px"></i></a>
                                        <a href="{{ route('test.skill.destroy', $speaking->slug) }}"
                                            onclick="event.preventDefault();
                                                    if(confirm('Are you sure you want to delete this test skill?')) {
                                                        document.getElementById('delete-form-{{ $speaking->slug }}').submit();
                                                    }">
                                            <i class="mdi mdi-delete-empty mdi-24px" style="color: red"></i>
                                        </a>
                                        <form id="delete-form-{{ $speaking->slug }}"
                                            action="{{ route('test.skill.destroy', $speaking->slug) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
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
