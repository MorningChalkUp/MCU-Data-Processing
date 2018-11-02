@extends('layouts.master')

@section('body')

  <div class="container">
    <div class="row">
      <div class="col">
        AID;
        Name;
        Address;
        City;
        StateCode;
        Zip;
        CuntryCode;
        State;
        Country;
        Website;
        Type;
        Active;
        Map;
        LatLong;
        Photo;
        PhotoVersion;
        ReadyToLink;
        Kids;
        BadStanding;
        OrderNum<br>
        @foreach($affiliates as $affiliate)
          @if(isset($affiliate->aid)){{ $affiliate->aid }}@endif;
          @if(isset($affiliate->name)){{ $affiliate->name }}@endif;
          @if(isset($affiliate->address)){{ $affiliate->address }}@endif;
          @if(isset($affiliate->city)){{ $affiliate->city }}@endif;
          @if(isset($affiliate->state_code)){{ $affiliate->state_code }}@endif;
          @if(isset($affiliate->zip)){{ $affiliate->zip }}@endif;
          @if(isset($affiliate->country_short_code)){{ $affiliate->country_short_code }}@endif;
          @if(isset($affiliate->full_state)){{ $affiliate->full_state }}@endif;
          @if(isset($affiliate->country)){{ $affiliate->country }}@endif;
          @if(isset($affiliate->website)){{ $affiliate->website }}@endif;
          @if(isset($affiliate->org_type)){{ $affiliate->org_type }}@endif;
          @if(isset($affiliate->active)){{ $affiliate->active }}@endif;
          @if(isset($affiliate->show_on_map)){{ $affiliate->show_on_map }}@endif;
          @if(isset($affiliate->latlon)){{ $affiliate->latlon }}@endif;
          @if(isset($affiliate->photo)){{ $affiliate->photo }}@endif;
          @if(isset($affiliate->photo_version)){{ $affiliate->photo_version }}@endif;
          @if(isset($affiliate->ready_to_link)){{ $affiliate->ready_to_link }}@endif;
          @if(isset($affiliate->kids)){{ $affiliate->kids }}@endif;
          @if(isset($affiliate->bad_standing)){{ $affiliate->bad_standing }}@endif;
          @if(isset($affiliate->ordernum)){{ $affiliate->ordernum }}@endif
          <br>
        @endforeach
      </div>
    </div>
  </div>

@endsection