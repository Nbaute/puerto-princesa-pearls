<div>
    @if (!empty($order))
        <div class="min-h-screen p-4 lg:p-8 bg-accent-500">
            <div class="text-3xl">Order #{{ $order->transaction_code }}</div>
            <div class="px-4 py-8 mt-8 bg-white shadow-xl lg:px-8 rounded-xl">
                <div class="pb-6 text-sm">
                    Customer: <span
                        class="font-semibold uppercase text-primary-800">{{ $order->billing_customer_name }}</span>
                    <br>
                    Address: <span class="font-semibold text-primary-800">{{ $order->shipping_address ?? '-' }}</span>
                    <br>
                    Shop: <a target="_blank" href="/shops/{{ $order->shop->username }}"
                        class="font-semibold text-primary-800">{{ $order->shop->name ?? '-' }}</a>
                    <br>

                </div>
                {{ view('order_table.items', ['row' => $order]) }}

                <div class="text-sm text-right">
                    <div class="flex flex-col justify-between gap-4 py-4 my-4 border-y items-between">

                        <div>
                            <span>Shipping Fee: </span>
                            <span class="text-lg font-semibold text-primary-800">@money($order->shipping_fee)</span>
                        </div>
                        <div>
                            <span>Subtotal: </span>
                            <span class="text-lg font-semibold text-primary-800">@money($order->sub_total)</span>
                        </div>
                        <div>
                            <span>Total: </span>
                            <span class="text-lg font-semibold text-primary-800">@money($order->total)</span>
                        </div>
                    </div>

                </div>
                @if ($viewAs == 'buyer')
                    <div class="flex items-center gap-4 mt-4">
                        @if (
                            !in_array($order->shipping_status, ['delivered', 'cancelled']) and
                                in_array($order->payment_status, ['pending', 'not received']))
                            <button type="button" wire:click='cancelOrder' wire:confirm
                                class="text-white btn btn-error">
                                <i class="fa fa-times"></i>Cancel the order
                            </button>
                        @endif
                        <a target="_blank" href="{{ $order->shop->link }}"
                            class="text-black btn btn-white">
                            <i class="fa fa-comments"></i>Contact the seller
                        </a>
                    </div>
                @endif
            </div>
            <div class="flex flex-col flex-wrap w-full gap-4 my-8 lg:flex-row justify-stretch">
                <div class="flex flex-col flex-1 gap-4 px-4 py-8 bg-white shadow-xl lg:px-8 rounded-xl">
                    <div class="text-lg font-semibold">Payment Details</div>
                    @if (!$showEditPaymentDetails)
                        <div class="text-sm ">
                            Status: <span
                                class="font-semibold uppercase text-primary-800">{{ $order->payment_status ?? '-' }}</span>
                            <br>
                            Payment Method: <span
                                class="font-semibold text-primary-800">{{ $order->payment_method ?? '-' }}</span>
                            <br>
                            Payment Ref.: <span
                                class="font-semibold text-primary-800">{{ $order->payment_ref ?? '-' }}</span>
                            <br>
                        </div>

                        {{-- <div class="p-6 text-sm bg-gray-100 rounded-lg">

                            @foreach ($paymentStatuses as $status)
                                <div class="flex flex-row items-center justify-between gap-4">

                                    <div class="uppercase">{{ $status->name }}</div>
                                    <div>{{ $status->created_at->format('M d, Y h:i:s A') }}</div>
                                </div>
                            @endforeach
                        </div> --}}
                        <div class="relative flex flex-col gap-4 p-6 text-sm bg-gray-100 rounded-lg">
                            <div class="absolute top-8 left-[29px] bottom-8 w-[2px] bg-gray-700"></div>
                            @foreach ($paymentStatuses as $status)
                                <div class="flex flex-row items-center justify-between gap-4 ">

                                    <div class="flex flex-row items-start gap-6 uppercase">
                                        <div class="bg-gray-700 border border-gray-700 rounded-full size-3"></div>
                                        <div class="flex flex-col">
                                            <span
                                                class="font-semibold @switch($status->name)
                                        @case('received')
                                            text-emerald-500
                                            @break
                                        @case('not received')
                                            text-red-500
                                        @default
                                            text-gray-500
                                    @endswitch)">
                                                {{ $status->name }}</span>
                                            @php
                                                $details = json_decode($status->description ?? '[]', true);
                                                $keys = array_keys($details);
                                            @endphp
                                            @if (count($keys) > 0)
                                                <div class="text-xs normal-case ">
                                                    @foreach ($keys as $k)
                                                        {{ $k }}: {{ $details[$k] }}
                                                        <br>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div>{{ $status->created_at->format('M d, Y h:i:s A') }}</div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 text-sm text-gray-800">Payment Method</label>
                                <div class="relative flex items-center">
                                    <input wire:model="paymentMethod" type="text" required
                                        class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                        placeholder="Payment Method" />
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                                        class="absolute w-4 h-4 right-4" viewBox="0 0 24 24">
                                        <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                                        <path
                                            d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                                            data-original="#000000"></path>
                                    </svg>
                                </div>
                                @error('paymentMethod')
                                    <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm text-gray-800">Payment Ref.</label>
                                <div class="relative flex items-center">
                                    <input wire:model="paymentRef" type="text" required
                                        class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                        placeholder="Payment Ref." />
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                                        class="absolute w-4 h-4 right-4" viewBox="0 0 24 24">
                                        <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                                        <path
                                            d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                                            data-original="#000000"></path>
                                    </svg>
                                </div>
                                @error('paymentRef')
                                    <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if ($viewAs === 'seller')
                        @if (!$showEditPaymentDetails)
                            <div class="flex items-center justify-end gap-4">
                                <button wire:click='editPaymentDetails' class="text-accent btn-primary btn">
                                    <i class="fa fa-pencil"></i> Edit details
                                </button>
                                {{-- <button wire:click='addPaymentStatusUpdate' class="text-white btn-accent btn">
                                    <i class="fa fa-plus"></i> Add a status update
                                </button> --}}
                                @if ($order->payment_status == 'pending' or $order->payment_status == 'not received')
                                    <button wire:click='markAsNotReceived' class="text-white btn-error btn">
                                        <i class="fa fa-times"></i> Mark as not received
                                    </button>
                                    <button wire:click='markAsReceived' class="text-white btn-success btn">
                                        <i class="fa fa-check"></i> Mark as paid
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center justify-end gap-4">
                                <button wire:click='cancelEditPayment' class="text-white text-accent btn-error btn">
                                    <i class="fa fa-times"></i>Cancel
                                </button>
                                <button wire:click='savePaymentDetails' class="text-white btn-accent btn">
                                    <i class="fa fa-save"></i> Save changes
                                </button>
                            </div>
                        @endif
                    @elseif($viewAs === 'buyer')
                        @if (!$showEditPaymentDetails)
                            @if (empty($order->payment_ref) or $order->payment_status == 'not received')
                                <div class="mt-4 text-sm font-semibold">
                                    Payment Instructions
                                </div>
                                <pre class="p-4 text-left bg-gray-100 rounded-xl">{{ $order->shop->payment_instructions }}</pre>
                                <button wire:click='editPaymentDetails' class="text-white btn-accent btn">
                                    <i class="fa fa-check"></i> Mark as paid
                                </button>
                            @endif
                        @else
                            <div class="flex items-center justify-end gap-4">
                                <button wire:click='cancelEditPayment' class="text-white text-accent btn-error btn">
                                    <i class="fa fa-times"></i>Cancel
                                </button>
                                <button wire:click='savePaymentDetails' class="text-white btn-accent btn">
                                    <i class="fa fa-save"></i> Save changes
                                </button>
                            </div>
                        @endif
                    @endif

                </div>
                <div class="flex flex-col flex-1 gap-4 px-4 py-8 bg-white shadow-xl lg:px-8 rounded-xl">
                    <div class="text-lg font-semibold">Shipping Details</div>

                    @if (!$showEditShippingDetails)
                        <div class="text-sm ">
                            Status: <span
                                class="font-semibold uppercase text-primary-800">{{ $order->shipping_status }}</span>
                            <br>
                            Shipped by: <span
                                class="font-semibold text-primary-800">{{ $order->shipped_by ?? '-' }}</span>
                            <br>
                            Tracking No.: @if ($order->shipping_no)
                                <span wire:click='$dispatch("copyToClipboard","{{ $order->shipping_no ?? '-' }}")'
                                    class="font-semibold text-primary-800">{{ $order->shipping_no ?? '-' }} <i
                                        role="button" type="button" class="ml-2 fa fa-clipboard"></i>
                            @endif
                            </span>
                            <br>
                        </div>

                        <div class="relative flex flex-col gap-4 p-6 text-sm bg-gray-100 rounded-lg">
                            <div class="absolute top-8 left-[29px] bottom-8 w-[2px] bg-gray-700"></div>
                            @foreach ($shippingStatuses as $status)
                                <div class="flex flex-row items-center justify-between gap-4 ">

                                    <div class="flex flex-row items-start justify-between gap-6 uppercase">
                                        <div class="bg-gray-700 border border-gray-700 rounded-full size-3"></div>
                                        <div class="flex flex-col">
                                            <span
                                                class="font-semibold @switch($status->name)
                                        @case('paid')
                                            text-emerald-500
                                            @break
                                        @case('unpaid')
                                            text-gray-500
                                        @default
                                            text-gray-500
                                    @endswitch)">
                                                {{ $status->name }}</span>
                                            @php
                                                $details = json_decode($status->description ?? '[]', true);
                                                $keys = array_keys($details);
                                            @endphp
                                            @if (count($keys) > 0)
                                                <div class="text-xs normal-case ">
                                                    @foreach ($keys as $k)
                                                        {{ $k }}: {{ $details[$k] }}
                                                        <br>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div>{{ $status->created_at->format('M d, Y h:i:s A') }}</div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 text-sm text-gray-800">Shipped by</label>
                                <div class="relative flex items-center">
                                    <input wire:model="shippedBy" type="text" required
                                        class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                        placeholder="Shipped by" />
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                                        class="absolute w-4 h-4 right-4" viewBox="0 0 24 24">
                                        <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                                        <path
                                            d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                                            data-original="#000000"></path>
                                    </svg>
                                </div>
                                @error('shippedBy')
                                    <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm text-gray-800">Tracking No.</label>
                                <div class="relative flex items-center">
                                    <input wire:model="trackingNo" type="text" required
                                        class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                        placeholder="Tracking No." />
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                                        class="absolute w-4 h-4 right-4" viewBox="0 0 24 24">
                                        <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                                        <path
                                            d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                                            data-original="#000000"></path>
                                    </svg>
                                </div>
                                @error('trackingNo')
                                    <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif


                    @if ($viewAs === 'seller')
                        @if (!$showEditShippingDetails)
                            <div class="flex items-center justify-end gap-4">
                                <button wire:click='editShippingDetails' class="text-accent btn-primary btn">
                                    <i class="fa fa-pencil"></i> Edit details
                                </button>

                            </div>
                        @else
                            <div class="flex items-center justify-end gap-4">
                                <button wire:click='cancelEditShipping' class="text-white text-accent btn-error btn">
                                    <i class="fa fa-times"></i>Cancel
                                </button>
                                <button wire:click='saveShippingDetails' class="text-white btn-accent btn">
                                    <i class="fa fa-save"></i> Save changes
                                </button>
                            </div>
                        @endif

                        @if (!in_array($order->shipping_status, ['completed', 'cancelled']))
                            <hr>
                            <br>
                            <div>
                                <label class="block mb-2 text-sm text-gray-800">Shipping Status</label>
                                <div x-data="{ selectedShippingStatus: @entangle('selectedShippingStatus') }" class="relative flex items-center">
                                    <select wire:change="selectShippingStatus()" wire:model="selectedShippingStatus"
                                        required
                                        class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600">
                                        <option value="">Select status</option>
                                        <option value="to ship">To ship</option>
                                        <option value="shipping">Shipping</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                                        class="absolute w-4 h-4 right-4" viewBox="0 0 24 24">
                                        <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                                        <path
                                            d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                                            data-original="#000000"></path>
                                    </svg>
                                </div>
                                @error('trackingNo')
                                    <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                @enderror
                            </div>
                            @if ($selectedShippingStatus == 'cancelled')
                                <div>
                                    <label class="block mb-2 text-sm text-gray-800">Cancellation Reason</label>
                                    <div class="relative flex items-center">
                                        <input wire:model="cancellationReason" type="text" required
                                            class="w-full px-4 py-3 text-sm text-gray-800 border border-gray-300 rounded-md !ring-0 focus:!border-primary-600"
                                            placeholder="Cancellation Reason" />
                                        <i class="fa fa-times absolute size-4 right-4 text-[#bbb]"></i>
                                    </div>
                                    @error('cancellationReason')
                                        <span class="text-xs text-red-500">{{ $message ?? 'An error occurred' }}</span>
                                    @enderror
                                </div>
                            @endif
                            <br>
                            <button wire:click='saveShippingStatusUpdate' class="text-white btn-accent btn">
                                <i class="fa fa-plus"></i> Add a status update
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @else
        @livewire('components.error-component-livewire', ['errorTitle' => 'Order not found', 'message' => 'The transaction code you entered is invalid.'])
    @endif

</div>
