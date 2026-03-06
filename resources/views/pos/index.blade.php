@extends('layouts.pos')

@section('title', $title ?? 'POS Ermis')

@section('content')
    <main class="min-h-screen p-4 md:p-6">
        <livewire:pos-dashboard />
    </main>
@endsection

