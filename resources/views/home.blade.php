<x-layout>
	<x-slot:titlePage>{{ $titlePage }}</x-slot:titlePage>
	<div class="my-5 d-flex flex-column align-items-center">
		<h1 class="text-center">Katalog Perpustakaan Disarpus Kota Bandung</h1>
		<h4 class="text-center">Cari referensi, buku, dan koleksi literatur dengan mudah dan cepat.</h4>
		<a class="btn btn-primary btn-lg my-5" href="{{ route('catalog') }}">Cari Katalog</a>
	</div>
</x-layout>
