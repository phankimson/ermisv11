@extends('layouts.default')
@section('title',  $title )
@push('css_up')
<link rel="stylesheet" href="{{ asset('addon/css/permission.css') }}">
<link rel="stylesheet" href="{{ asset('library/uniform/dist/css/default.css') }}" type="text/css" media="screen" charset="utf-8" />
@endpush

@push('css_down')

@push('action')
@include('action.action_1')
@endpush

@endpush

@section('content')
<div id="page_content">
<div id="page_content_inner">
<div class="md-card uk-margin-medium-bottom">
    <div class="md-card-toolbar">
        <div class="md-card-toolbar-heading-text">
            <a class="save"><i class="md-icon material-icons" data-uk-tooltip title="@lang('action.save')">save</i></a>
            <a class="cancel"><i class="md-icon material-icons" data-uk-tooltip title="@lang('action.cancel')">cancel</i></a>
        </div>
        <select placeholder="@lang('global.select') ..." id="user" class="uk-form-width-medium">
            <option value="" hidden>@lang('global.select') ...</option>
            @foreach($users_com as $u)
            <option value="{{ $u->id }}">{{ $u->username }}</option>
            @endforeach
        </select>
        <select placeholder="@lang('global.select') ..." id="group" class="uk-form-width-medium">
            <option value="" hidden>@lang('global.select') ...</option>
            @foreach($group_users as $u)
            <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
        </select>
        <div class="md-card-toolbar-actions">
        <i class="md-icon material-icons md-card-fullscreen-activate toolbar_fixed" data-uk-tooltip title="@lang('index.zoom')"></i>
        <i class="md-icon material-icons refesh" data-uk-tooltip title="@lang('global.refesh')"></i>
            <div class="md-card-dropdown" data-uk-dropdown="{pos:'bottom-right'}" aria-haspopup="true" aria-expanded="false">
                <i class="md-icon material-icons"></i>
                <div class="uk-dropdown uk-dropdown-bottom" aria-hidden="true" tabindex="" style="min-width: 200px; top: 32px; left: -168px;">
                    <ul class="uk-nav">
                        <li><a href="javascript:;" class="save">@lang('action.save')</a></li>
                        <li><a href="javascript:;" class="cancel">@lang('action.cancel')</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="md-card-content">

        <div id="notification"></div>
        <table class="uk-table">
            <thead>
                <tr>
                    <th>@lang('permission.name')</th>
                    <th>@lang('action.view')</th>
                    <th>@lang('action.add')</th>
                    <th>@lang('action.edit')</th>
                    <th>@lang('action.delete')</th>
                </tr>
            </thead>
            <tbody>
                <tr class="filter-all">
                    <td><a href="javascript:;" class="group"><i class="material-icons">remove</i>@lang('permission.all')</a></td>
                    <td><input type="checkbox" class="all" name="v"/></td>
                    <td><input type="checkbox" class="all" name="a" /></td>
                    <td><input type="checkbox" class="all" name="e"/></td>
                    <td><input type="checkbox" class="all" name="d"/></td>
                </tr>

                @if($menu->count()>0)
                  @foreach($menu as $m)
                    @if($m->link == "")
                        <tr><td colspan="7" style="text-align:center; text-transform: uppercase;font-weight:bold">{{ $lang == 'vi'? $m->name : $m->name_en }}</td></tr>
                        @foreach($m->sub_menu as $m1)
                                <tr class="group">
                                    <td><a href="javascript:;" class="group_click" data-group="{{ $m1->id }}"><i class="material-icons">remove</i>{{ $lang == 'vi'? $m1->name : $m1->name_en }}</a></td>
                                    <td><input type="checkbox" class="checkbox-group" name="v-{{ $m1->id }}" /></td>
                                    <td><input type="checkbox" class="checkbox-group" name="a-{{ $m1->id }}" /></td>
                                    <td><input type="checkbox" class="checkbox-group" name="e-{{ $m1->id }}" /></td>
                                    <td><input type="checkbox" class="checkbox-group" name="d-{{ $m1->id }}" /></td>
                                </tr>
                                @if($m1->sub_menu1->count()>0)
                                   @foreach($m1->sub_menu1 as $m2)
                                    <tr class="item" data-group="{{ $m2->parent_id }}" data-id="{{ $m2->id }}">
                                        <td>{{ $lang == 'vi'? $m2->name : $m2->name_en }}</td>
                                        <td><input type="checkbox" class="checkbox-item" data-group="v-{{ $m2->parent_id }}" name="v-{{ $m2->id }}" /></td>
                                        <td><input type="checkbox" class="checkbox-item" data-group="a-{{ $m2->parent_id }}" name="a-{{ $m2->id }}" /></td>
                                        <td><input type="checkbox" class="checkbox-item" data-group="e-{{ $m2->parent_id }}" name="e-{{ $m2->id }}" /></td>
                                        <td><input type="checkbox" class="checkbox-item" data-group="d-{{ $m2->parent_id }}" name="d-{{ $m2->id }}" /></td>
                                    </tr>
                                  @endforeach
                                @else
                                <tr class="item" data-id="{{ $m1->id }}">
                                    <td><a href="javascript:;" data-group="{{ $m1->id }}">{{ $lang == 'vi'? $m1->name : $m1->name_en }}</a></td>
                                    <td><input type="checkbox" class="checkbox-item" name="v-{{ $m1->id }}" /></td>
                                    <td><input type="checkbox" class="checkbox-item" name="a-{{ $m1->id }}" /></td>
                                    <td><input type="checkbox" class="checkbox-item" name="e-{{ $m1->id }}" /></td>
                                    <td><input type="checkbox" class="checkbox-item" name="d-{{ $m1->id }}" /></td>
                                </tr>

                                @endif
                          @endforeach
                        @else
                        <tr class="item" data-id="{{ $m->id }}">
                            <td><a href="javascript:;" data-group="{{ $m->id }}">{{ $lang == 'vi'? $m->name : $m->name_en }}</a></td>
                            <td><input type="checkbox" class="checkbox-item" name="v-{{ $m->id }}" /></td>
                            <td><input type="checkbox" class="checkbox-item" name="a-{{ $m->id }}" /></td>
                            <td><input type="checkbox" class="checkbox-item" name="e-{{ $m->id }}" /></td>
                            <td><input type="checkbox" class="checkbox-item" name="d-{{ $m->id }}" /></td>
                        </tr>
                       @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
@endsection
@push('js_up')

@endpush

@push('js_down')
<script>
  jQuery(document).ready(function () {
      Ermis.per = <?= json_encode($per);?>;
      Ermis.link = "{{$key}}";
      Ermis.elem = "";
      Ermis.short_key = "{{ config('app.short_key')}}";
  });
  </script>
<script src="{{ url('library/kendoui/js/jszip.min.js') }}"></script>
<script src="{{ url('library/shortcuts/shortcuts.js') }}"></script>
<script src="{{ url('library/uniform/dist/js/jquery.uniform.standalone.js') }}"></script>
<script src="{{ url('addon/scripts/ermis/ermis-3.js') }}"></script>
@endpush
