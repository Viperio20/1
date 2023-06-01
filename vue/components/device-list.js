"use strict";

Vue.component('device-list', {
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				properties
	props: {
		
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				data
	data: function () {
		return {

		}
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				computed
	computed: {
		sectors() { return this.$store.getters.sectors },
		devices() { return this.$store.getters.devices },

	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				methods
	methods: {
	},	
	
	mounted() {
	
	
	},
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				template
	template: `
	
<div class="card">
	<div class="card-header d-sm-flex align-items-sm-center py-sm-0">
		<h5 class="py-sm-2 my-sm-1">Стан пристроїв</h5>
		<div class="mt-2 mt-sm-0 ms-sm-auto">
			<select class="form-select">
				<option value="val1" selected="">Трав, 15 - Трав, 21</option>
				<option value="val2">Трав, 08 - Трав 14</option>
				<option value="val3">Трав, 01 - Трав, 07</option>
				<option value="val4">Квiт, 24 - Квiт, 30</option>
			</select>
		</div>
	</div>

	<div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">
		<div class="d-flex align-items-center mb-3 mb-lg-0">
			<div id="tickets-status"></div>
			<div class="ms-3">
				<div class="d-flex align-items-center">
					<h5 class="mb-0">14,327</h5>
					<span class="text-success ms-2">
						<i class="ph-arrow-up fs-base lh-base align-top"></i>
						(+2.9%)
					</span>
				</div>
				<span class="d-inline-block bg-success rounded-pill p-1 me-1"></span>
				<span class="text-muted">{{$store.getters.curr_time}}</span>
			</div>
		</div>

		<div class="d-flex align-items-center mb-3 mb-lg-0">
			<a href="#" class="bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-2">
				<i class="ph-folders"></i>
			</a>
			<div class="ms-3">
				<h5 class="mb-0">540</h5>
				<span class="text-muted">Запитiв</span>
			</div>
		</div>

		<div class="d-flex align-items-center mb-3 mb-lg-0">
			<a href="#" class="bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-2">
				<i class="ph-arrow-arc-left"></i>
			</a>
			<div class="ms-3">
				<h5 class="mb-0">00:02:23</h5>
				<span class="text-muted">Середній час відповіді</span>
			</div>
		</div>

		<button type="button" class="btn btn-light">
			<i class="ph-file-pdf me-2"></i>
			Роздрукувати
		</button>
	</div>

	<div class="table-responsive">
		<table class="table text-nowrap">
			<thead>
				<tr>
					<th style="width: 50px">Таймаут</th>
					<th style="width: 300px;">Пристрiй</th>
					<th>Опис</th>
					<th class="text-center" style="width: 20px;">
						<i class="ph-dots-three"></i>
					</th>
				</tr>
			</thead>
			<tbody>


			<template v-for="sector in sectors">

				<tr class="table-light">
					<td colspan="3">{{sector.title}}</td>
					<td class="text-end">
						<span class="badge bg-primary rounded-pill">6</span>
					</td>
				</tr>

				<template v-for="device in devices">
					<tr v-if="device.sector_id == sector.id" :data-status-id="device.status_id">
						<td class="text-center">
							<h6 class="mb-0">{{device.time_last}}</h6>
							<div class="fs-sm text-muted lh-1">{{device.time_dim}}</div>
						</td>
						<td >
							<div class="d-flex align-items-center">
								<a href="#" :class=" device.class " class="d-inline-flex align-items-center justify-content-center lh-1 rounded-pill w-40px h-40px me-3">
									<span class="letter-icon">{{device.code}}</span>
								</a>
								<div>
									<a href="#" class="text-body fw-semibold letter-icon-title">{{device.title}}</a>
									<div class="d-flex align-items-center text-muted fs-sm">
										<span :class=" device.class "  class="rounded-pill p-1 me-2"></span>
										{{device.name}}
									</div>
								</div>
							</div>
						</td>
						<td>
							<a href="#" class="text-body">
								<div class="fw-semibold">{{device.destination}}</div>
								<span class="text-muted">{{device.status_text}}</span>
							</a>
						</td>
						<td class="text-center">
							<div class="dropdown">
								<a href="#" class="text-body" data-bs-toggle="dropdown">
									<i class="ph-list"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="#" class="dropdown-item">
										<i class="ph-arrow-bend-up-left me-2"></i>
										Додати нотатку
									</a>
									<a href="#" class="dropdown-item">
										<i class="ph-clock-counter-clockwise me-2"></i>
										Вiдкрити iсторiю
									</a>
									<div class="dropdown-divider"></div>
									<a href="#" class="dropdown-item">
										<i class="ph-checks text-success me-2"></i>
										Позначити як робочий
									</a>
									<a href="#" class="dropdown-item">
										<i class="ph-eye-slash text-primary-100 me-2"></i>
										Позначити як вимкнутий
									</a>									
									<a href="#" class="dropdown-item">
										<i class="ph-alarm text-danger me-2"></i>
										Подати тривогу
									</a>
								</div>
							</div>
						</td>
					</tr>
				</template>

			</template>	

			
			</tbody>
		</table>
	</div>
</div>
		

`
});


