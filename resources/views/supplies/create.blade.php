{{-- resources/views/supplies/create.blade.php --}}

@extends('layouts.supplies')

@section('content')

<div class="container-fluid py-4 border h-100 d-flex flex-column" style="background-color: #fafafa">

    {{-- Top --}}
    <div>
    <h1 class="hdng">Add New Miscellaneous Charge</h1>
    <p>Add new manual charge to patients</p>

    <form method="POST" action="route{{  }}">
        @csrf

        {{-- Select Patient --}}
        <div class="mb-3">
            <label class="form-label">Patient</label>
            <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                <option value="">Select patient...</option>
                @foreach ($patients as $p)
                    <option value="{{ $p->patient_id }}" 
                        @selected(old('patient_id')==$p->patient_id)>
                        {{ $p->patient_first_name }} {{ $p->patient_last_name }}
                    </option>
                @endforeach
            </select>
            @error('patient_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="border p-3 my-3 flex-grow-1 d-flex flex-column" style="max-height: 350px; overflow-y: auto;">

          {{-- Misc Dynamic --}}
        <div id="misc-list">
            <div class="misc-item mt-1 border p-3">
                <h6>Item #1</h6>
                <div class="row gx-4">
                    <div class="col-md-5">
                        <label class="form-label">Item</label>
                        <select name="misc_item[0][service_id]" class="form-select @error('misc_item.0.service_id') is-invalid @enderror" required>
                            <option value="">Select Item</option>
                            @foreach ($services as $s)
                                <option value="{{ $s->service_id }}" data-price="{{ $s->price }}">
                                    {{ $s->department->department_name }} – ₱ {{ number_format($s->price,2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('misc_item.0.service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="misc_item[0][quantity]" min="1" class="form-control @error('misc_item.0.quantity') is-invalid @enderror">
                        @error('misc_item.0.quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Unit Price</label>
                        <input type="text" readonly class="form-control-plaintext border border-info">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total</label>
                        <input type="text" readonly class="form-control-plaintext border  border-warning">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn  btn-danger remove-supply"><i class="fa-solid fa-xmark me-2"></i>Remove</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Add Item Button --}}
    <button type="button" id="add-item" class="btn btn-sm btn-outline-secondary">+ Add Item</button>
    
    {{-- Bottom --}}
    <div class="mt-auto">
        {{-- Notes --}}
        <div class="mb-3">
            <label class="form-label">Notes (Optional)</label>
            <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" style="resize: none;"></textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Total Display --}}
        <div class="text-end">
            <strong>Total Amount:</strong> ₱<span id="grand-total">0.00</span>
        </div>

        {{-- Actions --}}
        <div class="text-end">
            <a href="{{ route('supplies.dashboard') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Checkout</button>
        </div>
    </div>
    </form>
</div>

{{-- Script --}}
@push('scripts')
    <script>

        let idx = 1;

        const addBtn = document.getElementById('add-item');
        const miscList = document.getElementById('misc-list');

        // Adds new Item
        addBtn.addEventListener('click', () => {
            const tpl = document.querySelector('.misc-item').cloneNode(true);

            tpl.querySelector('h6').textContent = `Item #${++idx}`;

            tpl.querySelectorAll('select, input').forEach(el => {
                if (el.name) {
                    el.name = el.name.replace(/\[\d+\]/, `[${idx - 1}]`);
                }
                if (!el.readOnly && el.type !== 'select-one') el.value = '';
                el.classList.remove('is-invalid');
            });

            tpl.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            // Append and update count
            miscList.appendChild(tpl);
            refreshLabels();
            
        });

        // Remove Button
        miscList.addEventListener('click', function(e){
           
            if(e.target.classList.contains('remove-supply')){
                const items = miscList.querySelectorAll('.misc-item');
                if(items.length > 1){
                    e.target.closest('.misc-item').remove();
                    refreshLabels();
                    idx = items.length - 1;
                }
            }
            
        });

        // Remember Misc# Labels
        function refreshLabels(){
            const items = miscList.querySelectorAll('.misc-item');
            items.forEach((el, i) => {
                el.querySelector('h6').textContent = `Item #${i+1}`
            })
        }
    
    </script>  
@endpush

@endsection