<?php

namespace Modules\PaymentReceiptModule\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\PaymentReceipt;
use Illuminate\Http\Request;
use App\DataTables\PaymentReceiptsDataTable;

class PaymentReceiptModuleController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.payment_receipts';

        $this->middleware(function ($request, $next) {
            abort_if(!in_array('clients', $this->user->modules), 403);
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaymentReceiptsDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->receipts = PaymentReceipt::all();
        }

        return $dataTable->render('paymentreceiptmodule::index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->pageTitle = __('app.menu.add_payment_receipt');
        $this->view = 'paymentreceiptmodule::ajax.create';

        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        return view('paymentreceiptmodule::create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|string|max:50',
            'project_name' => 'required|string|max:100',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
            'payment_date' => 'required|date',
            'paid_by' => 'required|string|max:100',
            'received_by' => 'required|string|max:100',
        ]);

        $receipt = new PaymentReceipt();
        $receipt->fill($validated);
        $receipt->receipt_num = $receipt->generateReceiptNumber();
        $receipt->save();

        return Reply::successWithData(
            __('messages.recordSaved'),
            ['redirectUrl' => route('paymentreceiptmodule.index')]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->receipt = PaymentReceipt::findOrFail($id);
        $this->pageTitle = __('app.payment_receipt') . ' #' . $this->receipt->receipt_num;
        $this->view = 'paymentreceiptmodule::ajax.show';

        if (request()->ajax()) {
            return $this->returnAjax($this->view);
        }

        return view('paymentreceiptmodule::show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->receipt = PaymentReceipt::findOrFail($id);
        $this->pageTitle = __('app.edit') . ' ' . __('app.payment_receipt');

        if (request()->ajax()) {
            return $this->returnAjax('paymentreceiptmodule::ajax.edit');
        }

        return view('paymentreceiptmodule::edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $receipt = PaymentReceipt::findOrFail($id);

        $validated = $request->validate([
            'project_id' => 'required|string|max:50',
            'project_name' => 'required|string|max:100',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
            'payment_date' => 'required|date',
            'paid_by' => 'required|string|max:100',
            'received_by' => 'required|string|max:100',
        ]);

        $receipt->fill($validated);
        $receipt->save();

        return Reply::successWithData(
            __('messages.updateSuccess'),
            ['redirectUrl' => route('paymentreceiptmodule.index')]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        PaymentReceipt::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }

    /**
     * Download the specified receipt.
     */
    public function download($id)
    {
        $receipt = PaymentReceipt::findOrFail($id);
        // Implement your PDF generation logic here
        return response()->download($receipt->generatePdf());
    }
}