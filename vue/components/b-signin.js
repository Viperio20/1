"use strict";

Vue.component('b-signin', {
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				properties
	props: {
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				data
	data: function () {
		return {
			login: "",
			password: "",
			account: {},
			confirm_password: "",			
		}
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				computed
	computed: {},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				methods
	methods: {
		postAuth() {
			this.$store.dispatch("postAuth", { login: this.login, password: this.password })
			return false;
		},		
		
		async register() {
		
			let 
				res = await FW.action.request( { account: this.account }, "post/user/register" );
			
			
			if ( res.success ) {		
				this.$store.dispatch("postAuth", { login: this.account.email, password: this.account.password })
			};
			
			return false;
		},			
	},	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				mounted
	mounted() {},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				watch
	watch: {},	
	

	template: `
		<div class="modal fade modal_style" id="login_reg">
			<div class="modal-dialog modal-send-dialog">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header" style="border-bottom: none;">
						<div class="modal-header__title">Вход/Регистрация</div>
						<div class="close_btn" data-dismiss="modal"></div>
					</div>
					<!-- Modal body -->
					<div class="modal-body modal-send-body">
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#login_modal">Войти</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#reg_modal">Зарегистрироваться</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane container active" id="login_modal">
								<div class="modal-form">
									<form @submit.prevent="postAuth">
										<div class="modal-form-group">
											<label for="login_login">Логин (email):</label>
											<input v-model="login" type="text" placeholder="mail@gmail.com" id="login_login" required>
										</div>
										<div class="modal-form-group">
											<label for="login_pass">Пароль:</label>
											<input v-model="password" type="text" placeholder="Введите пароль" id="login_pass" required>
										</div>
										<div class="modal-form-group">
											<input type="submit" value="Войти">
										</div>
									</form>
								</div>
							</div>
							<div class="tab-pane container fade" id="reg_modal">
								<div class="modal-form">
									<form @submit.prevent="register">
										<div class="modal-form-group">
											<label for="reg_modal_login">Имя:</label>
											<input v-model="account.name" type="text" placeholder="Имя" id="reg_modal_login" required>
										</div>
										<div class="modal-form-group">
											<label for="reg_modal_phone">Телефон:</label>
											<input v-model="account.phone" type="text" placeholder="Телефон" id="reg_modal_phone" required>
										</div>
										<div class="modal-form-group">
											<label for="reg_modal_mail">Email:</label>
											<input v-model="account.email" type="email" placeholder="Email" id="reg_modal_mail" required>
										</div>
										<div class="modal-form-group">
											<label for="login_pass1">Пароль:</label>
											<input v-model="account.password" type="text" placeholder="Введите пароль" id="login_pass1" required>
										</div>

										
										<div class="modal-form-group">
											<input type="submit" value="Зарегистрироваться">
										</div>
									</form>	
								</div>
							</div>
						</div>
		
					</div>
				</div>
			</div>
		</div>	
	`
});


