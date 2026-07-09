<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
	<div class="container row">
		<div class="col-12 col-md-4 ">
			<a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
				<img src="{{ asset('storage/images/logo-disarpus.jpg') }}" alt="" width="95">
				<div class="lh-sm">
					<div>Disarpus Kota Bandung</div>
					<small>inlislite x.x</small>
				</div>
			</a>
		</div>
		<div class="col-12 col-md-4 text-center align-self-center">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
			  <ul class="navbar-nav mx-auto fs-4">
				<li class="nav-item">
				  <a class="nav-link" aria-current="page" href="{{ route('home') }}">Beranda</a>
				</li>
				<li class="nav-item">
				  <a class="nav-link" href="{{ route('catalog') }}">Katalog</a>
				</li>
				<!--
				<li class="nav-item">
				  <a class="nav-link disabled" aria-disabled="true">Disabled</a>
				</li>
				-->
			  </ul>
			</div>
		</div>
		<div class="col-12 col-md-4 text-center"></div>
	</div>
  </div>
</nav>
