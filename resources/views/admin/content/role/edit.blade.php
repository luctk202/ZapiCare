@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Sửa nhóm')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.role.update',['role' => $role->id]) }}">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Tên nhóm</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="name" class="form-control" name="name"
                                       placeholder="" value="{{ old('name', $role->name) }}">
                                @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="pass-icon">Danh sách quyền</label>
                            </div>
                            <div class="col-sm-9 row">
                                @foreach($permissions as $key => $permission)
                                    <div class="col-sm-6 pt-1">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="permissions[]" type="checkbox" id="permission_{{$key}}" value="{{ $key }}" @if(in_array($key, old('permissions', $role_permissions))) checked @endif>
                                            <label class="form-check-label" for="permission_{{$key}}">{{ $permission }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary me-1 waves-effect waves-float waves-light">Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('page-script')
    <script type="text/javascript">
        $(document).ready(function () {

        })
    </script>
@endsection

