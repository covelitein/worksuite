<?php

return [
    'name' => 'BudgetUtilsModule',
    'verification_required' => true, // If required is required
    'envato_item_id' => '', // Add envato id if published to envato
    'parent_envato_id' => 23263417, // This is the envato id of the worksuite saas. This is mandatory
    'script_name' => 'worksuite-saas-new-zoom',
    'setting' => \Modules\Zoom\Entities\ZoomSetting::class
];
