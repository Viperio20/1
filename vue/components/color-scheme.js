"use strict";

const themeSwitcher = function() {


    //
    // Setup module components
    //

    // Theme
    const layoutTheme = function() {
        var primaryTheme = 'light';
        var secondaryTheme = 'dark';
        var storageKey = 'theme';
        var colorscheme = document.getElementsByName('main-theme');
        var mql = window.matchMedia('(prefers-color-scheme: ' + primaryTheme + ')');

        // Changes the active radiobutton
        function indicateTheme(mode) {
			
            for(var i = colorscheme.length; i--; ) {
                if(colorscheme[i].value == mode) {
                    colorscheme[i].checked = true;
                    colorscheme[i].closest('.list-group-item').classList.add('bg-primary', 'bg-opacity-10', 'border-primary');
                }
                else {
                    colorscheme[i].closest('.list-group-item').classList.remove('bg-primary', 'bg-opacity-10', 'border-primary');
                }
            }
        };

        // Turns alt stylesheet on/off
        function applyTheme(mode) {
            var st = document.documentElement;
            if (mode == primaryTheme) {
                st.removeAttribute('data-color-theme');
            }
            else if (mode == secondaryTheme) {
                st.setAttribute('data-color-theme', 'dark');
            }
            else {
                if (!mql.matches) {
                    st.setAttribute('data-color-theme', 'dark');
                }
                else {
                    st.removeAttribute('data-color-theme');
                }
            }
        };

        // Handles radiobutton clicks
        function setTheme(e) {
            var mode = e.target.value;
            document.documentElement.classList.add('no-transitions');
            if ((mode == primaryTheme)) {
                localStorage.removeItem(storageKey);
            }
            else {
                localStorage.setItem(storageKey, mode);
            }
            // When the auto button was clicked the auto-switcher needs to kick in
            autoTheme(mql);
        };

        // Handles the media query evaluation, so it expects a media query as parameter
        function autoTheme(e) {
            var current = localStorage.getItem(storageKey);
            var mode = primaryTheme;
            var indicate = primaryTheme;
            // User set preference has priority
            if ( current != null) {
                indicate = mode = current;
            }
            else if (e != null && e.matches) {
                mode = primaryTheme;
            }
            applyTheme(mode);
            indicateTheme(indicate);
            setTimeout(function() {
                document.documentElement.classList.remove('no-transitions');
            }, 100);
        };

        // Create an event listener for media query matches and run it immediately
        autoTheme(mql);
        mql.addListener(autoTheme);

        // Set up listeners for radio button clicks */
        for(var i = colorscheme.length; i--; ) {
            colorscheme[i].onchange = setTheme;
        }
    };

    // Direction
    const layoutDirection = function() {
        var dirSwitch = document.querySelector('[name="layout-direction"]');

        if (dirSwitch) {
            var dirSwitchSelected = localStorage.getItem("direction") !== null && localStorage.getItem("direction") === "rtl";
            dirSwitch.checked = dirSwitchSelected;

            function resetDir() {
                if (dirSwitch.checked) {
                    document.getElementById("stylesheet").setAttribute('href', 'assets/css/rtl/all.min.css');
                    document.documentElement.setAttribute("dir", "rtl");
                    localStorage.setItem("direction", "rtl");
                } else {
                    document.getElementById("stylesheet").setAttribute('href', 'assets/css/ltr/all.min.css');
                    document.documentElement.setAttribute("dir", "ltr");
                    localStorage.removeItem("direction");
                }
            }

            dirSwitch.addEventListener("change", function () {
                resetDir();
            });
        }
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            layoutTheme();
            layoutDirection();
        }
    }
}();


Vue.component('color-scheme', {
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				properties
	props: {
		drop_pos: { default: "right" },
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				data
	data: function () {
		return {

		}
	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				computed
	computed: {
		

	},
	
	// ~~~~~~~~~~~~~~~~~~~~~~				methods
	methods: {
		
		createPlugin() {
			
			((localStorage.getItem('theme') == 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) || localStorage.getItem('theme') == 'dark') && document.documentElement.setAttribute('data-color-theme', 'dark');
			localStorage.getItem('direction') == 'rtl' && document.getElementById("stylesheet").setAttribute('href', 'assets/css/rtl/all.min.css');
			localStorage.getItem('direction') == 'rtl' && document.documentElement.setAttribute('dir', 'rtl');

			themeSwitcher.init();
		},		
		
	},	
	
	mounted() {
		
		if (document.readyState === "complete") {
			$(document).ready( () =>  {
				this.createPlugin();
			});
		} else {
			$(document).ready( () =>  {
				this.createPlugin()
			});
		}			
		
	},
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~				template
	template: `
	
					<li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
						<a href="#" class="navbar-nav-link navbar-nav-link-icon rounded-pill" data-bs-toggle="dropdown" data-bs-auto-close="outside">
							<i class="ph-sun"></i>
						</a>

						<div class="dropdown-menu wmin-lg-400 p-0" :style=" drop_pos == 'left' ?  'right: 0; left: auto' : ''">
							<div class="d-flex align-items-center p-3">
								<h6 class="mb-0">Color mode</h6>
							</div>

							<div class="dropdown-menu-scrollable pb-2">
								<a href="#" class="list-group-item dropdown-item align-items-start text-wrap py-2">
									<div class="status-indicator-container me-3">
										<i class="ph-sun p-2"></i>
									</div>

									<div class="flex-1">
										<span class="fw-semibold">Light theme</span>
										<span class="text-muted float-end fs-sm px-3">
											<input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="light">
										</span>
										<div class="text-muted">Set light theme or reset to default</div>
									</div>
								</a>
								
								<a href="#" class="list-group-item dropdown-item align-items-start text-wrap py-2">
									<div class="status-indicator-container me-3">
										<i class="ph-moon p-2"></i>
									</div>

									<div class="flex-1">
										<span class="fw-semibold">Dark theme</span>
										<span class="text-muted float-end fs-sm px-3">
											<input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="dark">
										</span>
										<div class="text-muted">Switch to dark theme</div>
									</div>
								</a>								

								<a href="#" class="list-group-item dropdown-item align-items-start text-wrap py-2">
									<div class="status-indicator-container me-3">
										<i class="ph-translate p-2"></i>
									</div>

									<div class="flex-1">
										<span class="fw-semibold">Auto theme</span>
										<span class="text-muted float-end fs-sm px-3">
											<input type="radio" class="form-check-input cursor-pointer ms-auto" name="main-theme" value="auto">
										</span>
										<div class="text-muted">Set theme based on system mode</div>
									</div>
								</a>

							</div>

						</div>
					</li>	

`
});


