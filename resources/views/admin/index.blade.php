@extends('admin.layouts.master')

@section('styles')
  <style>
    .card-title { font-weight: bold;}
    .card-header { border: none;}
  </style>
@endsection


@section('content')

<div class="row">

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">آپلود فایل اکسل</h2>
      </div>
      <div class="card-body">
        <div class="row">
          <form action="{{ route('upload-file') }}" class="col-12" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="excel_file">
                    <label class="custom-file-label">Choose file</label>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <button class="btn btn-primary btn-block">آپلود</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">جستجوی پیشرفته</h2>
      </div>
      <div class="card-body">
        <div class="row">
          <form action="{{ route('dashboard') }}" class="col-12" method="GET">
            @csrf
            <div class="row">
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="بخشی از نام فایل را وارد کنید">
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <input class="form-control fc-datepicker" id="start_date_show" type="text" autocomplete="off" placeholder="تاریخ آپلود از"/>
                  <input name="start_date" id="start_date_hide" type="hidden" value="{{	request('start_date') }}"/>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <input class="form-control fc-datepicker" id="end_date_show" type="text" autocomplete="off" placeholder="تاریخ آپلود تا"/>
                  <input name="end_date" id="end_date_hide" type="hidden" value="{{	request('end_date') }}"/>
                </div>
              </div>
              <div class="col-12 col-lg-8">
                <div class="form-group">
                  <button class="btn btn-primary btn-block">جستجو <i class="fa fa-search mr-1"></i></button>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <a href="{{ route('dashboard') }}" class="btn btn-danger btn-block">حذف فیلترها <i class="fa fa-close mr-1"></i></a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <p class="card-title">لیست تمامی فایل ها ({{ $files->total() }})</p>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-striped text-nowrap text-center">
                <thead>
                <tr>
                  <th class="fs-16 font-weight-bold">ردیف</th>
                  <th class="fs-16">نام فایل</th>
                  <th class="fs-16">تعداد داده ها</th>
                  <th class="fs-16">ارسال شده</th>
                  <th class="fs-16">تاریخ آپلود</th>
                  <th class="fs-16">ساعت</th>
                  <th class="fs-16">وضعیت ارسال</th>
                  <th class="fs-16">عملیات</th>
                </tr>
                </thead>
                <tbody>
                  @forelse ($files as $file)
                    <tr>
                      <td class="font-weight-bold">{{ $loop->iteration }}</td>
                      <td>{{ $file->name }}</td>
                      <td>{{ $file->customers->count() }}</td>
                      <td>{{ $file->count_customers_sent }}</td>
                      <td>{{ verta($file->created_at)->formatDate() }}</td>
                      <td>{{ verta($file->created_at)->formatTime() }}</td>
                      <td>
                        @php( $status = $file->checkAllDataIsSend() )
                        <span @class($status['css_class'])> {{ $status['name'] }} </span>
                      </td>
                      <td>
                        <button 
                          @class(['btn', 'btn-sm', 'btn-icon', 'btn-success' => !$file->is_send, 'btn-dark' => $file->is_send])
                          class="btn btn-sm btn-icon btn-success" 
                          data-toggle="tooltip"
                          data-original-title="ارسال پیامک"
                          @disabled($file->is_send)>
                          <i class="fa fa-send"></i>
                        </button>
                        <a 
                          href="{{ route('download-file', $file) }}"
                          data-toggle="tooltip"
                          data-original-title="دانلود فایل"
                          class="btn btn-sm btn-icon btn-warning">
                          <i class="fa fa-download"></i>
                        </a>
                      <button 
                        onclick="showData({{ json_encode($file) }})"
                        class="btn btn-sm btn-icon btn-primary">
                        <i class="fa fa-eye"></i>
                      </button>
                    </td>
                    </tr>
                  @empty
                  <tr>
                    <td colspan="8">
                      <span class="text-danger font-weight-bold fs-16">هیچ داده ای یافت نشد !</span>
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="FileData" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <p id="ModalTitle" class="modal-title fs-20 font-weight-bold">داده های فایل</p>
        <button aria-label="Close" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
              <table class="table table-striped table-bordered text-nowrap text-center">
                <thead>
                <tr>
                  <th class="fs-16 font-weight-bold">ردیف</th>
                  <th class="fs-16">موبایل</th>
                  <th class="fs-16">کد رهگیری</th>
                  <th class="fs-16">وضعیت ارسال</th>
                  <th class="fs-16">عملیات</th>
                </tr>
                </thead>
                <tbody id="ModalTableBody">

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script>  
  function showData(file) {  
    let counter = 1;  
    let tableBodyData = '';
    if (file.customers.length > 0) {
      file.customers.forEach(customer => {  
        let row = `  
          <tr>  
            <td class="font-weight-bold">${counter}</td>  
            <td>${customer.mobile}</td>  
            <td>${customer.tracking_code}</td>  
            <td>  
              ${customer.is_send ? '<span class="badge badge-success-light">ارسال شده</span>' : '<span class="badge badge-danger-light">ارسال نشده</span>'}  
            </td>  
            <td>  
              <button class="btn btn-sm btn-icon btn-success" ${customer.is_send ? 'disabled' : ''}> <i class="fa fa-send"></i></button>  
            </td>  
          </tr>  
        `;  
        tableBodyData += row;  
        counter++;  
      });  
    }else {
      let row = `  
        <tr>  
          <td colspan="5" class="font-weight-bold text-danger text-center">هیچ داده ای یافت نشد !</td>  
        </tr>  
      `;
      tableBodyData += row; 
    }

    $('#ModalTableBody').html(tableBodyData);  
    $('#FileData').modal('show');  
  }  
</script>

  <script>
    $('#start_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#start_date_hide',
      targetTextSelector: '#start_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });
    $('#end_date_show').MdPersianDateTimePicker({
      targetDateSelector: '#end_date_hide',
      targetTextSelector: '#end_date_show',
      englishNumber: false,
      toDate:true,
      enableTimePicker: false,
      dateFormat: 'yyyy-MM-dd',
      textFormat: 'yyyy-MM-dd',
      groupId: 'rangeSelector1',
    });
  </script>
@endsection
