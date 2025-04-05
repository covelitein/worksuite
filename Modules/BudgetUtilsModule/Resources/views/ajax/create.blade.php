<x-form id="save-project-budget-data-form">
    <div class="row p-20">
        <div class="col-lg-12">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal border-bottom-grey">
                    @lang('app.menu.add_budget_utils')
                </h4>
                <div class="row p-20">
                    <div class="col-md-6">
                        <x-forms.text fieldId="project_name" 
                            :fieldLabel="__('modules.budget_utils.projectName')" 
                            fieldName="project_name"
                            fieldRequired="true" 
                            :fieldPlaceholder="__('placeholders.projectName')">
                        </x-forms.text>
                    </div>
                    
                    <div class="col-md-6">
                        <x-forms.text fieldId="project_id" 
                            :fieldLabel="__('modules.budget_utils.project_id')" 
                            fieldName="project_id"
                            :fieldPlaceholder="__('placeholders.projectId')">
                        </x-forms.text>
                    </div>
                    
                    <div class="col-md-6">
                        <x-forms.text fieldId="category" 
                            :fieldLabel="__('app.category')" 
                            fieldName="category"
                            :fieldPlaceholder="__('placeholders.category')">
                        </x-forms.text>
                    </div>
                    
                    <div class="col-md-6">
                        <x-forms.number fieldId="budget_approved_usd" 
                            :fieldLabel="__('modules.budget_utils.budgetApproved') . ' (USD)'" 
                            fieldName="budget_approved_usd"
                            fieldRequired="true"
                            :fieldPlaceholder="__('placeholders.amount')">
                        </x-forms.number>
                    </div>
                    
                    <div class="col-md-4">
                        <x-forms.number fieldId="planned_cost_usd" 
                            :fieldLabel="__('modules.budget_utils.plannedCost') . ' (USD)'" 
                            fieldName="planned_cost_usd"
                            :fieldPlaceholder="__('placeholders.amount')">
                        </x-forms.number>
                    </div>
                    
                    <div class="col-md-4">
                        <x-forms.number fieldId="actual_cost_usd" 
                            :fieldLabel="__('modules.budget_utils.actualCost') . ' (USD)'" 
                            fieldName="actual_cost_usd"
                            :fieldPlaceholder="__('placeholders.amount')">
                        </x-forms.number>
                    </div>
                    
                    <div class="col-md-4">
                        <x-forms.number fieldId="variance_usd" 
                            :fieldLabel="__('modules.budget_utils.variance') . ' (USD)'" 
                            fieldName="variance_usd"
                            :fieldPlaceholder="__('placeholders.amount')" 
                            fieldReadOnly="true">
                        </x-forms.number>
                    </div>
                    
                    <div class="col-md-6">
                        <x-forms.number fieldId="remaining_budget_usd" 
                            :fieldLabel="__('modules.budget_utils.remainingBudget') . ' (USD)'" 
                            fieldName="remaining_budget_usd"
                            :fieldPlaceholder="__('placeholders.amount')" 
                            fieldReadOnly="true">
                        </x-forms.number>
                    </div>
                    
                    <div class="col-md-6">
                        <x-forms.textarea fieldId="comments" 
                            :fieldLabel="__('modules.budget_utils.comments')" 
                            fieldName="comments"
                            :fieldPlaceholder="__('placeholders.comments')">
                        </x-forms.textarea>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <x-form-actions>
        <x-forms.button-primary id="save-project-budget-form" class="mr-3" icon="check">
            @lang('app.save')
        </x-forms.button-primary>
        <x-forms.button-cancel :link="route('budgetutilsmodule.index')" class="border-0">
            @lang('app.cancel')
        </x-forms.button-cancel>
    </x-form-actions>

</x-form>

<script>
    $(document).ready(function() {
        // Calculate variance when planned or actual costs change
        $('#planned_cost_usd, #actual_cost_usd').on('change', function() {
            const planned = parseFloat($('#planned_cost_usd').val()) || 0;
            const actual = parseFloat($('#actual_cost_usd').val()) || 0;
            const variance = actual - planned;
            $('#variance_usd').val(variance.toFixed(2));
        });

        // Calculate remaining budget when approved budget or actual costs change
        $('#budget_approved_usd, #actual_cost_usd').on('change', function() {
            const approved = parseFloat($('#budget_approved_usd').val()) || 0;
            const actual = parseFloat($('#actual_cost_usd').val()) || 0;
            const remaining = approved - actual;
            $('#remaining_budget_usd').val(remaining.toFixed(2));
        });

        $('#save-project-budget-form').click(function() {
            const url = "{{ route('budgetutilsmodule.store') }}";
            var data = $('#save-project-budget-data-form').serialize();

            $.easyAjax({
                url: url,
                container: '#save-project-budget-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-project-budget-form",
                data: data,
                success: function(response) {
                    if (response.status == 'success') {
                        if (typeof MODAL_XL !== 'undefined' && $(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).modal('hide');
                            window.location.reload();
                        } else if(typeof response.redirectUrl !== 'undefined'){
                            setTimeout(function() {
                                console.log($(MODAL_XL).hasClass('show'))
                                $(MODAL_XL).modal('hide');
                            //   window.location.href = redirectUrl;
                            }, 1500);
                        }
                    }
                }
            });
        });
    });
</script>