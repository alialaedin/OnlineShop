<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">فیلتر ها</div>
                <div class="card-options">
                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                            class="fe fe-chevron-up"></i></a>
                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i
                            class="fe fe-maximize"></i></a>
                    <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                </div>
            </div>

            <div class="card-body">
                <form class="form-inline" action="{{ $action }}" method="get" id="filter-form">
                    @foreach($inputs as $input => $value)
                        @if(in_array($value['type'], ['text', 'number', 'email']))
                            <div class="form-group ml-2">
                                <input type="{{ $value['type'] }}" name="{{ $input }}" class="form-control"
                                       placeholder="{{ $value['placeholder'] }}" value="{{ request($input) }}">
                            </div>
                        @endif
                        @if(in_array($value['type'], ['select', 'select-multiple']))
                            <div class="form-group ml-2">
                                <select
                                    class="form-control @if($value['type'] == 'select-multiple') select2-show-search @endif"
                                    name="{{ $input }}" @if($value['type'] == 'select-multiple') multiple @endif>
                                    <option class="text-muted" value="">{{ $value['placeholder'] }}</option>
                                    @foreach($value['options'] as $option => $label)
                                        <option
                                            value="{{ $option }}" @selected(request($input) == $option)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if($value['type'] == 'date')
                            <div class="form-group ml-2">
                                <input type="{{ $value['type'] }}" name="{{ $input }}" class="form-control"
                                       placeholder="{{ $value['placeholder'] }}" id="{{ $input }}" value="{{ request($input) }}">
                                <span id="span_{{ $input }}"></span>
                            </div>
                        @endif
                    @endforeach
                </form>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-10">
                        <button type="submit" class="btn btn-primary btn-sm btn-block" form="filter-form">جستجو</button>
                    </div>
                    <div class="col-2">
                        <a href="{{ $action }}" class="btn btn-danger btn-sm btn-block">حذف فیلترها <i
                                class="fa fa-close"
                                aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
