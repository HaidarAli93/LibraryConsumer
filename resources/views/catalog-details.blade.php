<x-layout>
	<x-slot:titlePage>{{ $titlePage }}</x-slot:titlePage>
	<a href="{{ url()->previous() }}" type="button" class="btn btn-primary my-3">Kembali</a>
	<h3 class="my-4">{{ $titlePage }}</h3>
	<div class="card mb-3" style="max-width: 100%;">
	  <div class="row g-0">
		@if (isset($catalog_details))
		<div class="col-4">
			<div class="ratio" style="--bs-aspect-ratio: 125%;">
			  <img src="{{ $catalog_details['CoverURL'] ? asset('storage/' . $catalog_details['CoverURL']) : asset('storage/images/catalog/400x600.svg') }}" class="img-fluid h-100 w-100 object-fit-cover rounded-start" alt="...">
			</div>
		</div>
		<div class="col-8">
		  <div class="card-body">
			<h5 class="card-title">{{ $catalog_details['Title'] }}</h5>
			<p class="card-text">
				<table class="table table-striped table-hover">
					<tbody>
							<tr>
								<td scope="row">ID</td>
								<td>{{ $catalog_details['external_id'] }}</td>
							</tr>
							<tr>
								<td scope="row">BIBID</td>
								<td>{{ $catalog_details['BIBID'] }}</td>
							</tr>
							<tr>
								<td scope="row">Author</td>
								<td>{{ $catalog_details['Author'] }}</td>
							</tr>
							<tr>
								<td scope="row">Edition</td>
								<td>{{ $catalog_details['Edition'] }}</td>
							</tr>
							<tr>
								<td scope="row">Publisher</td>
								<td>{{ $catalog_details['Publisher'] }}</td>
							</tr>
							<tr>
								<td scope="row">Publish Location</td>
								<td>{{ $catalog_details['PublishLocation'] }}</td>
							</tr>
							<tr>
								<td scope="row">Publish Year</td>
								<td>{{ $catalog_details['PublishYear'] }}</td>
							</tr>
							<tr>
								<td scope="row">Subject</td>
								<td>{{ $catalog_details['Subject'] }}</td>
							</tr>
							<tr>
								<td scope="row">Physical Description</td>
								<td>{{ $catalog_details['PhysicalDescription'] }}</td>
							</tr>
							<tr>
								<td scope="row">ISBN</td>
								<td>{{ $catalog_details['ISBN'] }}</td>
							</tr>
							<tr>
								<td scope="row">Nomor Panggil</td>
								<td>{{ $catalog_details['CallNumber'] }}</td>
							</tr>
							<tr>
								<td scope="row">Languages</td>
								<td>{{ $catalog_details['Languages'] }}</td>
							</tr>
							<tr>
								<td scope="row">Nomor Dewey</td>
								<td>{{ $catalog_details['DeweyNo'] }}</td>
							</tr>
							<tr>
								<td scope="row">Ketersediaan</td>
								<td>{{ $catalog_details['IsOPAC'] == 1 ? 'Tersedia' : 'Tidak tersedia' }}</td>
							</tr>
					</tbody>
				</table>
			</p>
		  </div>
		</div>
		@else
			<p class="h3">{{ $message }}</p>
		@endif
	  </div>
	</div>
	
</x-layout>
