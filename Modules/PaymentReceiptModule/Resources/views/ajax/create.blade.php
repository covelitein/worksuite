<x-form id="save-payment-receipt-form">
    <div class="row p-20">
        <div class="col-lg-12">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal border-bottom-grey">
                    @lang('app.menu.add_payment_receipt')
                </h4>
                <div class="row p-20">
                    <div class="col-md-6">
                        <x-forms.text fieldId="project_id" 
                            :fieldLabel="__('modules.payment_receipts.projectId')" 
                            fieldName="project_id"
                            fieldRequired="true" 
                            :fieldPlaceholder="__('placeholders.projectId')"
                            maxlength="50">
                        </x-forms.text>
                    </div>
                    
                    <div class="col-md-6">
                        <x-forms.text fieldId="project_name" 
                            :fieldLabel="__('modules.payment_receipts.projectName')" 
                            fieldName="project_name"
                            fieldRequired="true" 
                            :fieldPlaceholder="__('placeholders.projectName')"
                            maxlength="100">
                        </x-forms.text>
                    </div>
                    
                    <div class="col-md-4">
                        <x-forms.number fieldId="amount_paid" 
                            :fieldLabel="__('modules.payment_receipts.amountPaid')" 
                            fieldName="amount_paid"
                            fieldRequired="true"
                            :fieldPlaceholder="__('placeholders.amount')"
                            min="0"
                            step="0.01">
                        </x-forms.number>
                    </div>
                    
                    <div class="col-md-4">
                        <x-forms.select fieldId="payment_method" 
                            :fieldLabel="__('modules.payment_receipts.paymentMethod')" 
                            fieldName="payment_method"
                            fieldRequired="true">
                            <option value="">-- Select --</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="mobile_money">Mobile Money</option>
                        </x-forms.select>
                    </div>
                    
                    <div class="col-md-4">
                        <x-forms.datepicker fieldId="payment_date" 
                            :fieldLabel="__('modules.payment_receipts.paymentDate')" 
                            fieldName="payment_date"
                            fieldRequired="true"
                            :fieldPlaceholder="__('placeholders.date')"
                            :fieldValue="now()->format(company()->date_format)"/>
                    </div>
                    
                    <div class="col-md-6">
                        <x-forms.text fieldId="paid_by" 
                            :fieldLabel="__('modules.payment_receipts.paidBy')" 
                            fieldName="paid_by"
                            fieldRequired="true" 
                            :fieldPlaceholder="__('placeholders.name')"
                            maxlength="100">
                        </x-forms.text>
                    </div>
                    
                    <div class="col-md-6">
                        <x-forms.text fieldId="received_by" 
                            :fieldLabel="__('modules.payment_receipts.receivedBy')" 
                            fieldName="received_by"
                            fieldRequired="true"
                            :fieldPlaceholder="__('placeholders.name')"
                            maxlength="100">
                        </x-forms.text>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-form-actions>
        <x-forms.button-primary id="save-payment-receipt" class="mr-3" icon="check">
            @lang('app.save')
        </x-forms.button-primary>
        <x-forms.button-cancel :link="route('paymentreceiptmodule.index')" class="border-0">
            @lang('app.cancel')
        </x-forms.button-cancel>
    </x-form-actions>
</x-form>

<script>
$(document).ready(function() {
    // Initialize datepicker

    $('#save-payment-receipt').click(function() {
        const url = "{{ route('paymentreceiptmodule.store') }}";
        var data = $('#save-payment-receipt-form').serialize();

        $.easyAjax({
            url: url,
            container: '#save-payment-receipt-form',
            type: "POST",
            disableButton: true,
            blockUI: true,
            buttonSelector: "#save-payment-receipt",
            data: data,
            success: function(response) {
                if (response.status == 'success') {
                    if (typeof MODAL_XL !== 'undefined' && $(MODAL_XL).hasClass('show')) {
                        $(MODAL_XL).modal('hide');
                        window.location.reload();
                    } else if(typeof response.redirectUrl !== 'undefined'){
                        setTimeout(function() {
                            $(MODAL_XL).modal('hide');
                        }, 1500);
                    }
                }
            }
        });
    });
});
</script>