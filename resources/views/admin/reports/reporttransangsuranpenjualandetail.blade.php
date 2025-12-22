@extends('layouts.app')
@push('style')


  <!-- daterange picker -->
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}">

  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
  <link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">

  <link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- daterange picker -->
  <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/dark-hive/jquery-ui.css"> -->
  <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css"> -->

  <style>
	.status
	{
		position:absolute;
        /* top: 270px; */
		left: 250px;
        bottom: 65px;
        width: 300px;
        height: 70px;
		z-index: 2;
        opacity: 0.3;
        transform: rotate(340deg);
	}

    .tes{
        padding-top:0px;
        padding-left:50px;
        margin-top:-10px !important;
    }
    .garis
    {
        border-collapse: collapse;
        border: 1px solid black;
    }


    .logorg
	{

        width: 45px;
        height: 40px;
		z-index: 2;
	}
    .lebarkwitansi
    {
        width: 21cm;
        margin: auto;
        align: "center";
    }
  </style>
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">

  <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">
@endpush

<!-- hagan di detail angsuran nya lah, yang bagian satu persatu -->

@section('body')
    <body class="lebarkwitansi">
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                <img src="{{asset('dist/img/rg.png')}}" class="logorg"> <strong>RESTU GURU PROMOSINDO</strong> Cab. {{$transaksi->Nama_Cabang}}
                <small class="pull-right" style="margin-top:5px !important;"> Pelunasan Transaksi Det. Penjualan</small>
                <br/>
                    <small class="tes">Alamat: {{$transaksi->Alamat}}, No. Telp: {{$transaksi->No_Telepon}}</small>
                </h2>
            </div>
            <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                Dari
                <address>
                <strong>{{$transaksi->nama}}</strong><br>
                {{$transaksi->display_name}}<br>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                Kepada
                <address>
                <strong>{{$transaksi->nama_pelanggan}}</strong><br>
                {{$transaksi->hp_pelanggan}}
                @if ($transaksi->pelanggan_id=="")
                @else
                    ({{$transaksi->jenis_pelanggan}})
                @endif

                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>No. #{{$transaksi->id}}</b><br>
                <b>Tanggal :</b> {{date("d-m-Y",strtotime($transaksi->created_at))}}<br>
                <b>Jam :</b> {{date("H:i:s",strtotime($transaksi->created_at))}}
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
            
            <div class="col-xs-12 table-responsive">
                <table class="table garis table-bordered table-striped strong" width="100%">
                <thead>
                    <tr>
                        <th style="word-wrap: break-word;">Produk</th>
                        <th style="word-wrap: break-word;">Harga</th>
                        <th style="word-wrap: break-word;">P</th>
                        <th style="word-wrap: break-word;">L</th>
                        <th style="word-wrap: break-word;">Kuantitas</th>
                        <th style="word-wrap: break-word;">Finishing</th>
                        <th style="word-wrap: break-word;">Keterangan</th>
                        <th style="word-wrap: break-word;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subtransaksis as $subtransaksi)
                    <tr>
                        <td style="word-break: break-all;">{{$subtransaksi->nama_produk}}</td>
                        <td style="word-wrap: break-word;">Rp. {{number_format(floatval($subtransaksi->harga_satuan),0,',','.')}}</td>
                        <td style="word-wrap: break-word;">{{number_format(floatval($subtransaksi->panjang),2,',','.')}}</td>
                        <td style="word-wrap: break-word;">{{number_format(floatval($subtransaksi->lebar),2,',','.')}}</td>
                        <td style="word-wrap: break-word;">{{number_format(floatval($subtransaksi->banyak),0,',','.')}}</td>
                        <td style="word-wrap: break-word;">{{$subtransaksi->finishing}}</td>
                        <td style="width: 20%;word-break: break-all;">{{$subtransaksi->keterangan}}</td>
                        <td style="word-wrap: break-word;">Rp. {{number_format(floatval($subtransaksi->subtotal),0,',','.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">

            <div class="col-xs-4">

                <table class="table no-border">
                    <thead>
                        <th><center>Penerima</center></th>
                        <th><center>Pembuat</center></th>
                    </thead>
                    <tbody>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr>
                            <td><center>{{$transaksi->nama_pelanggan}}</center></td>
                            <td><center>{{$transaksi->nama}}</center></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- accepted payments column -->
            <div class="col-xs-4">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>No. Pelunasan:</th>
                            <td>#{{$angsuran->id}}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pelunasan</th></th>
                            <td>{{date("d-m-Y",strtotime($angsuran->created_at))}}</td>
                        </tr>
                        <tr>
                            <th>Jam Pelunasan</th></th>
                            <td>{{date("H:i:s",strtotime($angsuran->created_at))}}</td>
                        </tr>
                    </table>
                </div>

                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                "Harap cek kembali orderan anda."
                </p>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Total :</th>
                            <td>Rp. {{number_format(floatval($transaksi->total_harga),0,',','.')}}</td>
                        </tr>
                        <tr>
                            <th>Pelunasan:</th>
                            <td>Rp. {{number_format(floatval($angsuran->nominal_angsuran),0,',','.')}}</td>
                        </tr>
                        <tr>
                            <th>Sisa:</th>
                            <td>Rp. {{number_format(floatval($transaksi->total_harga - $jumlahangsuran->totalangsuran),0,',','.')}}</td>
                        </tr>
                        <tr>
                            <th>Metode:</th>
                            <td>{{$angsuran->metode_pembayaran}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @if ($transaksi->sisa_tagihan==0)
                <img src="{{asset('dist/img/brush_lunas.png')}}" class="status">
            @endif

            <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <!-- DataTables -->
    <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- FastClick -->

    <script src="{{asset('bower_components/jquery-ui/jquery-ui-new.js')}}"></script>

    <!-- iCheck 1.0.1 -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>

    <!-- bootstrap datepicker -->
    <script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>


    <script src="{{asset('bower_components/jquery-maskmoney/jquery.maskMoney.js')}}"></script>
    <!-- <script src="{{asset('bower_components/jquery-number/jquery.number.js')}}"></script> -->

    <script src="{{asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->


    </body>
@endsection
