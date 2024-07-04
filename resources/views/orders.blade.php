<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Kasir | Order</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animated.css') }}">
</head>
<body>
    <div class="container my-3">
        <h1>Order Table</h1>
    </div>
    <div class="container my-3">
        <div class="col-lg-12">
            <div class="cashier-details">
                @foreach($orders as $order)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Order #{{ $order->id }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User ID</th>
                                        <th>Total Harga</th>
                                        <th>Tanggal Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user_id }}</td>
                                        <td>Rp{{ number_format($order->total_harga, 2, ',', '.') }}</td>
                                        <td>{{ $order->tanggal_order }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h6 class="mt-4">Order Details</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Kuantitas</th>
                                        <th>SubTotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderDetails as $detail)
                                    <tr>
                                        <td>{{ $detail->KodeBarang }}</td>
                                        <td>{{ $detail->NamaBarang }}</td>
                                        <td>{{ $detail->Kuantitas }}</td>
                                        <td>Rp{{ number_format($detail->SubTotal, 2, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="button-container">
        <a href="{{ url('kasir') }}" id="form-submit" class="main-button mt-3 btn btn-primary">Back</a>
    </div>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/isotope.min.js') }}"></script>
    <script src="{{ asset('js/owl-carousel.js') }}"></script>
    <script src="{{ asset('js/counter.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>