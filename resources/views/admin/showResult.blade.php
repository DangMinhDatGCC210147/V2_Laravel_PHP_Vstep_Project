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
                        <li class="breadcrumb-item active">List of Result</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- end page title -->
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            {{-- <a href="{{ route('createInstructor.create') }}" class="btn btn-info">Create</a> --}}
            <button class="btn btn-light" onclick="window.location='{{ route('download.allfiles') }}'">Download All</button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">List of Results</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Test Name</th>
                                <th>Listening</th>
                                <th>Reading</th>
                                <th>Writing</th>
                                <th>Speaking</th>
                                <th>Overall</th>
                                <th>Date Finish</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($testResults as $result)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $result->student->name }}</td>
                                    <td>{{ $result->student->account_id }}</td>
                                    <td>{{ $result->test_name }}</td>
                                    <td>{{ number_format($result->computed_listening_score, 1) }}</td>
                                    <td>{{ number_format($result->computed_reading_score, 1) }}</td>
                                    <td>
                                        @if (number_format($result->computed_writing_score, 1) != 0.0)
                                            {{ number_format($result->computed_writing_score, 1) }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($result->speaking_part1 != null || $result->speaking_part2 != null || $result->speaking_part3 != null)
                                            {{ $result->speaking }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($result->computed_writing_score != 0.0 && $result->speaking !== null)
                                            {{ number_format($result->average_score, 1) }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </td>
                                    <td>{{ $result->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a
                                            href="{{ route('mark.response', [
                                                'studentId' => $result->student->id,
                                                'testName' => $result->test_name,
                                                'resultId' => isset($result->computed_listening_score) ? $result->id : '',
                                            ]) }}">
                                            <i class="mdi mdi-lead-pencil mdi-24px" style="color:orange"></i>
                                        </a>
                                        <a
                                            href="{{ route('download.response', ['studentId' => $result->student->id, 'testName' => $result->test_name]) }}">
                                            <i class="mdi mdi-download mdi-24px"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- @foreach ($lecturers as $lecturer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $lecturer->name }}</td>
                                    <td>{{ $lecturer->email }}</td>
                                    <td>{{ $lecturer->account_id }}</td>
                                    <td>
                                        <a href="{{ route('createInstructor.edit', $lecturer->slug) }}"><i
                                                class="mdi mdi-lead-pencil mdi-24px"></i></a>
                                        <a href="{{ route('createInstructor.destroy', $lecturer->slug) }}"
                                            onclick="event.preventDefault();
                                                    if(confirm('Are you sure you want to delete this test?')) {
                                                        document.getElementById('delete-form-{{ $lecturer->slug }}').submit();
                                                    }">
                                            <i class="mdi mdi-delete-empty mdi-24px" style="color: red"></i>
                                        </a>
                                        <form id="delete-form-{{ $lecturer->slug }}"
                                            action="{{ route('createInstructor.destroy', $lecturer->slug) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
    <script>
        // Chạy script này khi tài liệu đã sẵn sàng
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                });
            @endif
        });
    </script>
@endsection
