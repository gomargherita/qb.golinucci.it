@extends('errors::minimal')

@php
    $raw = isset($exception) ? trim((string) $exception->getMessage()) : '';
    $isSession = $raw === ''
        || stripos($raw, 'CSRF') !== false
        || stripos($raw, 'mismatch') !== false
        || stripos($raw, 'Page Expired') !== false;
@endphp

@section('title', $isSession ? 'Sessione scaduta' : 'Rinnovo non disponibile')
@section('code', '419')
@section('message', $isSession
    ? 'La sessione è scaduta. Torna indietro, ricarica la pagina e riprova. Non è la scadenza dei 30 giorni della polizza.'
    : $raw)
