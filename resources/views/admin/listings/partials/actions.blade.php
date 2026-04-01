<div class="btn-list flex-nowrap">
    <a href="{{ route('admin.listings.show', $listing) }}" class="btn btn-sm btn-ghost-secondary" title="{{ __('common.view') }}">
        <i class="ti ti-eye icon"></i>
    </a>
    <a href="{{ route('admin.listings.edit', $listing) }}" class="btn btn-sm btn-ghost-primary" title="{{ __('common.edit') }}">
        <i class="ti ti-pencil icon"></i>
    </a>
    @if($listing->status === \App\Models\Listing::STATUS_PENDING)
    <button class="btn btn-sm btn-ghost-success btn-approve-listing" data-url="{{ route('admin.listings.approve', $listing) }}" title="{{ __('admin.listings.approve') }}">
        <i class="ti ti-check icon"></i>
    </button>
    <button class="btn btn-sm btn-ghost-danger btn-reject-listing" data-url="{{ route('admin.listings.reject', $listing) }}" title="{{ __('admin.listings.reject') }}">
        <i class="ti ti-x icon"></i>
    </button>
    @endif
    @if($listing->status === \App\Models\Listing::STATUS_ACTIVE)
    <button class="btn btn-sm btn-ghost-warning btn-passive-listing" data-url="{{ route('admin.listings.passive', $listing) }}" title="{{ __('admin.listings.passive') }}">
        <i class="ti ti-eye-off icon"></i>
    </button>
    @endif
    <button class="btn btn-sm btn-ghost-yellow btn-toggle-featured" data-url="{{ route('admin.listings.toggle-featured', $listing) }}"
            title="{{ $listing->is_featured ? __('admin.listings.remove_featured') : __('admin.listings.set_featured') }}">
        <i class="ti ti-star icon"></i>
    </button>
    <button class="btn btn-sm btn-ghost-danger btn-delete-listing" data-url="{{ route('admin.listings.destroy', $listing) }}" title="{{ __('common.delete') }}">
        <i class="ti ti-trash icon"></i>
    </button>
</div>
