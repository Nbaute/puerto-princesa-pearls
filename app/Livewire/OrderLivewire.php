<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderPaymentStatus;
use App\Models\OrderShippingStatus;
use App\Traits\AlertTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

class OrderLivewire extends Component
{
    use AlertTrait;
    public $order;
    public $paymentStatuses = [];
    public $shippingStatuses = [];

    public $showEditPaymentDetails = false;
    public $showEditShippingDetails = false;
    public $showAddPaymentStatusUpdate = false;
    public $showAddShippingStatusUpdate = false;

    public $shippedBy;
    public $trackingNo;


    public $paymentMethod;
    public $paymentRef;

    public $selectedShippingStatus;
    public $cancellationReason;

    public $viewAs = 'buyer';
    public function mount()
    {
        $transactionCode =  Route::current()->parameters()['transactionCode'];
        $this->order = Order::with([
            'payment_statuses',
            'shipping_statuses',
            'items.item',
        ])->where('transaction_code', $transactionCode)->first();

        if (!empty($this->order)) {

            $this->paymentStatuses = $this->order->payment_statuses()->orderBy('id', 'desc')->get();
            $this->shippingStatuses = $this->order->shipping_statuses()->orderBy('id', 'desc')->get();

            $this->shippedBy = $this->order->shipped_by;
            $this->trackingNo = $this->order->shipping_no;
            $this->paymentMethod = $this->order->payment_method;
            $this->paymentRef = $this->order->payment_ref;

            if ($this->order->shop->user_id == Auth::id()) {
                $this->viewAs = 'seller';
            }
        }
    }
    public function render()
    {
        return view('livewire.order-livewire');
    }

    public function addShippingStatusUpdate()
    {
        $this->showAddShippingStatusUpdate = true;
    }
    public function addPaymentStatusUpdate()
    {
        $this->showAddPaymentStatusUpdate = true;
    }
    public function editPaymentDetails()
    {
        $this->showEditPaymentDetails = true;
    }
    public function editShippingDetails()
    {
        $this->showEditShippingDetails = true;
    }

    public function cancelEditShipping()
    {
        $this->showEditShippingDetails = false;
        $this->showAddShippingStatusUpdate = false;
    }
    public function cancelEditPayment()
    {
        $this->showEditPaymentDetails = false;
        $this->showAddPaymentStatusUpdate = false;
    }

    public function saveShippingStatusUpdate()
    {
        $data = $this->validate([
            'selectedShippingStatus' => [
                'required',
                Rule::in(["to ship", 'shipping', 'completed', 'cancelled'])
            ],
            'cancellationReason' => [
                'sometimes',
                'required_if:selectedShippingStatus,cancelled'
            ]
        ]);
        try {
            DB::beginTransaction();
            $this->order->shipping_status = $data['selectedShippingStatus'];
            $this->order->save();

            $orderShippingStatus = new OrderShippingStatus();
            $orderShippingStatus->order_id = $this->order->id;
            $orderShippingStatus->name = $data['selectedShippingStatus'];
            if (isset($data['cancellationReason']) and !empty($data['cancellationReason'])) {
                $orderShippingStatus->description = json_encode([
                    'Cancellation Reason' => $data['cancellationReason']
                ]);
            }
            $orderShippingStatus->save();

            DB::commit();
            $this->order->refresh();
            $this->shippingStatuses = $this->order->shipping_statuses()->orderBy('id', 'desc')->get();
            $this->successAlert();
            $this->selectedShippingStatus = '';
        } catch (Throwable $t) {
            DB::rollBack();
            $this->errorAlert($t->getMessage());
        }
    }
    public function savePaymentDetails()
    {
        $this->validate([
            'paymentRef' => 'required',
            'paymentMethod' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $this->order->payment_ref = $this->paymentRef;
            $this->order->payment_method = $this->paymentMethod;
            $previousPaymentStatus = $this->order->payment_status;
            if ($this->viewAs == ('seller')) {

                $this->order->payment_status = 'received';
            } else if ($this->viewAs == 'buyer') {
                $this->order->payment_status = 'pending';
            }
            $this->order->save();

            if ($this->viewAs == ('seller')) {
                if ($previousPaymentStatus != 'received') {

                    $paymentStatus = new OrderPaymentStatus();
                    $paymentStatus->order_id = $this->order->id;
                    $paymentStatus->name = 'received';
                    $paymentStatus->description = json_encode([
                        'Payment Method' => $this->paymentMethod,
                        'Payment Ref.' => $this->paymentRef,
                    ]);
                    $paymentStatus->save();
                    $this->order->refresh();
                    $this->paymentStatuses = $this->order->payment_statuses()->orderBy('id', 'desc')->get();
                }
                if ($this->order->shipping_status == 'unpaid') {
                    $this->order->shipping_status = 'paid';
                    $this->order->save();
                    $shippingStatus = new OrderShippingStatus();
                    $shippingStatus->order_id = $this->order->id;
                    $shippingStatus->name = 'paid';
                    $shippingStatus->description = json_encode([
                        'Payment Method' => $this->paymentMethod,
                        'Payment Ref.' => $this->paymentRef,
                    ]);
                    $shippingStatus->save();
                    $this->order->refresh();
                    $this->shippingStatuses = $this->order->shipping_statuses()->orderBy('id', 'desc')->get();
                }
            } else {
                $paymentStatus = new OrderPaymentStatus();
                $paymentStatus->order_id = $this->order->id;
                $paymentStatus->name = 'pending';
                $paymentStatus->description = json_encode([
                    'Payment Method' => $this->paymentMethod,
                    'Payment Ref.' => $this->paymentRef,
                ]);
                $paymentStatus->save();
                $this->order->refresh();
                $this->paymentStatuses = $this->order->payment_statuses()->orderBy('id', 'desc')->get();
            }
            DB::commit();
            $this->successAlert();
            $this->cancelEditPayment();
        } catch (Throwable $t) {
            DB::rollBack();
            $this->errorAlert("An error occurred! " . $t->getMessage());
        }
    }
    public function saveShippingDetails()
    {
        $this->validate([
            'trackingNo' => 'required',
            'shippedBy' => 'required',
        ]);
        $this->order->shipping_no = $this->trackingNo;
        $this->order->shipped_by = $this->shippedBy;
        $this->order->save();

        $this->successAlert();
        $this->cancelEditShipping();
    }
    public function savePaymentStatusUpdate() {}

    public function selectShippingStatus() {}

    public function markAsNotReceived()
    {
        try {
            DB::beginTransaction();
            $paymentStatus = new OrderPaymentStatus();
            $paymentStatus->order_id = $this->order->id;
            $paymentStatus->name = 'not received';
            $paymentStatus->save();
            $this->order->payment_status = 'not received';
            $this->order->save();
            $this->order->refresh();
            $this->paymentStatuses = $this->order->payment_statuses()->orderBy('id', 'desc')->get();
            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            $this->errorAlert($t->getMessage());
        }
    }
    public function markAsReceived()
    {
        try {
            DB::beginTransaction();
            $paymentStatus = new OrderPaymentStatus();
            $paymentStatus->order_id = $this->order->id;
            $paymentStatus->name = 'received';
            $paymentStatus->description = json_encode([
                'Payment Method' => $this->paymentMethod,
                'Payment Ref.' => $this->paymentRef,
            ]);
            $paymentStatus->save();
            $this->order->payment_status = 'received';
            $this->order->save();
            if ($this->order->shipping_status == 'unpaid') {
                $this->order->shipping_status = 'paid';
                $this->order->save();
                $shippingStatus = new OrderShippingStatus();
                $shippingStatus->order_id = $this->order->id;
                $shippingStatus->name = 'paid';
                $shippingStatus->description = json_encode([
                    'Payment Method' => $this->paymentMethod,
                    'Payment Ref.' => $this->paymentRef,
                ]);
                $shippingStatus->save();
                $this->order->refresh();
                $this->shippingStatuses = $this->order->shipping_statuses()->orderBy('id', 'desc')->get();
            }
            $this->order->refresh();
            $this->paymentStatuses = $this->order->payment_statuses()->orderBy('id', 'desc')->get();
            DB::commit();
        } catch (Throwable $t) {
            DB::rollBack();
            $this->errorAlert($t->getMessage());
        }
    }

    public function cancelOrder()
    {
        try {
            DB::beginTransaction();
            $this->order->shipping_status = "cancelled";
            $this->order->save();

            $orderShippingStatus = new OrderShippingStatus();
            $orderShippingStatus->order_id = $this->order->id;
            $orderShippingStatus->name = "cancelled";
            $orderShippingStatus->description = json_encode([
                'Cancelled by' => 'customer'
            ]);
            $orderShippingStatus->save();

            DB::commit();
            $this->order->refresh();
            $this->shippingStatuses = $this->order->shipping_statuses()->orderBy('id', 'desc')->get();
            $this->successAlert();
            $this->selectedShippingStatus = '';
        } catch (Throwable $t) {
            DB::rollBack();
            $this->errorAlert($t->getMessage());
        }
    }
}