<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Columns\SumColumn;

class OrderTable extends DataTableComponent
{
    protected $model = Order::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->model::query()
            ->where(function($query) {
                return $query
                    ->where('user_id', Auth::id())
                    ->orWhereHas('shop', fn($q)=>$q->where('user_id', Auth::id()));
            })
            ->orderBy('id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable(),
            Column::make("Transaction Code", "transaction_code")
                ->searchable(),
            Column::make("Billing Customer Name", 'billing_customer_name'),
            Column::make("Items")->label(
                fn($row, Column $column) => view('order_table.items')->with(
                    [
                        'row' => $row

                    ]
                )
            )->html(),
            Column::make("Created at", "created_at")->format(fn($v) => $v->format('M d, Y h:i:s A'))
                ->sortable(),
            Column::make("Updated at", "updated_at")->format(fn($v) => $v->format('M d, Y h:i:s A'))
                ->sortable(),
            Column::make("Payment Status", "payment_status"),
            Column::make("Shipping Status", "shipping_status"),
            Column::make("Total", "total")->format(function ($v) {
                return '&#8369;' . number_format($v, 2);
            })->html(),
            Column::make('Action')
                ->label(
                    fn($row, Column $column) => view('actions.order_table_actions')->with(
                        [
                            'transactionCode' => $row->transaction_code,

                        ]
                    )
                )->html(),
        ];
    }
}