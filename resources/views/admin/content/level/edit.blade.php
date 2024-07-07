@extends('admin/layouts/contentLayoutMaster')

@section('title', 'Cáp độ')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.level.update', ['level' => $data->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label class="col-form-label" for="name">Tên cấp độ </label>
                            </div>
                            <div class="col-sm-9">
                                <div class="input-group input-group-merge">
                                    <input type="text" id="name" class="form-control" name="name"
                                           placeholder="" value="{{ old('name', $data->name) }}">
                                </div>
                                @error('name')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 row">
                            <div class="col-sm-3">
                                <label for="level_image" class="col-form-label">Ảnh cấp độ</label>
                            </div>
                            <div class="col-sm-9">
                                <input class="form-control" type="file" name="level_image" id="level_image" accept="image/png, image/gif, image/jpeg"
                                       placeholder="Vui lọng chọn file">
                                @error('level_image')
                                <span class="form-text text-danger">{{ $message }}</span>
                                @enderror
                                <div class="d-flex overflow-scroll" id="preview_logo">
                                    <div style="width: 110px;height: 160px;border: 1px solid #ccc;margin:5px;position: relative">
                                        <div style="width: 110px;height: 110px;padding:5px">
                                            <image style="object-fit: contain;max-height: 100%;max-width: 100%" alt="{{ $data->name }}" src="{{ \Illuminate\Support\Facades\Storage::url($data->level_image) }}"/>
                                        </div>
                                        <div style="width: 110px;border-top: 1px solid #ccc;padding-left: 3px;padding-top: 5px">
                                            <div
                                                style="text-overflow: ellipsis;height: 20px;line-height: 20px;overflow: hidden;white-space: nowrap;font-weight: bold">{{ $data->level_image }}</div>
                                            <small style="line-height: 20px"></small>
                                        </div>
                                    </div>
                                </div>
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
            // $('input[name="logo"]').on('change', function () {
            //     ProSell.readURL(this, 'logo')
            // })
            $('input[name="image"]').on('change', function () {
                ProSell.readURL(this, 'image')
            })
        })
    </script>
@endsection

