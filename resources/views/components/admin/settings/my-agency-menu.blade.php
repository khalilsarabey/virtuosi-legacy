<a href="{{ route('admin.settings.branding') }}" class="btn btn {{ request()->is('v2/admin/settings/branding') ? 'btn-primary' : 'btn-outline-primary' }} rounded-left">Branding</a>
<a href="{{ route('admin.settings.top-menu-links') }}" class="btn {{ request()->is('v2/admin/settings/menu-links') ? 'btn-primary' : 'btn-outline-primary' }}">Top Menu Links</a>
<a href="{{ route('admin.settings.footer') }}" class="btn {{ request()->is('v2/admin/settings/footer') ? 'btn-primary' : 'btn-outline-primary' }}">Footer</a>
<a href="{{ route('admin.settings.modules') }}" class="btn {{ request()->is('v2/admin/settings/modules') ? 'btn-primary' : 'btn-outline-primary' }} rounded-right">Modules</a>
<a href="{{ route('admin.settings.email') }}" class="btn {{ request()->is('v2/admin/settings/email') ? 'btn-primary' : 'btn-outline-primary' }} rounded-right">Email Settings</a>
<a href="{{ route('admin.settings.currencies') }}" class="btn {{ request()->is('v2/admin/settings/currencies') ? 'btn-primary' : 'btn-outline-primary' }} rounded-right">Currency Settings</a>
