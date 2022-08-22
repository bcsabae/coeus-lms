@extends('layouts.app')

@section('title', 'Pay')

@section('page-title')
    <h1>Payment</h1>
@endsection

@section('content')
    <div class="container p-0 mb-5">
        <form method="post" action="{{route('repay.webhook')}}" class="w-50 mx-auto">
            @csrf
            <div class="form-group">
                <label>Would you like the payment to succeed?</label>
                <input type="radio" id="yes" class="form-control" required name="is_successful" value="yes">
                <label for="yes">Yes</label>
                <input type="radio" id="no" class="form-control" required name="is_successful" value="no">
                <label for="yes">No</label>
            </div>
            <input type="hidden" name="vendor_uid" value="@php(rand())">
            <input type="hidden" name="payment_uid" value="{{$id}}">
            <button type="submit" class="btn btn-secondary">Make test payment</button>
        </form>
    </div>
@endsection
