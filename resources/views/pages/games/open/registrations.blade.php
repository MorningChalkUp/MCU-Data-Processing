@extends('layouts.master')

@section('body')

	<div class="container">
		<div class="row">
			<div class="com">
				<table class="table">
					<tr>
						<th scope="col">Division</th>
						<th scope="col">Men</th>
						<th scope="col">Women</th>
						<th scope="col">Total</th>
					</tr>

					@foreach($registrations as $key => $div)
						<tr>
							<th scope="row">{{ $key }}</th>
							<td>{{ number_format( $div['Men'] ) }}</td>
							<td>{{ number_format( $div['Women'] ) }}</td>
							<td>{{ number_format( $div['Men'] + $div['Women'] ) }}</td>
						</tr>
					@endforeach

				</table>
			</div>
		</div>
	</div>

@endsection