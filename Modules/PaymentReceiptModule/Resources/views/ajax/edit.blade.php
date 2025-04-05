@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- EDIT PAYMENT RECEIPT START -->
        <div class="bg-white rounded b-shadow-4 create-inv">
            <!-- HEADING START -->
            <div class="px-lg-4 px-md-4 px-3 py-3">
                <h4 class="mb-0 f-21 font-weight-normal">@lang('Edit Payment Receipt')</h4>
            </div>
            <!-- HEADING END -->
            <hr class="m-0 border-top-grey">
            <!-- FORM START -->
            <x-form class="c-inv-form" id="saveReceiptForm" method="PUT">
                <!-- BASIC INFO START -->
                <div class="row px-lg-4 px-md-4 px-3 py-3">
                    <!-- RECEIPT NUMBER -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-forms.label fieldId="receipt_num" :fieldLabel="__('Receipt Number')" fieldRequired="true"></x-forms.label>
                            <input type="text" class="form-control height-35 f-15" name="receipt_num" id="receipt_num"
                                value="{{ $receipt->receipt_num }}" required>
                        </div>
                    </div>

                    <!-- PAYMENT DATE -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-forms.label fieldId="payment_date" :fieldLabel="__('Payment Date')"></x-forms.label>
                            <div class="input-group">
                                <input type="text" id="payment_date" name="payment_date"
                                    class="px-6 position-relative text-dark font-weight-normal form-control height-35 rounded p-0 text-left f-15"
                                    placeholder="@lang('placeholders.date')"
                                    value="{{ $receipt->payment_date ? $receipt->payment_date->format(company()->date_format) : '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row px-lg-4 px-md-4 px-3 py-3">
                    <!-- PROJECT ID -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-forms.label fieldId="project_id" :fieldLabel="__('Project ID')" fieldRequired="true"></x-forms.label>
                            <input type="text" class="form-control height-35 f-15" name="project_id" id="project_id"
                                value="{{ $receipt->project_id }}" required>
                        </div>
                    </div>

                    <!-- PROJECT NAME -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-forms.label fieldId="project_name" :fieldLabel="__('Project Name')" fieldRequired="true"></x-forms.label>
                            <input type="text" class="form-control height-35 f-15" name="project_name" id="project_name"
                                value="{{ $receipt->project_name }}" required>
                        </div>
                    </div>
                </div>

                <div class="row px-lg-4 px-md-4 px-3 py-3">
                    <!-- AMOUNT PAID -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-forms.label fieldId="amount_paid" :fieldLabel="__('Amount Paid')" fieldRequired="true"></x-forms.label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ company()->currency->currency_symbol }}</span>
                                </div>
                                <input type="number" step="0.01" min="0" class="form-control height-35 f-15" 
                                    name="amount_paid" id="amount_paid" value="{{ $receipt->amount_paid }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- PAYMENT METHOD -->
                    <div class="col-md-6">
                        <div class="form-group c-inv-select mb-4">
                            <x-forms.label fieldId="payment_method" :fieldLabel="__('Payment Method')" fieldRequired="true"></x-forms.label>
                            <div class="select-others height-35 rounded">
                                <select class="form-control select-picker" name="payment_method" id="payment_method" required>
                                    <option value="Cash" @selected($receipt->payment_method == 'Cash')>Cash</option>
                                    <option value="Bank Transfer" @selected($receipt->payment_method == 'Bank Transfer')>Bank Transfer</option>
                                    <option value="Credit Card" @selected($receipt->payment_method == 'Credit Card')>Credit Card</option>
                                    <option value="Check" @selected($receipt->payment_method == 'Check')>Check</option>
                                    <option value="Other" @selected($receipt->payment_method == 'Other')>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row px-lg-4 px-md-4 px-3 py-3">
                    <!-- PAID BY -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-forms.label fieldId="paid_by" :fieldLabel="__('Paid By')" fieldRequired="true"></x-forms.label>
                            <input type="text" class="form-control height-35 f-15" name="paid_by" id="paid_by"
                                value="{{ $receipt->paid_by }}" required>
                        </div>
                    </div>

                    <!-- RECEIVED BY -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-forms.label fieldId="received_by" :fieldLabel="__('Received By')" fieldRequired="true"></x-forms.label>
                            <input type="text" class="form-control height-35 f-15" name="received_by" id="received_by"
                                value="{{ $receipt->received_by }}" required>
                        </div>
                    </div>
                </div>
                <!-- BASIC INFO END -->

                <!-- CANCEL SAVE SEND START -->
                <div class="px-lg-4 px-md-4 px-3 py-3 c-inv-btns">
                    <button type="button" class="btn-cancel rounded mr-0 mr-lg-3 mr-md-3 f-15">
                        @lang('app.cancel')
                    </button>

                    <div class="d-flex">
                        <x-forms.button-primary class="save-form" icon="check">
                            @lang('app.update')
                        </x-forms.button-primary>
                    </div>
                </div>
                <!-- CANCEL SAVE SEND END -->
            </x-form>
            <!-- FORM END -->
        </div>
        <!-- EDIT PAYMENT RECEIPT END -->
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize date picker
            const dp1 = datepicker('#payment_date', {
                position: 'bl',
                @if($receipt->payment_date)
                    dateSelected: new Date("{{ str_replace('-', '/', $receipt->payment_date->format('Y-m-d')) }}"),
                @endif
                ...datepickerConfig
            });

            // Form submission
            $('.save-form').click(function() {
                $.easyAjax({
                    url: "{{ route('paymentreceiptmodule.update', $receipt->id) }}",
                    container: '#saveReceiptForm',
                    type: "POST",
                    blockUI: true,
                    redirect: true,
                    data: $('#saveReceiptForm').serialize()
                });
            });

            // Cancel button
            $('.btn-cancel').click(function() {
                window.location.href = "{{ route('paymentreceiptmodule.index') }}";
            });
        });
    </script>
@endpush