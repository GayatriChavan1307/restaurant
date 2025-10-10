@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reception Dashboard</h1>
    <p>Live table status and ongoing orders.</p>

    <div class="row">
        <div class="col-md-7">
            <h2>Table Status</h2>
            <div class="row" id="table-status">
                @forelse($tables as $table)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 table-card status-{{ $table->status }}">
                        <div class="card-header">
                            <strong>{{ $table->name }}</strong>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-capitalize">Status: {{ $table->status }}</h5>
                            @if($table->currentOrder)
                                <p>Order #{{ $table->currentOrder->id }}
                                <br>Waiter: {{ $table->currentOrder->waiter->name }}
                                <br>Customers: {{ $table->currentOrder->customer_count }}
                                </p>
                                @if($table->currentOrder->status != 'paid' && $table->currentOrder->status != 'cancelled')
                                <a href="{{ route('reception.bill.generate', $table->currentOrder->id) }}" class="btn btn-sm btn-primary">Generate Bill</a>
                                @elseif($table->currentOrder->status == 'paid')
                                <span class="badge bg-success">PAID</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p>No tables found.</p>
                @endforelse
            </div>
        </div>
        <div class="col-md-5">
            <h2>Recent Activity / Notifications</h2>
            <ul class="list-group" id="notifications-list">
                @forelse (Auth::user()->notifications()->where('is_read', false)->take(5)->get() as $notification)
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-notification-id="{{ $notification->id }}">
                        <div>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small><br>
                            {{ $notification->message }}
                            @if($notification->link) <a href="{{ url($notification->link) }}">View</a> @endif
                        </div>
                        <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Mark as read">✓</button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item">No new notifications.</li>
                @endforelse
            </ul>
            <a href="{{ route('reception.notifications.index') }}" class="btn btn-link mt-2">View All Notifications</a>
        </div>
    </div>

    <h2 class="mt-4">Ongoing Orders</h2>
    <table class="table table-striped" id="ongoing-orders">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Table</th>
                <th>Waiter</th>
                <th>Customers</th>
                <th>Status</th>
                <th>Total</th>
                <th>Last Update</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ongoingOrders as $order)
            <tr data-order-id="{{ $order->id }}">
                <td>#{{ $order->id }}</td>
                <td>{{ $order->restaurantTable->name }}</td>
                <td>{{ $order->waiter->name }}</td>
                <td>{{ $order->customer_count }}</td>
                <td class="text-capitalize">{{ $order->status }}</td>
                <td>${{ number_format($order->total_amount, 2) }}</td>
                <td>{{ $order->updated_at->format('g:i A') }}</td>
                <td>
                    @if($order->status != 'paid' && $order->status != 'cancelled')
                    <a href="{{ route('reception.bill.generate', $order->id) }}" class="btn btn-sm btn-primary">Generate Bill</a>
                    @elseif($order->status == 'paid')
                    <a href="{{ route('reception.bill.generate', $order->id) }}" class="btn btn-sm btn-outline-success">View Bill</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8">No ongoing orders.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('styles')
<style>
    .table-card.status-available .card-header { background-color: #d1e7dd; border-color: #badbcc; }
    .table-card.status-occupied .card-header { background-color: #f8d7da; border-color: #f5c2c7; }
    .table-card.status-reserved .card-header { background-color: #fff3cd; border-color: #ffecb5; }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    if (window.Echo) {
        console.log('Laravel Echo initialized successfully.');

        let refreshTimeout = null;

        // Debounce page refresh
        function debounceRefresh() {
            console.log('debounceRefresh called');
            if (refreshTimeout) {
                console.log('Clearing existing refresh timeout');
                clearTimeout(refreshTimeout);
            }
            refreshTimeout = setTimeout(() => {
                console.log('Triggering page refresh');
                location.reload(true); // Force reload from server
            }, 1000);
        }

        window.Echo.channel('reception-notifications')
            .listen('.OrderCancelled', (e) => {
                console.log('OrderCancelled event received:', e);
                addNotification(e.message, e.link, e.created_at);
                updateTableStatus(e.order.id);
                updateOngoingOrders(e.order.id, 'cancelled');
                debounceRefresh();
            })
            .listen('.TableAssigned', (e) => {
                console.log('TableAssigned event received:', e);
                addNotification(e.message, e.link, e.created_at);
                updateTableStatus(e.order.id);
                updateOngoingOrders(e.order.id, null);
                debounceRefresh();
            })
            .listen('.OrderUpdated', (e) => {
                console.log('OrderUpdated event received:', e);
                addNotification(e.message, e.link, e.created_at);
                updateOngoingOrders(e.order_id, null);
                debounceRefresh();
            });

        function addNotification(message, link, created_at) {
            console.log('Adding notification:', { message, link, created_at });
            const notificationList = document.getElementById('notifications-list');
            const noNotifications = notificationList.querySelector('li')?.textContent === 'No new notifications';
            if (noNotifications) {
                notificationList.innerHTML = '';
            }

            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.innerHTML = `
                <div>
                    <small>${moment(created_at).fromNow()}</small><br>
                    ${message}
                    ${link ? `<a href="${link}">View</a>` : ''}
                </div>
                <form action="{{ route('notifications.mark-as-read', 'temp') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Mark as read">✓</button>
                </form>
            `;
            notificationList.prepend(li);
        }

        function updateTableStatus(orderId) {
            console.log('Updating table status for order ID:', orderId);
            fetch('/reception/tables')
                .then(response => response.json())
                .then(tables => {
                    const tableStatus = document.getElementById('table-status');
                    tableStatus.innerHTML = tables.map(table => `
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 table-card status-${table.status}">
                                <div class="card-header">
                                    <strong>${table.name}</strong>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-capitalize">Status: ${table.status}</h5>
                                    ${table.current_order ? `
                                        <p>Order #${table.current_order.id}
                                        <br>Waiter: ${table.current_order.waiter_name}
                                        <br>Customers: ${table.current_order.customer_count}
                                        </p>
                                        ${table.current_order.status !== 'paid' && table.current_order.status !== 'cancelled' ? `
                                            <a href="/reception/orders/${table.current_order.id}/bill" class="btn btn-sm btn-primary">Generate Bill</a>
                                        ` : table.current_order.status === 'paid' ? `
                                            <span class="badge bg-success">PAID</span>
                                        ` : ''}
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(error => console.error('Error updating table status:', error));
        }

        function updateOngoingOrders(orderId, status) {
            console.log('Updating ongoing orders for order ID:', orderId, 'Status:', status);
            fetch('/reception/orders')
                .then(response => response.json())
                .then(orders => {
                    const tbody = document.querySelector('#ongoing-orders tbody');
                    tbody.innerHTML = orders.length ? orders.map(order => `
                        <tr data-order-id="${order.id}">
                            <td>#${order.id}</td>
                            <td>${order.table_name}</td>
                            <td>${order.waiter_name}</td>
                            <td>${order.customer_count}</td>
                            <td class="text-capitalize">${order.status}</td>
                            <td>$${parseFloat(order.total_amount).toFixed(2)}</td>
                            <td>${moment(order.updated_at).format('g:i A')}</td>
                            <td>
                                ${order.status !== 'paid' && order.status !== 'cancelled' ? `
                                    <a href="/reception/orders/${order.id}/bill" class="btn btn-sm btn-primary">Generate Bill</a>
                                ` : order.status === 'paid' ? `
                                    <a href="/reception/orders/${order.id}/bill" class="btn btn-sm btn-outline-success">View Bill</a>
                                ` : ''}
                            </td>
                        </tr>
                    `).join('') : '<tr><td colspan="8">No ongoing orders.</td></tr>';
                })
                .catch(error => console.error('Error updating ongoing orders:', error));
        }
    } else {
        console.error('Laravel Echo is not initialized. Check bootstrap.js and Reverb configuration.');
    }
</script>
@endpush