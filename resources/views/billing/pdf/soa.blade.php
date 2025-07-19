<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Statement of Account</title>
  <style>
    body { font-family: sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 1em; }
    th, td { border: 1px solid #ccc; padding: 4px; text-align: left; }
  </style>
</head>
<body>
  <h2>Statement of Account</h2>
  <p>
    <strong>Patient:</strong> {{ $patient->patient_first_name }} {{ $patient->patient_last_name }}<br>
    <strong>ID:</strong> {{ str_pad($patient->patient_id,8,'0',STR_PAD_LEFT) }}
  </p>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Description</th>
        <th class="text-end">Amount (₱)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($charges as $item)
        <tr>
          <td>{{ $item->billing_date->format('Y-m-d') }}</td>
          <td>{{ optional($item->service)->service_name }}</td>
          <td class="text-end">
            {{ number_format($item->amount - ($item->discount_amount ?? 0), 2) }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
