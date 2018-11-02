@extends('layouts.master')

@section('body')

  <div class="container">
    <div class="row">
      <div class="col">
        <table class="table table-sm table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>City</th>
              <th>State</th>
              <th>Zip</th>
              <th>Country</th>
              <th>Website</th>
              <th>Type</th>
            </tr>
          </thead>
          <tbody>
            @foreach($affiliates as $affiliate)
              <tr>
                <td>@if(isset($affiliate->aid)){{ $affiliate->aid }}@endif</td>
                <td>@if(isset($affiliate->name)){{ $affiliate->name }}@endif</td>
                <td>@if(isset($affiliate->city)){{ $affiliate->city }}@endif</td>
                <td>@if(isset($affiliate->state_code)){{ $affiliate->state_code }}@endif</td>
                <td>@if(isset($affiliate->zip)){{ $affiliate->zip }}@endif</td>
                <td>@if(isset($affiliate->country_short_code)){{ $affiliate->country_short_code }}@endif</td>
                <td>@if(isset($affiliate->website))<a href="{{ $affiliate->website }}" target="_blank">Link</a>@endif</td>
                <td>@if(isset($affiliate->org_type)){{ $affiliate->org_type }}@endif</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection