@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Non si può procedere con il rinnovo, perché sono trascorsi almeno 30 giorni dalla scadenza della polizza.'))
