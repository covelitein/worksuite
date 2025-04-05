<?php

namespace Modules\BudgetUtilsModule\Http\Controllers;

use App\DataTables\BudgetUtilsDataTable;
use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\BudgetUtils;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BudgetUtilsModuleController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.budget_utils';

        $this->middleware(function ($request, $next) {
            abort_if(!in_array('clients', $this->user->modules), 403);
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BudgetUtilsDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->budget_utils = BudgetUtils::all();
        }

        return $dataTable->render('budgetutilsmodule::index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new budget entry.
     */
    public function create()
    {
        $this->pageTitle = __('app.menu.add_budget_utils');
        $this->view = 'budgetutilsmodule::ajax.create';

        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        return view('budgetutilsmodule::create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'budget_approved_usd' => 'required|numeric|min:0',
            'project_id' => 'nullable|integer',
            'category' => 'nullable|string|max:255',
            'planned_cost_usd' => 'nullable|numeric|min:0',
            'actual_cost_usd' => 'nullable|numeric|min:0',
            'comments' => 'nullable|string'
        ]);

        $budget = new BudgetUtils();
        $budget->fill($validated);

        // Calculate derived fields
        $budget->variance_usd = ($budget->actual_cost_usd ?? 0) - ($budget->planned_cost_usd ?? 0);
        $budget->remaining_budget_usd = $budget->budget_approved_usd - ($budget->actual_cost_usd ?? 0);

        // Sanitize comments
        $budget->comments = trim_editor($request->comments);

        $budget->save();

        $redirectUrl = urldecode($request->redirect_url);
        if ($redirectUrl == '') {
            $redirectUrl = route('budgetutilsmodule.index');
        }

        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }
    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $this->budget = BudgetUtils::findOrFail($id);
        $this->pageTitle = __('Budget Utilization') . ' - ' . $this->budget->project_name;
        $this->view = 'budgetutilsmodule::ajax.show';
        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        return view('budgetutilsmodule::show', $this->data);
    }

    public function edit($id)
    {
        $this->budget = BudgetUtils::findOrFail($id);
        $this->pageTitle = __('Edit') . ' ' . __('Budget Utilization');

        if (request()->ajax()) {
            return $this->returnAjax('budgetutilsmodule::ajax.edit');
        }

        return view('budgetutilsmodule::edit', $this->data);
    }

    public function update(Request $request, $id)
    {
        $budget = BudgetUtils::findOrFail($id);

        $validated = $request->validate([
            'project_id' => 'nullable|string|max:50',
            'project_name' => 'required|string|max:100',
            'budget_approved_usd' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'planned_cost_usd' => 'nullable|numeric|min:0',
            'actual_cost_usd' => 'nullable|numeric|min:0',
            'comments' => 'nullable|string'
        ]);

        // Calculate derived fields
        $validated['variance_usd'] = ($request->actual_cost_usd ?? 0) - ($request->planned_cost_usd ?? 0);
        $validated['remaining_budget_usd'] = $request->budget_approved_usd - ($request->actual_cost_usd ?? 0);

        $budget->fill($validated);
        $budget->save();

        return Reply::successWithData(
            __('messages.updateSuccess'),
            ['redirectUrl' => route('budgetutilsmodule.index')]
        );
    }
    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        BudgetUtils::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }
}