@extends('layouts.master')

@section('body')

	<div class="container">
		<div class="row">
			<div class="com">
				<table class="table">
					<tr>
						<th scope="col">Division</th>
						@if(isset($registrations['RX']['Men']['Registrants']['Rx']))
							<th scope="col">Men</th>
							<th scope="col">Women</th>
							<th scope="col">Total</th>
						@endif
						@if(isset($registrations['RX']['Men']['Registrants']['Scaled']))
							<th scope="col">Men</th>
							<th scope="col">Women</th>
							<th scope="col">Total</th>
						@endif
					</tr>

					@foreach($registrations as $div => $d)
						<tr>
							<th scope="row">{{ $div }}</th>
							@if(isset($d['Men']['Registrants']['Rx']))
								<td>{{ number_format( $d['Men']['Registrants']['Rx'] ) }}</td>
								<td>{{ number_format( $d['Women']['Registrants']['Rx'] ) }}</td>
								<td>{{ number_format( $d['Men']['Registrants']['Rx'] + $d['Women']['Registrants']['Rx'] ) }}</td>
							@endif

							@if(isset($d['Men']['Registrants']['Scaled']))
								<td>{{ number_format( $d['Men']['Registrants']['Scaled'] ) }}</td>
								<td>{{ number_format( $d['Women']['Registrants']['Scaled'] ) }}</td>
								<td>{{ number_format( $d['Men']['Registrants']['Scaled'] + $d['Women']['Registrants']['Scaled'] ) }}</td>
							@endif
						</tr>

					@endforeach

				</table>
			</div>
		</div>
	</div>

@endsection