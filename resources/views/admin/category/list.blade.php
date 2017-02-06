@extends( 'Admin::layouts.default', [
	'active_admin_menu'	=> ['news', 'news.category'],
	'breadcrumbs'		=>	[
		'title' => ['Tin tức', 'Danh mục'],
		'url'	=> [
			admin_url( 'news' ),
		],
	],	
])

@section( 'page_title', 'Danh mục tin tức' )

@section( 'tool_bar' )
	<a href="{{ route('admin.news.category.create') }}" class="btn btn-primary">
		<i class="fa fa-plus"></i> Thêm danh mục mới
	</a>
@endsection

@section( 'content' )
<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-inline filter">
					<div style="display: inline" category-apply-form>
						<div class="form-group">
							<select class="form-control input-sm" category-apply-action>
								<option value="0"></option>
								<option value="">Xóa tạm</option>
							</select>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-sm" category-apply-btn>Áp dụng</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 text-right">
				{!!$categories->setPath( 'category' )->appends( $filter )->render()!!}
			</div>
		</div>
	</div>
	<div class="col-md-12 col-sm-12 table-function-container">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<table class="master-table table table-striped table-hover table-checkable order-column">
			<thead>
				<tr>
					<th width="50" class="table-checkbox text-center">
						<div class="checker">
							<span>
								<input type="checkbox" class="group-checkable">
							</span>
						</div>
					</th>
					<th class="text-center">
						{!! $category->linkSort( 'ID', 'id' ) !!}
					</th>
					<th>Thumbnail</th>
					<th>
						{!! $category->linkSort( 'Tên danh mục', 'title' ) !!}
					</th>
					<th>
						{!! $category->linkSort( 'Ngày tạo', 'created_at' ) !!}
					</th>
					<th> Thao tác </th>
				</tr>
			</thead>
			<tbody category-list>
				@foreach( $categories as $category_item )
				<tr class="odd gradeX">
					<td width="50" class="table-checkbox text-center">
						<div class="checker">
							<span>
								<input type="checkbox" class="group-checkable">
							</span>
						</div>
					</td>
					<td class="text-center">
						<strong>
							{{ $category_item->id }}
						</strong>
					</td>
					<td><img src="{{ thumbnail_url( $category_item->thumbnail, ['height' => '70', 'width' => '70'] ) }}" /></td>
					<td>
						<a href="{{ route('admin.news.category.edit', ['id' => $category_item->id]) }}">
							<strong>
								{{ $category_item->title }}
							</strong>
						</a>
						({{ $category_item->newses()->count() }} tin tức)
					</td>
					<td>
						{{ changeFormatDate( $category_item->created_at ) }}
					</td>
					<td category-action>
						<div class="btn-group" table-function>
                            <a href="" class="btn btn-circle btn-xs grey-salsa btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Chức năng
                                <span class="fa fa-angle-down"> </span>
                            </a>
                            <ul class="dropdown-menu pull-right">
                            	<li><a href="{{ route('admin.news.category.show', ['id' => $category_item->id]) }}"><i class="fa fa-eye"></i> Xem</a></li>
                                <li role="presentation" class="divider"> </li>
                                <li><a href="{{ route('admin.news.category.edit', ['id' => $category_item->id]) }}"><i class="fa fa-pencil"></i> Sửa</a></li>
                            	<li><a data-function="destroy" data-method="delete" href="{{ route('admin.news.category.destroy', ['id' => $category_item->id]) }}"><i class="fa fa-times"></i> Xóa</a></li>
                            </ul>
                        </div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="col-sm-6">
		<div class="form-inline filter">
			<div style="display: inline" category-apply-form>
				<div class="form-group">
					<select class="form-control input-sm" category-apply-action>
						<option value="0"></option>
						<option value="">Xóa tạm</option>
					</select>
				</div>
				<div class="form-group">
					<button class="btn btn-primary btn-sm" category-apply-btn>Áp dụng</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 text-right">
		{!!$categories->render()!!}
	</div>
</div>
@endsection

@push( 'css' )
	<link href="{{ url( 'assets/admin/global/plugins/bootstrap-toastr/toastr.min.css' )}}" rel="stylesheet" type="text/css" />
@endpush

@push( 'js_footer' )
	<script type="text/javascript" src="{{ url( 'assets/admin/global/plugins/bootstrap-toastr/toastr.min.js' ) }}"></script>
@endpush