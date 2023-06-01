"use strict";

const LoginPage = {
	
	mixins: [globalMixin],
	
	data: function () {
		return {
			login: "",
			password: ""
		}
	},	
	
	computed: {
	
		
	},
	
	methods: {
		postAuth() {
			this.$store.dispatch("postAuth", { login: this.login, password: this.password })
			return false;
		},
	
		
	},
	
	mounted() {

	},

	template: `	



				<!-- Content area -->
				<div class="content d-flex justify-content-center align-items-center">

					<!-- Login form -->
					<form class="login-form"  @submit.prevent="postAuth">
						<div class="card mb-0">
							<div class="card-body">
								<div class="text-center mb-3">
									<div class="d-inline-flex align-items-center justify-content-center mb-3 mt-2">
											<img src="../../../assets/images/logo_icon.svg" class="h-48px" alt="">
									</div>
									<h5 class="mb-0">Сервіс документів</h5>
									<span class="d-block text-muted">Введіть облікові дані</span>
								</div>

								<div class="mb-3">
									<label class="form-label">Логiн</label>
									<div class="form-control-feedback form-control-feedback-start">
										<input v-model="login"  type="text" class="form-control" placeholder="admin">
										<div class="form-control-feedback-icon">
											<i class="ph-user-circle text-muted"></i>
										</div>
									</div>
								</div>

								<div class="mb-3">
									<label class="form-label">Пароль</label>
									<div class="form-control-feedback form-control-feedback-start">
										<input v-model="password" type="password" class="form-control" placeholder="•••••••••••">
										<div class="form-control-feedback-icon">
											<i class="ph-lock text-muted"></i>
										</div>
									</div>
								</div>

								<div class="mb-3">
									<button type="submit" class="btn btn-primary w-100">Увійти</button>
								</div>
<!--
								<div class="text-center">
									<a href="login_password_recover.html">Forgot password?</a>
								</div>
-->
							</div>
						</div>
					</form>
					<!-- /login form -->

				</div>
				<!-- /content area -->




	`
	
};
