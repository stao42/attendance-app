@extends('layouts.app')

@section('title', '送付先住所変更 - CoachTech')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1 style="font-size: 36px; font-weight: 700; color: #000000; margin-bottom: 30px;">送付先住所変更</h1>
    
    <form action="{{ route('purchases.update_address', $product) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label for="postal_code" style="display: block; font-size: 18px; font-weight: 700; color: #000000; margin-bottom: 10px;">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" 
                   style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;">
            @error('postal_code')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>
        
        <div style="margin-bottom: 20px;">
            <label for="address" style="display: block; font-size: 18px; font-weight: 700; color: #000000; margin-bottom: 10px;">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" 
                   style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;">
            @error('address')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>
        
        <div style="margin-bottom: 30px;">
            <label for="building" style="display: block; font-size: 18px; font-weight: 700; color: #000000; margin-bottom: 10px;">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $user->building) }}" 
                   style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;">
            @error('building')
                <p style="color: red; margin-top: 5px;">{{ $message }}</p>
            @enderror
        </div>
        
        <div style="display: flex; gap: 20px;">
            <button type="submit" style="flex: 1; padding: 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 18px; cursor: pointer;">住所を更新</button>
            <a href="{{ route('purchases.create', $product) }}" style="flex: 1; padding: 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-size: 18px; text-align: center;">戻る</a>
        </div>
    </form>
</div>
@endsection
