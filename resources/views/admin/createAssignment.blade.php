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
                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="simple-input">Title</label>
                        <div class="col-md-10">
                            <input type="text" id="simple-input" class="form-control" 
                            placeholder="Title" name="Title" required>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="simple-input2">Description </label>
                        <div class="col-md-10">
                            <input type="text" id="simple-input2" class="form-control" 
                            placeholder="Description" name="Description" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="skillSelect" name="skillName" aria-label="Floating label select example" required>
                                    <option selected>Choose Question type</option>
                                    <option value="Multiplechoice">Multiplechoice</option>
                                    <option value="Fillintheblank">Fill in the blank</option>
                                    <option value="Truefalse">True-False</option>
                                    <option value="Matching">Matching</option>
                                </select>
                                <label for="skillSelect">Skill Name</label>
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
