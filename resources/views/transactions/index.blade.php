@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <a class="text-lg" href="/">&#8592; Back</a>
            <div class="flex justify-between">
                <h2 class="text-2xl font-semibold leading-tight">Transactions</h2>
                <a href="{{ route('transactions.create', $account->id) }}" class="px-5 py-2 rounded-lg text-white font-bold bg-blue-500 cursor-pointer">Add Transaction</a>
            </div>
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Id
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Reference
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                amount
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Transaction Type
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Previous Balance
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Balance After
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Invoice Total Amount
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($account->transactions as $item)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $item->id }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $item->reference }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $item->amount }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                        <span @class(['absolute inset-0 opacity-50 rounded-full',
                                            'bg-green-200' => in_array($item->transactionType->name, ['credit','refund','deposit']),
                                            'bg-yellow-200' => $item->transactionType->name == 'payment',
                                            'bg-blue-200' => $item->transactionType->name == 'discount'
                                        ]) aria-hidden></span><span class="relative">{{ $item->transactionType->name }}</span>
                                    </span>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $item->previous_balance }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $item->balance_after }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $item->invoice_total_amount }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                    <div class="flex">
                                        <a href="{{ route('transactions.edit', $item->id) }}" class="px-4 py-2 bg-green-300 rounded-lg mr-3 font-bold cursor-pointer">Edit</a>
                                        <form action="{{ route('transactions.destroy',[$item->id, $account->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-400 rounded-lg font-bold cursor-pointer">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div></div>
@endsection