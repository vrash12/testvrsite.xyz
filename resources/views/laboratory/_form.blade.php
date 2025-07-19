{{--resources/views/laboratory/_form.blade.php--}}
@extends('layouts.laboratory')

@section('content')
<div class="container-fluid py-4">

  {{-- Title --}}
  <div>
    <h5 class="hdng">Laboratory Services Management</h5>
    <p class="lead">Welcome to Laboratory! Manage Lab Charges and Service Completion</p>
  </div>

{{-- Patient & Doctor Selectors --}}
    <div class="card mb-3">
      <div class="card-body">
        <div class="row gx-3">
          {{-- Patient dropdown --}}
          <div class="col-md-6">
            <label for="patientSelect" class="form-label fw-semibold text-muted">
              <i class="fa-solid fa-user me-1 text-secondary"></i> Patient
            </label>
          <select name="search_patient" class="form-select" required>
    <option value="">– Select patient –</option>
    @foreach($patients as $p)
      <option value="{{ $p->patient_id }}" @selected(old('search_patient') == $p->patient_id)>
        {{ $p->patient_first_name }} {{ $p->patient_last_name }} (ID: {{ $p->patient_id }})
      </option>
    @endforeach
  </select>
          </div>

          {{-- Doctor dropdown --}}
          <div class="col-md-6">
            <label for="doctorSelect" class="form-label fw-semibold text-muted">
              <i class="fa-solid fa-user-doctor me-1 text-secondary"></i> Recommending Doctor
            </label>
           <select name="doctor_id" class="form-select" required>
    <option value="">– Select doctor –</option>
    @foreach($doctors as $doc)
      <option value="{{ $doc->doctor_id }}" @selected(old('doctor_id') == $doc->doctor_id)>
        Dr. {{ $doc->doctor_name }}
      </option>
    @endforeach
  </select>
          </div>
        </div>
      </div>
    </div>

  {{-- Charge Composer --}}
  <form method="POST" action="{{ route('laboratory.manual.store') }}">
    @csrf
    <div class="card border-0 mb-4">
      <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-secondary"><i class="fa-solid fa-cart-plus me-2"></i> Add Laboratory Charge</h5>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="addRowBtn">
          <i class="fa-solid fa-plus me-1"></i> Add Row
        </button>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive" style="height: 350px;">
          <table class="table table-hover align-middle mb-0" id="chargesTable">
            <thead class="table-light">
              <tr>
                <th style="width: 40%">Procedure</th>
                <th style="width: 20%" class="text-end">Unit Price</th>
                <th style="width: 20%" class="text-end">Subtotal</th>
                <th style="width: 20%" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- First row -->
              <tr>
            <td>
  <select name="charges[0][service_id]" class="form-select" required>
    <option value="">Select test…</option>
    @foreach ($services as $service)
      <option
        value="{{ $service->service_id }}"
        data-price="{{ $service->price }}"
      >
        {{ $service->service_name }}
      </option>
    @endforeach
  </select>
</td>
                <td class="text-end">
                  <input type="text" readonly class="form-control-plaintext unit text-end" value="₱0.00" />
                </td>
                <td class="text-end">
                  <input type="text" readonly class="form-control-plaintext lineTotal text-end" value="₱0.00" />
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-outline-danger removeRow">
                    <i class="fa-solid fa-xmark"></i>
                  </button>
                </td>
              </tr>
            </tbody>
            <tfoot class="table-light fw-bold sticky-bottom">
              <tr>
                <td colspan="3" class="text-end">Total</td>
                <td class="text-end text-danger" id="grandTotalCell">₱0.00</td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      {{-- Notes & Actions --}}
      <div class="card-footer bg-white">
        <div class="mb-3">
          <label class="form-label fw-semibold text-muted">Notes (Optional)</label>
          <textarea name="notes" rows="3" class="form-control" style="resize: none;"></textarea>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="{{ route('laboratory.queue') }}" class="btn btn-secondary">Cancel</a>
          <button type="submit" id="checkoutBtn" class="btn btn-primary">
            <i class="fa-solid fa-right-long me-1"></i> Next
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
  const chargesTable = document.querySelector("#chargesTable tbody");
  const addRowBtn = document.getElementById("addRowBtn");
  const grandTotalCell = document.getElementById("grandTotalCell");
  let idx = 1;

  // Add Row
  addRowBtn.addEventListener("click", () => {
    const firstRow = chargesTable.querySelector("tr");
    const newRow = firstRow.cloneNode(true);

    const select = newRow.querySelector("select");
    if (select) {
      const name = select.getAttribute("name");
      const newName = name.replace(/\[\d+\]/, `[${idx}]`);
      select.setAttribute("name", newName);
      select.selectedIndex = 0;
    }

    newRow.querySelector(".unit").value = "₱0.00";
    newRow.querySelector(".lineTotal").value = "₱0.00";

    chargesTable.appendChild(newRow);
    idx++;
  });

  // Update Unit/Subtotal
  chargesTable.addEventListener("change", (e) => {
    if (e.target.matches("select")) {
      const selectedOption = e.target.selectedOptions[0];
      const price = parseFloat(selectedOption.dataset.price) || 0;
      const row = e.target.closest("tr");

      row.querySelector(".unit").value = `₱${price.toFixed(2)}`;
      row.querySelector(".lineTotal").value = `₱${price.toFixed(2)}`;
      calcGrandTotal();
    }
  });

  // Remove Row
  chargesTable.addEventListener("click", (e) => {
    if (e.target.closest(".removeRow")) {
      const row = e.target.closest("tr");
      row.remove();
      calcGrandTotal();
    }
  });

  // Grand Total
  function calcGrandTotal() {
    let total = 0;
    chargesTable.querySelectorAll(".lineTotal").forEach((input) => {
      const val = parseFloat(input.value.replace(/[₱,]/g, "")) || 0;
      total += val;
    });
    grandTotalCell.textContent = `₱${total.toFixed(2)}`;
  }
});
</script>
@endpush
