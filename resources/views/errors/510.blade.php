@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', 'Errore')
@section('message', __($exception->getMessage() ?: 'Il carrello non è rinnovabile in questo momento.'))
