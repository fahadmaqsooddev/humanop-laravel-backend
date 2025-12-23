@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .modal-close-btn {
        background: #1b3a62;
        border: none;
        color: white;
        font-weight: bold;
        font-size: x-large;
        float:right;
        border-radius: 3px;
        padding: 0px 10px 1px 10px;
    }
    .pagination{
        float:right;
        margin-right:24px ;
    }
    .page-link {
        background: none !important;
    }
    .page-link:hover{
        background: #1b3a62 !important;
        color:white !important;
    }

    .page-item.active .page-link {
        background: #1b3a62 !important;
        color: white !important;
        border-color: #1b3a62 !important;
    }

    .table-text-color{
        color: #1c365e !important;
    }

    .dataTable-table th a{
        color: #1c365e !important;
    }

    .stats-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .code-list {
        max-height: 300px;
        overflow-y: auto;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-top: 15px;
    }

    .code-item {
        padding: 8px;
        margin: 5px 0;
        background: white;
        border-radius: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .copy-btn {
        cursor: pointer;
        padding: 4px 12px;
        background: #1b3a62;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 12px;
    }

    .copy-btn:hover {
        background: #0d2a4a;
    }

</style>
@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">Manage Lifetime Coupons</h5>
                    <button data-bs-toggle="modal"
                       data-bs-target="#generateCouponModal"
                       style="background-color: #1B3A62 ; color: white" class="btn btn-sm float-end mb-0">Generate Coupons</button>
                </div>

                <!-- Stats Cards -->
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stats-card">
                                <h6 class="text-muted mb-2">Total Coupons</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <h6 class="text-muted mb-2">Premium Lifetime</h6>
                                <h3 class="mb-0">{{ $stats['premium_lifetime'] }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <h6 class="text-muted mb-2">Beta Breaker Lifetime</h6>
                                <h3 class="mb-0">{{ $stats['bb_lifetime'] }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-card">
                                <h6 class="text-muted mb-2">Available</h6>
                                <h3 class="mb-0 text-success">{{ $stats['available'] }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Coupons Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="datatable-search">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Redeemed By</th>
                                    <th>Redeemed At</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td class="text-muted"><strong>{{ $coupon->code }}</strong></td>
                                        <td>
                                            @if($coupon->type === 'premium_lifetime')
                                                <span class="badge bg-primary">Premium Lifetime</span>
                                            @else
                                                <span class="badge bg-info">Beta Breaker Lifetime</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->is_redeemed)
                                                <span class="badge bg-danger">Redeemed</span>
                                            @else
                                                <span class="badge bg-success">Available</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->redeemedBy)
                                                {{ $coupon->redeemedBy->first_name }} {{ $coupon->redeemedBy->last_name }}
                                                <br><small class="text-muted">{{ $coupon->redeemedBy->email }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->redeemed_at)
                                                {{ $coupon->redeemed_at->format('Y-m-d H:i:s') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ $coupon->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No coupons found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $coupons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Coupon Modal -->
    <div class="modal fade" id="generateCouponModal" tabindex="-1" aria-labelledby="generateCouponModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateCouponModalLabel">Generate Lifetime Coupons</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="generateCouponForm">
                        <div class="mb-3">
                            <label for="couponType" class="form-label">Coupon Type</label>
                            <select class="form-select" id="couponType" name="type" required>
                                <option value="">Select Type</option>
                                <option value="premium_lifetime">Premium Lifetime</option>
                                <option value="bb_lifetime">Beta Breaker Lifetime</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="couponQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="couponQuantity" name="quantity" min="1" max="500" value="1" required>
                            <small class="text-muted">Maximum 500 codes at a time</small>
                        </div>
                    </form>
                    <div id="generatedCodes" class="code-list" style="display: none;">
                        <h6>Generated Codes:</h6>
                        <div id="codesContainer"></div>
                        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="copyAllCodes()">Copy All</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="generateCoupons()">Generate</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script>
        const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
            searchable: true,
            fixedHeight: true
        });

        function generateCoupons() {
            const form = document.getElementById('generateCouponForm');
            const formData = new FormData(form);
            const type = formData.get('type');
            const quantity = formData.get('quantity');

            if (!type || !quantity) {
                alert('Please fill in all fields');
                return;
            }

            // Show loading
            const generateBtn = event.target;
            const originalText = generateBtn.textContent;
            generateBtn.textContent = 'Generating...';
            generateBtn.disabled = true;

            fetch('{{ route("admin_generate_lifetime_coupons") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    type: type,
                    quantity: parseInt(quantity)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // Display generated codes
                    const codesContainer = document.getElementById('codesContainer');
                    codesContainer.innerHTML = '';

                    data.codes.forEach(code => {
                        const codeItem = document.createElement('div');
                        codeItem.className = 'code-item';
                        codeItem.innerHTML = `
                            <span><strong>${code}</strong></span>
                            <button class="copy-btn" onclick="copyCode('${code}')">Copy</button>
                        `;
                        codesContainer.appendChild(codeItem);
                    });

                    document.getElementById('generatedCodes').style.display = 'block';

                    // Reset form
                    form.reset();

                    // Reload page after 3 seconds to show new codes in table
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                } else {
                    alert('Error: ' + (data.message || 'Failed to generate coupons'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while generating coupons');
            })
            .finally(() => {
                generateBtn.textContent = originalText;
                generateBtn.disabled = false;
            });
        }

        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                alert('Code copied: ' + code);
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }

        function copyAllCodes() {
            const codes = Array.from(document.querySelectorAll('#codesContainer .code-item span strong'))
                .map(el => el.textContent)
                .join('\n');

            navigator.clipboard.writeText(codes).then(() => {
                alert('All codes copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }
    </script>
@endpush

