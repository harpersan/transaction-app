@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 mx-auto max-w-6xl bg-white py-20 px-12 lg:px-24 shadow-xl mb-24">
        <a class="text-lg" href="{{ route('transactions.index', $accountId) }}">&#8592; Back</a>
        <h1 class="text-2xl mb-10 font-bold">Create Transaction</h1>

        <form action="{{ route('transactions.store', $accountId) }}" method="POST">
            @csrf
            <input type="hidden" name="account_id" value="{{ $accountId }}">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
                <div class="-mx-3 md:flex mb-6">
                    <div class="md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="uppercase tracking-wide text-black text-xs font-bold mb-2" for="company">
                            Amount*
                        </label>
                        <input name="amount" class="w-full bg-gray-200 text-black border border-gray-200 rounded py-3 px-4 mb-3" id="company" type="number" placeholder="Amount">
                        @error('amount')
                            <span class="text-red-500 text-xs italic">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="md:w-1/2 px-3">
                        <label class="uppercase tracking-wide text-black text-xs font-bold mb-2" for="location">
                            Transaction Type
                        </label>
                        <div>
                            <select name="transaction_type" class="w-full bg-gray-200 border border-gray-200 text-black py-3 px-4 pr-8 mb-3 rounded" id="location">
                                @foreach($transactionTypes as $types)
                                    <option value="{{ $types->id }}">{{ $types->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('transaction_type')
                        <span class="text-red-500 text-xs italic">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="md:flex mt-2">
                    <div class="px-3">
                        <button class="px-5 py-2 rounded-lg text-white font-bold bg-blue-500 cursor-pointer" type="submit">Create Transaction</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection





