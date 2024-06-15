@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0"> Create Assignment</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active"> New </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('tableAssignment.index') }}">
                <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title">Create Assignment</h5>
                <form id="assignmentTypetForm" action="{{ route('storeAssignmentType') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                    <label class="col-md col-form-label" for="simple-input">Number of Question</label>
                                    <div class="col-md-8">
                                        <input type="number" id="numberQuestion" class="form-control"
                                            placeholder="Number of Quesion" name="numberQuestion" required>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="typeQuestion" name="typeQuestion" aria-label="Floating label select example" required>
                                    <option selected>Choose Question type</option>
                                    <option value="Multiplechoice">Multiplechoice</option>
                                    <option value="Fillintheblank">Fill in the blank</option>
                                    <option value="Truefalse">True-False</option>
                                    <option value="Matching">Matching</option>
                                </select>
                                <label for="skillSelect">Type Question  </label>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="partSelect" name="partName" aria-label="Floating label select example" required>
                                    <option selected>Choose part name</option>
                                </select>
                                <label for="partSelect">Part Name</label>
                            </div>
                        </div> --}}
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Next</button>
                    </div>
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
    {{-- <script src="{{ asset('admin/assets/js/selectSkillAndPart.js') }}"></script> --}}
@endsection
