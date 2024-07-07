{{-- For submenu --}}
<ul class="menu-content">
  @if (isset($menu))
    @foreach ($menu as $submenu)
      <li @if (in_array(Route::currentRouteName(), $submenu->slug)) class="active" @endif>
        <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0)' }}" class="d-flex align-items-center"
          target="{{ isset($submenu->newTab) && $submenu->newTab === true ? '_blank' : '_self' }}">
          @if (isset($submenu->icon))
            <i data-feather="{{ $submenu->icon }}"></i>
          @endif
          <span class="menu-item text-truncate">{{ translate($submenu->name) }}</span>
        </a>
        @if (isset($submenu->submenu))
          @include('admin/panels/submenu', ['menu' => $submenu->submenu])
        @endif
      </li>
    @endforeach
  @endif
</ul>
