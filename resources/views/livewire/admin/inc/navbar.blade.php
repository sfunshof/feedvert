    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid" x-data="{ 
                activeTab: 'reports',
                logoutFunc : function() {}
            }">
            <!-- Brand -->
            <a class="navbar-brand p-0 me-4" href="#">
                <img src="{{ asset('custom/app/img/clients/' . Auth::user()->companyID . '/'. Auth::user()->companySettings->logo) }}"
                    class="img-fluid" alt="Card Image" style="max-height: 40px;">
            </a>
            <!-- Current Page Indicator (Visible on Mobile/Tablet) -->
            <div class="navbar-brand-wrapper">
                <span class="current-page-indicator"
                    x-text="activeTab === 'reports' ? 'Analytics' : 
                    activeTab === 'users' ? 'User Management' : 
                    activeTab === 'menu-items' ? 'Menu Items' : 
                    activeTab === 'menu-meals' ? 'Menu Meals' : 
                    activeTab === 'menu-categories' ? 'Menu Categories' : 
                    activeTab === 'menu-subCategories' ? 'Menu Sub-Categories' : 
                    activeTab === 'menu-modifiers' ? 'Menu Modifiers' : 
                    activeTab === 'menu-ingredients' ? 'Menu Ingredients' : 
                    activeTab === 'profile-account' ? 'My Account' : ''"
                    >
                </span>
            </div>

            <!-- Toggle Button -->
            <button class="navbar-toggler p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar content with default width -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <!-- Reports -->
                    <li class="nav-item">
                        <a class="nav-link" :class="{ 'active': activeTab === 'reports' }" 
                           href="#"  @click="activeTab = 'reports';$wire.show_reports()">
                            <i class="bi bi-graph-up"></i> Analytics
                        </a>
                    </li>

                    <div class="nav-divider d-none d-lg-block"></div>

                    <!-- Users -->
                    <li class="nav-item">
                        <a class="nav-link" :class="{ 'active': activeTab === 'users' }" 
                          href="#"  @click="activeTab = 'users';$wire.show_users()">
                            <i class="bi bi-house-door"></i> Users
                        </a>
                    </li>

                    <div class="nav-divider d-none d-lg-block"></div>

                    <!-- Menu Management -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" 
                        :class="{ 'active': activeTab.startsWith('menu-') }" 
                        href="#" 
                        id="productsDropdown" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            <i class="bi bi-box"></i> Menu Management
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                            <li>
                                <a class="dropdown-item" 
                                :class="{ 'active': activeTab === 'menu-items' }" 
                                href="#" 
                                @click="activeTab = 'menu-items'; $wire.show_items()">
                                    <i class="bi bi-list-ul"></i> Items
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" 
                                :class="{ 'active': activeTab === 'menu-meals' }" 
                                href="#" 
                                @click="activeTab = 'menu-meals'; $wire.show_meals()">
                                    <i class="bi bi-tags"></i> Meals
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" 
                                :class="{ 'active': activeTab === 'menu-categories' }" 
                                href="#" 
                                @click="activeTab = 'menu-categories'; $wire.show_categories()">
                                    <i class="bi bi-tags"></i> Categories
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" 
                                :class="{ 'active': activeTab === 'menu-subCategories' }" 
                                href="#" 
                                @click="activeTab = 'menu-subCategories'; $wire.show_subCategories()">
                                    <i class="bi bi-tags"></i> Sub-Categories
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" 
                                :class="{ 'active': activeTab === 'menu-modifiers' }" 
                                href="#" 
                                @click="activeTab = 'menu-modifiers'; $wire.show_modifiers()">
                                    <i class="bi bi-tags"></i> Modifiers
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" 
                                :class="{ 'active': activeTab === 'menu-ingredients' }" 
                                href="#" 
                                @click="activeTab = 'menu-ingredients'; $wire.show_ingredients()">
                                    <i class="bi bi-tags"></i> Ingredients
                                </a>
                            </li>
                        </ul>
                    </li>

                    <div class="nav-divider d-none d-lg-block"></div>

                 </ul>

                <!-- Right side items Profile-->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <!-- Profile Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" 
                        :class="{ 'active': activeTab.startsWith('profile-') }" 
                        href="#" 
                        id="userDropdown" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" 
                                :class="{ 'active': activeTab === 'profile-account' }" 
                                href="#" 
                                @click="activeTab = 'profile-account'; $wire.show_myAccount()">
                                    <i class="bi bi-person"></i> My Account
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" 
                                :class="{ 'active': activeTab === 'profile-logout' }" 
                                href="#" 
                                @click="activeTab = 'profile-logout'; logoutFunction();">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>