@extends('Cms::layouts.default', [
	'active_admin_menu'	=> ['news', 'news.category'],
	'breadcrumbs'		=>	[
		'title' => ['Tin tức', 'Danh mục'],
		'url'	=> [
			admin_url('news'),
		],
	],	
])

@section('page_title', 'Danh mục tin tức')

@section('tool_bar')
	@can('admin.news.category.create')
		<a href="{{ route('admin.news.category.create') }}" class="btn btn-primary">
			<i class="fa fa-plus"></i> <span class="hidden-xs">Thêm danh mục mới</span>
		</a>
	@endcan
@endsection

@section('content')
<div class="table-function-container">
	<div class="note note-success">
		<p><i class="fa fa-info"></i> Tổng số {{ $categories->count() }} kết quả</p>
	</div>
	<div class="row table-above">
		<div class="col-sm-6">
			<div class="form-inline mb-10 apply-action">
				@include('Cms::components.form-apply-action', [
					'actions' => [
						['action' => '', 'name' => 'Xóa vĩnh viễn', 'method' => 'DELETE'],
					],
				])
				</div>
		</div>
		<div class="col-sm-6 text-right">
			{!!$categories->setPath('category')->appends($filter)->render()!!}
		</div>
	</div>
	<div class="table-responsive main">
		<table class="master-table table table-striped table-hover table-checkable order-column">
			<thead>
				<tr>
					<th width="50" class="table-checkbox text-center">
						<div class="checker">
							<input type="checkbox" class="icheck check-all">
						</div>
					</th>
					<th class="text-center">
						{!! $category->linkSort('ID', 'id') !!}
					</th>
					<th>Thumbnail</th>
					<th>
						{!! $category->linkSort('Tên danh mục', 'title') !!}
					</th>
					<th>
						{!! $category->linkSort('Ngày cập nhật', 'updated_at') !!}
					</th>
					<th> Thao tác </th>
				</tr>
			</thead>
			<tbody class="pb-items">
				@include('News::admin.components.category-table-item', [
					'categories' => $categories,
				])
			</tbody>
		</table>
	</div>
</div>
@endsection

@push('css')
	<link href="{{ asset_url('admin', 'global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset_url('admin', 'global/plugins/icheck/skins/all.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js_footer')
	<script type="text/javascript" src="{{ asset_url('admin', 'global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset_url('admin', 'global/plugins/icheck/icheck.min.js')}} "></script>
@endpush