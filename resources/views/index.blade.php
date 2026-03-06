@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboards</h4>
        </div>
    </div>
</div>

<div class="row project-wrapper">
    <!-- City Filter Section -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-filter me-2 text-primary"></i>
                            Filter by City
                        </h5>
                        <p class="text-muted mb-0 mt-1">Select a city to filter dashboard data</p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="city" class="form-label me-3 mb-0 fw-semibold">City:</label>
                            <div class="flex-grow-1">
                                <select name="city" id="city" class="form-select form-select-lg shadow-sm border-0 bg-light text-dark">
                                    @if(auth()->user()->type != 'dealer')
                                    <option value="all" {{ request()->query('city') == 'all' ? 'selected' : '' }}>All Cities</option>
                                    @endif
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" {{ request()->query('city') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary ms-3 px-4" onclick="applyFilter()">
                                <i class="fas fa-search me-2"></i>
                                Apply Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-xl-4">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium mb-3">Future Appointments (Next 3 Days)</p>
                                <div class="d-flex align-items-center mb-3">
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Freelancer/Partner</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($futureAppointments as $appointment)
                                                <tr>
                                                    <td>{{ $appointment['date'] }}</td>
                                                    <td>{{ $appointment['user_name'] }}</td>
                                                    <td>{{ $appointment['partner_name'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">No upcoming appointments</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <a class="text-center" href="{{ route('report.appointments.upcoming') }}">View More <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @if(in_array(Auth::user()->type, ['admin', 'dealer']))
                <div class="card card-animate">
                    <div class="card-body">
                        <canvas id="ordersPie"></canvas>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-xl-12 mb-3">
                        <form action="">
                        @foreach(request()->except(['start_date','end_date','graph']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <div class="row">
                                <div class="col-xl-3">
                                    <label>Start Date</label>
                                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date', \carbon\Carbon::now()->startOfMonth()->format('Y-m-d')) }}">
                                </div>
                                <div class="col-xl-3">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date', \carbon\Carbon::now()->endOfMonth()->format('Y-m-d')) }}">
                                </div>
                                <div class="col-xl-2">
                                    <label>Graph Timeframe</label>
                                    <select class="form-select" name="graph">
                                        <option value="Weekly" {{ request('graph', 'Weekly') === 'Weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="Monthly" {{ request('graph') === 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="Yearly" {{ request('graph') === 'Yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                </div>
                                <div class="col-xl-4">
                                    <br>
                                    <button class="btn btn-primary mt-1">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(in_array(Auth::user()->type, ['admin', 'dealer']))
                    <div class="col-xl-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <canvas id="ordersBar"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <canvas id="ordersLine"></canvas>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('users') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">New Users</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $todayUsersCount }}">{{ $todayUsersCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('salons') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">New Partners</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $todaySalonCount }}">{{ $todaySalonCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('freelancers') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">New Freelancers</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $todayFreelancerCount }}">{{ $todayFreelancerCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('users') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Total Users</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $usersCount }}">{{ $usersCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
            
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('freelancers') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Total Freelancers</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $freelancerCount }}">{{ $freelancerCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
            
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('salons') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Total Partners</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $salonCount }}">{{ $salonCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
            
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('salon.appointments') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden ms-3">
                                                <p class="text-uppercase fw-medium text-truncate mb-3">Partner Appointments</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $salonAppointmentsCount }}">{{ $salonAppointmentsCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
            
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('freelancer.appointments') }}">
                                    <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden ms-3">
                                                    <p class="text-uppercase fw-medium text-truncate mb-3">Freelancer Appointments</p>
                                                    <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $freelancerAppointmentsCount }}">{{ $freelancerAppointmentsCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
            
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('report.appointments')}}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Total Appointments</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $totalAppointmentsCount }}">{{ $totalAppointmentsCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
            
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('salon.orders') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Partner Orders</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $salonOrdersCount }}">{{ $salonOrdersCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('freelancer.orders') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Freelancer Orders</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $freelancerOrdersCount }}">{{ $freelancerOrdersCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('shop.orders') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Total Orders</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $totalOrdersCount }}">{{ $totalOrdersCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @if(in_array(Auth::user()->type, ['admin', 'dealer']))
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('banners') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Total Ads</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $adsCount }}">{{ $adsCount }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('report.appointments') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Appointments Income</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $appointmentsIncome }}">{{ number_format($appointmentsIncome,2) }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('shop.orders') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Product Orders Income</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $productOrdersIncome }}">{{ number_format($productOrdersIncome,2) }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <a href="{{ route('banners') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Ads Income</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="{{ $adsIncome }}">{{ number_format($adsIncome,2) }}</span></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="col-xl-6">
                            <div class="card card-animate">
                                <a href="{{ route('salons') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Partners Subscription</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0">{{ $upgradedSalonsCount }} / {{ $salonCount }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card card-animate">
                                <a href="{{ route('freelancers') }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium mb-3">Freelancer Subscription</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 class="fs-4 flex-grow-1 mb-0">{{ $upgradedFreelancerCount }} / {{ $freelancerCount }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for enhanced styling -->
<style>
    .form-select-lg {
        font-size: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .form-select-lg:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        border-color: #0d6efd;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.75rem;
    }
    
    .btn-primary {
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .text-primary {
        color: #0d6efd !important;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>

<!-- JavaScript for filter functionality -->
<script>
    function applyFilter() {
        const selectedCity = document.getElementById('city').value;
        const cityName = document.getElementById('city').options[document.getElementById('city').selectedIndex].value;

        console.log(selectedCity);
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('city', selectedCity);
        window.location.href = currentUrl.toString();
    }
    
    // Add event listener for Enter key on select
    document.addEventListener('DOMContentLoaded', function() {
        const citySelect = document.getElementById('city');
        citySelect.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilter();
            }
        });
    });

    const pieLabels = ['Ads', 'Appointments', 'Orders'];
    const pieData = [{{ $adsIncome }}, {{ $appointmentsIncome }}, {{ $productOrdersIncome }}];
    const multiLabels = @json($weekDays);
    const ordersSeries = @json($weeklyOrders);
    const appointmentsSeries = @json($weeklyAppointments);
    const adsSeries = @json($weeklyAds);
    const appointmentsIncomeSeries = @json($weeklyAppointmentsIncome);
    const adsIncomeSeries = @json($weeklyAdsIncome);
    const ordersIncomeSeries = @json($weeklyOrdersIncome);

    new Chart(document.getElementById('ordersPie'), {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                label: ['Ads', 'Appointments', 'Orders'],
                data: pieData,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: "Income Distribution - {{ request()->query('graph') ? request()->query('graph') : 'All time' }}"
                }
            }
        },
    });
    new Chart(document.getElementById('ordersBar'), {
        type: 'bar',
        data: {
            labels: multiLabels,
            datasets: [
                {
                    label: 'Orders',
                    data: ordersIncomeSeries,
                    backgroundColor: '#60a5fa',
                },
                {
                    label: 'Appointments',
                    data: appointmentsIncomeSeries,
                    backgroundColor: '#f59e0b',
                },
                {
                    label: 'Ads',
                    data: adsIncomeSeries,
                    backgroundColor: '#4bc0c0',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                x: {
                    stacked: false,
                },
                y: {
                    beginAtZero: true
                }
            }
        },
    });
    new Chart(document.getElementById('ordersLine'), {
        type: 'line',
        data: {
            labels: multiLabels,
            datasets: [
                {
                    label: 'Orders',
                    data: ordersSeries,
                    backgroundColor: '#60a5fa',
                },
                {
                    label: 'Appointments',
                    data: appointmentsSeries,
                    backgroundColor: '#f59e0b',
                },
                {
                    label: 'Ads',
                    data: adsSeries,
                    backgroundColor: '#4bc0c0',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    beginAtZero: true
                }
            }
        },
    });
</script>

@endsection
