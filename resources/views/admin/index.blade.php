@extends('admin.layouts.layout-admin')
@section('title', 'Lecture Index')
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashtrap</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-danger float-end">Monthly</span>
                        <h5 class="card-title mb-0">The person who does the most tests</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h4 class="d-flex align-items-center mb-0">
                                {{ $person->name }}
                            </h4>
                        </div>
                        <div class="col-4 text-end">
                            <span class="text-muted"><h3>{{ $person->test_results_count }}</h3></span>
                        </div>
                    </div>
                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-info float-end">Per Day</span>
                        <h5 class="card-title mb-0">Highest number of correct reading questions</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h4 class="d-flex align-items-center mb-0">
                                {{ $highestReading->name }}
                            </h4>
                        </div>
                        <div class="col-4 text-end">
                            <span class="text-muted"><h3>{{ $highestReading->reading_correctness }} câu</h3></span>
                        </div>
                    </div>
                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-success float-end">Per Day</span>
                        <h5 class="card-title mb-0">Highest number of correct listening questions</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h4 class="d-flex align-items-center mb-0">
                                {{ $highestListening->name }}
                            </h4>
                        </div>
                        <div class="col-4 text-end">
                            <span class="text-muted"><h3>{{ $highestListening->listening_correctness }} câu</h3></span>
                        </div>
                    </div>
                </div>
                <!--end card body-->
            </div>
            <!--end card-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-end">All Time</span>
                        <h5 class="card-title mb-0">Number of Students who took the test</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h3 class="d-flex align-items-center mb-0">
                                {{ $count }}
                            </h3>
                        </div>
                        <div class="col-4 text-end">
                            <span class="text-muted"><h4>{{ $totalStudentsCount }} sinh viên</h4></span>
                        </div>
                    </div>
                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->
    </div>
    <!-- end row-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Rank diligently</h4>

                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Total time to do the test</th>
                                <th>Quantity test done</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->account_id }}</td>
                                    <td>{{ $student->total_duration }}</td>
                                    <td>{{ $student->tests_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
@endsection
