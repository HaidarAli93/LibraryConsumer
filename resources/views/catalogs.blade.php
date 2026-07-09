<x-layout>
	<x-slot:titlePage>{{ $titlePage }}</x-slot:titlePage>
	<?php $criteria = request('criteria', 'title'); ?>
	<div class="my-5">
		<h1 class="text-center">Katalog Perpustakaan</h1>
		<h4 class="text-center">Temukan referensi, buku, dan koleksi literatur dengan mudah dan cepat.</h4>
	</div>
	<div class="card my-5">
	  <div class="card-body">
		<div class="row gy-3 my-2">
			<form action="{{ route('catalog') }}" method="GET">
				<div class="col-12">
					<div class="input-group">
					  <select name="criteria" class="form-select flex-grow-0 w-auto" aria-label="Default select example" aria-expanded="false">
						<option value="title" {{ $criteria == 'title' ? 'selected' : '' }}>Judul</option>
						<option value="subject" {{ $criteria == 'subject' ? 'selected' : '' }}>Subjek</option>
					  </select>
					  <input type="text" name="search" class="form-control" value="{{ request('search') }}" aria-label="Text input with dropdown button" placeholder="Masukkan kata kunci pencarian...">
					  <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Cari</button>
					</div>
				</div>
				<!-- Filter feature -->
				<div class="col-12">
					<div class="input-group mt-3">
					  <span class="input-group-text">Filter</span>
					  <input type="text" name="author" aria-label="Penulis" placeholder="Masukkan nama penulis..." class="form-control">
					  <input type="text" name="publisher" aria-label="Penerbit" placeholder="Masukkan nama penerbit..." class="form-control">
					  <input type="text" name="isbn" aria-label="Isbn" placeholder="Masukkan nomor ISBN..." class="form-control">
					  <input type="text" name="year" aria-label="Tahun terbit" placeholder="Masukkan tahun terbit..." class="form-control">
					</div>
				</div>
			</form>
		</div>
	  </div>
	</div>
	
	@if ($catalogs->isNotEmpty())
		<div class="row gx-3 gy-2 my-3">
			@foreach ($catalogs as $catalog)
				<div class="col-12 col-md-6">
					<div class="card mb-3" style="max-width: 750px;">
					  <div class="row g-0">
						<div class="col-4">
							<div class="ratio" style="--bs-aspect-ratio: 150%;">
								<a href="{{ route('catalog.details', ['id' => $catalog['id']]) }}">
								  <img src="{{ !empty($catalog['CoverURL']) ? asset('storage/' . $catalog['CoverURL']) : asset('storage/images/catalog/400x600.svg') }}" class="img-fluid w-100 h-100 object-fit-cover rounded-start" alt="...">
								</a>
							</div>
						</div>
						<div class="col-8">
						  <div class="card-body">
							<a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="{{ route('catalog.details', ['id' => $catalog['id']]) }}">
							  <h5 class="card-title">{{ $catalog['Title'] . ' - ' . $catalog['Subject'] }}</h5>
							</a>
							<ul class="card-text list-group list-group-flush my-2 deskripsi">
							  <li class="list-group-item"><pre>Penulis			{{ $catalog['Author'] }}</pre></li>
							  <li class="list-group-item"><pre>Penerbit			{{ $catalog['Publisher'] }}</pre></li>
							  <li class="list-group-item"><pre>Tahun terbit		{{ $catalog['PublishYear'] }}</pre></li>
							  <li class="list-group-item"><pre>ISBN			{{ $catalog['ISBN'] }}</pre></li>
							  <li class="list-group-item"><pre>Kuantitas		{{ $catalog['Quantity'] }}</pre></li>
							</ul>
							<p class="card-text"><small class="text-body-secondary">Last synced {{ $catalog->synced_at->diffForHumans() }}</small></p>
						  </div>
						</div>
					  </div>
					</div>
				</div>
			@endforeach
		</div>
		{{ $catalogs->links(); }}
		<!--
		<nav aria-label="Catalog Page Navigation">
		  <ul class="pagination">
			<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
			<li class="page-item"><a class="page-link" href="{{ route('catalog', ['page' => 1]) }}">1</a></li>
			<li class="page-item"><a class="page-link" href="{{ route('catalog', ['page' => 2]) }}">2</a></li>
			<li class="page-item"><a class="page-link" href="{{ route('catalog', ['page' => 3]) }}">3</a></li>
			<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
		  </ul>
		</nav>	
		-->
	@else
		<p class="h4 text-center my-5">{{ $message ?? 'Katalog tidak ditemukan' }}</p>
	@endif
</x-layout>
