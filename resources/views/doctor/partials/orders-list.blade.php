<table class="table">
  <thead>
    <tr>
      <th>Date/Time</th>
      <th>Type</th>
      <th>Item</th>
      <th>Qty</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($serviceOrders as $o)
      <tr>
        <td>{{ $o->datetime->format('Y-m-d H:i') }}</td>
        <td>{{ ucfirst($o->service->service_type) }}</td>
        <td>{{ $o->service->service_name }}</td>
        <td>—</td>
        <td>{{ $o->service_status }}</td>
      </tr>
    @endforeach

    @foreach($medOrders as $m)
      <tr>
        <td>{{ \Carbon\Carbon::parse($m->datetime)->format('Y-m-d H:i') }}</td>
        <td>Medication</td>
        <td>{{ $m->service->service_name }}</td>
        <td>{{ $m->quantity_asked }}</td>
        <td>{{ ucfirst($m->status) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
